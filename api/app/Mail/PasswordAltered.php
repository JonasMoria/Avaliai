<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordAltered extends Mailable {
    use Queueable, SerializesModels;


    public string $userName = '';
    public function __construct(string $userName) {
        $this->userName = $userName;
    }

    public function envelope(): Envelope {
        return new Envelope(
            subject: 'Senha de acesso alterada',
        );
    }

    public function content(): Content {
        return new Content(
            markdown: 'emails.password.altered',
        );
    }

    public function attachments(): array {
        return [];
    }
}
