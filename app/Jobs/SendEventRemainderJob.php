<?php

namespace App\Jobs;

use App\Models\Event;
use App\Mail\EventRemainderMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEventRemainderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function handle(): void
    {
        // Check if reminder was already sent
        if ($this->event->reminder_sent) {
            return;
        }

        // Send the reminder email
        Mail::to($this->event->email)->send(new EventRemainderMail($this->event));

        // Mark reminder as sent
        $this->event->update(['reminder_sent' => true]);
    }
}
