<?php

namespace App\Services\Auth\Providers;

use Illuminate\Support\Carbon;
use Laravel\Socialite\AbstractUser;
use SocialiteProviders\Manager\OAuth2\User as OAuth2User;

class TelegramProvider extends AbstractProvider
{
    protected array $scopes = [
    ];

    public function getRedirectUrl(): string
    {
        return $this->getProvider()->getButton();
    }

    public function user(): AbstractUser
    {
        return $this->getProvider()->user();
    }

    public function userByInitData(string $initData): ?AbstractUser
    {
        parse_str($initData, $data);

        logger(__METHOD__, $data);

        if (empty($data['hash']) || empty($data['user']) || empty($data['auth_date'])) {
            return null;
        }

        // Защита от аутентификации с помощью перехваченного когда-то запроса.
        // `auth_date` - таймстемп получения ответа от Телеграма.
        // Если окажется, что `auth_date` может долго не обновляться в SPA-приложении, то увеличить время до 24 часов.
        // Если и это не поможет, нужно форсировать обновление на фронтенде.
        if (Carbon::createFromTimestamp($data['auth_date'])->addMinutes(15)->isPast()) {
            return null;
        }

        $userData = json_decode($data['user'], true);
        if (empty($userData['id'])) {
            return null;
        }

        $hash = $data['hash'];
        unset($data['hash']);
        ksort($data);

        $checkArray = array_map(
            fn ($key, $value) => "$key=$value",
            array_keys($data),
            array_values($data),
        );
        $checkString = implode("\n", $checkArray);
        $secretKey = hash_hmac('sha256', config('services.telegram.client_secret'), 'WebAppData', true);
        if (bin2hex(hash_hmac('sha256', $checkString, $secretKey, true)) != $hash) {
            return null;
        }

        return (new OAuth2User())->setRaw($userData)->map([
            'id' => $userData['id'],
            'nickname' => $userData['username'] ?? 'tg'.$userData['id'],
            'name' => $userData['first_name'] ?? null,
            'email' => $userData['email'] ?? null,  // Вряд ли email приходит, но на всякий случай пусть будет
            'avatar' => $userData['photo_url'] ?? null,
        ]);
    }

    protected function getProviderName(): string
    {
        return 'telegram';
    }
}
