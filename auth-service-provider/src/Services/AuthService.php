<?php

namespace Testtask\AuthServiceProvider\Services;

use Arr;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Testtask\AuthServiceProvider\Contracts\AuthServiceContract;
use Testtask\AuthServiceProvider\Contracts\UserContract;
use Testtask\AuthServiceProvider\Enums\CacheKeys;
use Testtask\AuthServiceProvider\Exceptions\AuthFailedException;
use Testtask\AuthServiceProvider\Exceptions\AuthServiceException;

class AuthService implements AuthServiceContract
{
    public function __construct(
        protected array $config
    ) {
    }

    /**
     * @inheritdoc
     * @throws AuthFailedException|AuthServiceException
     */
    public function getByToken(string $token): array
    {
        return $this->useCache(
            sprintf(CacheKeys::AUTH_TOKEN->value, $token),
            $this->config['cache']['ttl_token'],
            function () use ($token) {
                $response = Http::withToken($token)
                    ->asJson()
                    ->acceptJson()
                    ->get($this->config['base_url'].'/auth/me');

                if ($response->unauthorized()) {
                    throw new AuthFailedException(401, 'Invalid token');
                }
                if ($response->failed()) {
                    throw new AuthServiceException(500, 'Unknown error');
                }

                return $response->json()['data'];
            }
        );
    }


    /**
     * @inheritdoc
     * @throws AuthFailedException|AuthServiceException
     */
    public function getById(string $id): ?array
    {
        $users = $this->getByIds([$id]);

        return $users[0] ?? null;
    }

    /**
     * @inheritdoc
     * @return array|null
     */
    public function getByEmail(string $email): ?array
    {
        $response = Http::withToken($this->config['service_token'])
            ->asJson()
            ->acceptJson()
            ->post($this->config['base_url'].'/internal/user/by-email', [
                'email' => $email
            ]);

        if ($response->unauthorized()) {
            throw new AuthFailedException(401, 'Invalid token');
        }
        if ($response->failed()) {
            throw new AuthServiceException(500, 'Unknown error');
        }

        return $response->json()['data'] ?? null;
    }

    /**
     * @inheritdoc
     * @throws AuthFailedException|AuthServiceException
     */
    public function getByIds(array $ids): array
    {
        $response = Http::withToken($this->config['service_token'])
            ->asJson()
            ->acceptJson()
            ->post($this->config['base_url'].'/internal/user/by-ids', [
                'ids' => $ids
            ]);

        if ($response->unauthorized()) {
            throw new AuthFailedException(401, 'Invalid token');
        }
        if ($response->failed()) {
            throw new AuthServiceException(500, 'Unknown error');
        }

        return $response->json()['data'];
    }

    public function register(
        ?string $first_name,
        ?string $last_name,
        ?string $email,
        string $password,
        ?string $provider = null,
        ?string $providerId = null,
        ?string $promoCode = null
    ): void {
        $response = Http::withToken($this->config['service_token'])
            ->asJson()
            ->acceptJson()
            ->post($this->config['base_url'].'/internal/register/register', [
                'promo_code' => $promoCode,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => $password,
                'provider' => $provider,
                'provider_id' => $providerId,
            ]);

        Log::info($response->body());

        if ($response->unauthorized()) {
            throw new AuthFailedException(401, 'Invalid token');
        }
        if ($response->failed()) {
            throw new AuthServiceException(500, 'Unknown error');
        }
    }

    public function impersonate(int $userId): string
    {
        $response = Http::withToken($this->config['service_token'])
            ->asJson()
            ->acceptJson()
            ->post($this->config['base_url'].'/internal/auth/impersonate', [
                'user_id' => $userId,
            ]);

        if ($response->unauthorized()) {
            throw new AuthFailedException(401, 'Invalid token');
        }
        if ($response->failed()) {
            throw new AuthServiceException(500, 'Unknown error');
        }

        return $response->json('data');
    }

    public function updateUser(UserContract $user): bool
    {

        $response = Http::withToken($this->config['service_token'])
            ->asJson()
            ->acceptJson()
            ->put(
                $this->config['base_url']."/internal/user/{$user->getKey()}",
                Arr::only($user->toArray(), $user->getChanges())
            );

        if ($response->unauthorized()) {
            throw new AuthFailedException(401, 'Invalid token');
        }
        if ($response->failed()) {
            throw new AuthServiceException(500, 'Unknown error');
        }

        return true;
    }

    protected function useCache($key, $ttl, Closure $callback)
    {
        if (!$this->config['cache']['enabled']) {
            return $callback();
        }

        return Cache::driver($this->config['cache']['driver'])
            ->remember(
                $this->config['cache']['prefix'].$key,
                $ttl,
                $callback
            );
    }

}
