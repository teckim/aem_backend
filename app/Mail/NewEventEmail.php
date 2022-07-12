<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewEventEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $event, $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($event, $user)
    {
        $this->event = $event;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('New event - ' . $this->event->title)
            ->markdown('emails.event', [
                'event' => $this->event,
                'user' => $this->user
            ]);
    }
}
