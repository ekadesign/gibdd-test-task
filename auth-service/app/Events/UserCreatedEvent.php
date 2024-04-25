<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class UserCreatedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ?User $user,
        public array $context = [],
    ) {
    }
}
