<?php

namespace App\Mail;

use App\Attendee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Generator;

class EventRegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public Attendee $attendee;

    public function __construct(Attendee $attendee)
    {
        $this->attendee = $attendee;
    }

    public function envelope(): Envelope
    {
        $eventName = $this->attendee->event->name ?? __('frontend.nav_event');

        return new Envelope(
            subject: __('mail.registration_subject', ['event' => $eventName]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.event-registration',
            with: [
                'attendee'   => $this->attendee,
                'event'      => $this->attendee->event,
                'confirmUrl' => $this->attendee->verification_url,
            ],
        );
    }
}