<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccessRequestNotification extends Mailable
{
    use SerializesModels;

    public $accessRequestData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($accessRequestData)
    {
        $this->accessRequestData = $accessRequestData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Access Request Submitted')
            ->view('emails.access_request_notification')
            ->with(['data' => $this->accessRequestData]);
    }
}
