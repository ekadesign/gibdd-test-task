<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\Verify\SendPhoneVerifyService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPhoneVerificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly string $phone,
    ){}

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(SendPhoneVerifyService $service): void
    {
        $service->handle($this->phone);
    }
}
