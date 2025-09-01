<?php

namespace App\Mail;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventRemainderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Event Reminder: ' . $this->event->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'events.event-reminder',
            with: [
                'event' => $this->event,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
