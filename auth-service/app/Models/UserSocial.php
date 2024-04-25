<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LaravelTool\EloquentExternalEventsServer\Traits\EventServer;

/**
 * @property int $id
 * @property int $user_id
 * @property string $provider_name
 * @property string $provider_id
 * @property string $nickname
 *
 * @property User $user
 */
class UserSocial extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider_name',
        'provider_id',
        'nickname',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
