<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $phone
 * @property string $code
 * @property Carbon|null $verified_at
 * @property Carbon|null $expired_at
 */
class UserPhoneVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'code',
        'verified_at',
        'expired_at',
    ];

    protected $casts = [
        'is_verified' => 'bool',
        'verified_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function isVerified(): bool
    {
        return ! is_null($this->verified_at);
    }

    public function isExpired(): bool
    {
        return $this->expired_at->lessThanOrEqualTo(now());
    }
}
