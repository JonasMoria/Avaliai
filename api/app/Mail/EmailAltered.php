<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailAltered extends Mailable {
    use Queueable, SerializesModels;

    public string $userName;
    public string $userNewEmail;

    public function __construct(string $name, string $email) {
        $this->userName = $name;
        $this->userNewEmail = $email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        return new Envelope(
            subject: 'Email de login alterado',
        );
    }

    public function content(): Content {
        return new Content(
            markdown: 'emails.email.altered',
        );
    }

    public function attachments(): array {
        return [];
    }
}
