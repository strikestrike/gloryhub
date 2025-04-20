<?php

namespace App\Mail;

use App\Models\AccessRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class AccessApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $accessRequest;
    public $registrationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(AccessRequest $accessRequest)
    {
        $this->accessRequest = $accessRequest;

        $this->registrationUrl = URL::temporarySignedRoute(
            'register.from_token',
            now()->addHours(24),
            ['token' => $accessRequest->token]
        );
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Access Has Been Approved!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.access_approved',
            with: [
                'playerName' => $this->accessRequest->player_name,
                'kingdom' => $this->accessRequest->kingdom,
                'registrationUrl' => $this->registrationUrl,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
