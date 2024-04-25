<?php

namespace App\Services\Verify\Senders;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Laravel\Octane\Exceptions\DdException;

class EmailSenderVerifyService implements SenderVerifyEmailInterface
{

    /**
     * @param  string  $to
     * @param  string  $emailVerifiedHash
     * @return bool
     * @throws DdException
     */
    public function handle(string $to, string $emailVerifiedHash): bool
    {
        Mail::send('emails.verification', ['hash' => $emailVerifiedHash], function (Message $m) use ($to) {
            $m->from('info@example.com', 'Info');
            $m->to($to)->subject('Confirm Email Verification Site');
        });

        return true;
    }
}
