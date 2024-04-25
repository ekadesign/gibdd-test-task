<?php

namespace App\Services\Auth;

use App\Events\UserCreatedEvent;
use App\Jobs\AvatarDownloadJob;
use App\Models\User;
use App\Models\UserSocial;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Exception;
use hisorange\BrowserDetect\Facade as Browser;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Socialite\AbstractUser;
use Throwable;

class AuthService
{
    public function __construct(
        protected array $providers
    ) {
    }

    public function redirect($provider): string
    {
        return $this->getProvider($provider)->getRedirectUrl();
    }

    public function user($provider, ?User $currentUser = null): User
    {
        $socialiteUser = $this->getProvider($provider)->user();

        return $this->userBySocialite($socialiteUser, $provider, $currentUser);
    }

    public function userByInitData(string $initData, string $provider): ?User
    {
        $socialiteUser = $this->getProvider($provider)->userByInitData($initData);

        if (!$socialiteUser) {
            return null;
        }

        return $this->userBySocialite($socialiteUser, $provider);
    }

    public function getTokenUser(User $user): NewAccessToken
    {
        $ip = request()->ip() ?? null;
        $token = $user->createToken(
            sprintf('%s, %s', Browser::browserName(), $ip),
            ['*'],
            now()->addYear()
        );

        $accessToken = $token->accessToken;
        $accessToken->user_agent = request()->userAgent() ?? null;
        $accessToken->ip = $ip;
        $accessToken->save();

        return $token;
    }

    protected function userBySocialite(AbstractUser $socialiteUser, string $provider, ?User $currentUser = null): ?User
    {
        DB::beginTransaction();

        try {
            /** @var UserSocial $userSocial */
            $userSocial = UserSocial::query()
                ->where('provider_name', $provider)
                ->where('provider_id', $socialiteUser->getId())
                ->first();

            // Если пользователь уже залогинен, создаём привязку для текущего пользователя.
            if ($currentUser) {
                if ($userSocial && $userSocial->user_id !== $currentUser->id) {
                    DB::rollBack();

                    throw new Exception('This social already linked to other account');
                }

                if (!$userSocial) {
                    $currentUser->socials()->create([
                        'provider_name' => $provider,
                        'provider_id'   => $socialiteUser->getId(),
                        'nickname'      => $socialiteUser->getNickname(),
                    ]);
                }

                DB::commit();

                return $currentUser;
            }

            // Если у нас уже есть такая привязка пользователя к провайдеру, просто возвращаем этого пользователя.
            if ($userSocial) {
                DB::commit();

                return $userSocial->user;
            }

            // У нас нет такой привязки пользователя к провайдеру.
            // Если от провайдера пришёл email, ищем такого пользователя.
            /** @var User $user */
            if ($socialiteUser->getEmail()) {
                $user = User::query()
                    ->where('email', $socialiteUser->getEmail())
                    ->first();
            }

            // Если подходящий пользователь не найден, создаём его.
            if (empty($user)) {
                $user = User::query()
                    ->create([
                        'name'              => $socialiteUser->getName(),
                        'nickname'          => $socialiteUser->getNickname(),
                        'email'             => $socialiteUser->getEmail(),
                        'email_verified_at' => $socialiteUser->getEmail() ? now() : null,
                        'password'          => bcrypt(Str::random()),
                        'last_name'         => $socialiteUser['last_name'] ?? null,
                    ]);
            }

            // Сохраняем привязку пользователя к провайдеру.
            $user->socials()->create([
                'provider_name' => $provider,
                'provider_id'   => $socialiteUser->getId(),
                'nickname'      => $socialiteUser->getNickname(),
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        if ($user->wasRecentlyCreated) {
            UserCreatedEvent::dispatch($user);
        }

        if ($socialiteUser->getAvatar() && !$user->avatar) {
            dispatch(new AvatarDownloadJob($user, $socialiteUser->getAvatar()));
        }

        return $user;
    }

    /**
     * @param $provider
     * @return mixed
     * @throws Exception
     */
    protected function getProvider($provider): Providers\AbstractProvider
    {
        if (!isset($this->providers[$provider])) {
            throw new Exception('Unknown provider name');
        }

        if (is_string($this->providers[$provider])) {
            $this->providers[$provider] = new $this->providers[$provider];
        }

        return $this->providers[$provider];
    }

    public function createUserIfNotExist(array $data): ?User
    {
        // регистрируем пользователя сразу после отправки кода если его не было
        $emailOrPhone = Arr::get($data, 'email') ? ['email' => Arr::get($data, 'email')] : ['phone' => Arr::get($data, 'phone')];

        $user = User::query()->firstOrNew($emailOrPhone, $data);

        if (!$user->exists) {
            try {
                $user->first_name = Arr::get($data, 'first_name');
                $user->last_name = Arr::get($data, 'last_name');
                $user->email = Arr::get($data, 'email');
                $user->phone = Arr::get($data, 'phone');
                $user->password = Arr::get($data, 'password') ? bcrypt($data['password']) : null;
                $user->created_at = Carbon::now();
                $user->updated_at = Carbon::now();
                $user->save();

                if (isset($data['provider'])) {
                    $user->socials()->create($data['provider']);
                }

            } catch (Throwable $exception) {
                Log::error(__METHOD__.' '.$exception->getMessage(), $data + compact('exception'));
                throw $exception;
            }

            UserCreatedEvent::dispatch($user);
        }

        return $user;
    }
}
