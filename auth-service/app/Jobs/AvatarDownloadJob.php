<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\FileService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class AvatarDownloadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected User $user,
        protected $url
    ) {
        $this->onQueue('avatar');
    }

    /**
     * Execute the job.
     */
    public function handle(FileService $fileService): void
    {
        $response = Http::get($this->url);
        $file = $fileService->uploadResponse($response, 'avatars', 'public');
        $this->user->avatar_id = $file->id;
        $this->user->save();
    }
}
