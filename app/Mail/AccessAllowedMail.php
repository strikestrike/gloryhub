<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class AccessAllowedMail extends Mailable
{
    use SerializesModels;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Access Allowed Notification')
            ->view('emails.accessAllowed')
            ->with([
                'user' => $this->user,
            ]);
    }
}
