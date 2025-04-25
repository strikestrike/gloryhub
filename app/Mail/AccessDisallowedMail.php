<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class AccessDisallowedMail extends Mailable
{
    use SerializesModels;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Access Disallowed Notification')
            ->view('emails.accessDisallowed')
            ->with([
                'user' => $this->user,
            ]);
    }
}
