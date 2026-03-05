<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeadReceivedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Lead $lead)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New ' . ucfirst($this->lead->type) . ' Lead - Lunar Ambalaj',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lead-received',
            with: ['lead' => $this->lead],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
