<?php

namespace App\Models;

use Carbon\Carbon;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use LaravelTool\EloquentExternalEventsServer\Traits\EventServer;

/**
 * @property int $id
 * @property string $tokenable_type
 * @property int $tokenable_id
 * @property string $name
 * @property string $token
 * @property string $user_agent
 * @property string $ip
 * @property array $abilities
 * @property Carbon $expires_at
 */
class PersonalAccessToken extends SanctumPersonalAccessToken
{

    protected $fillable = [
        'name',
        'token',
        'user_agent',
        'ip',
        'abilities',
        'expires_at',
    ];

}
