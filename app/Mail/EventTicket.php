<?php

namespace App\Mail;

use App\Attendee;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventTicket extends Mailable
{
    use Queueable, SerializesModels;

    public Attendee $attendee;

    public function __construct(Attendee $attendee)
    {
        $this->attendee = $attendee;

        if (empty($attendee->qr)) {
            $attendee->regenerateQr();
        }
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('mail.ticket_subject', ['event' => $this->attendee->event->name ?? __('frontend.nav_event')]),
        );
    }

    public function attachments(): array
    {
        return [];
    }

    protected function buildIcs(): string
    {
        $event = $this->attendee->event;

        $start = $event?->start_date ? Carbon::parse($event->start_date) : null;
        $end = $event?->end_date ? Carbon::parse($event->end_date) : null;

        if (!$start) {
            return '';
        }

        $summary = $event->name ?? __('frontend.nav_event');
        $description = trim(strip_tags($event->description ?? ''));
        if (empty($description)) {
            $description = $summary;
        }

        $dtstart = $start->format('Ymd');
        $dtend = ($end ? $end->copy() : $start->copy())->addDay()->format('Ymd');
        $location = $event->venue ?? '';

        $email = config('mail.from.address', 'no-reply@example.com');
        $uid = 'event-' . $event->id . '-' . $this->attendee->id . '@' . parse_url(config('app.url'), PHP_URL_HOST);

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//SMB+//Event//EN',
            'CALSCALE:GREGORIAN',
            'METHOD:REQUEST',
            'BEGIN:VEVENT',
            'UID:' . $uid,
            'DTSTAMP:' . now()->utc()->format('Ymd\THis\Z'),
            'DTSTART;VALUE=DATE:' . $dtstart,
            'DTEND;VALUE=DATE:' . $dtend,
            'SUMMARY:' . $this->escapeIcs($summary),
            'LOCATION:' . $this->escapeIcs($location),
            'DESCRIPTION:' . $this->escapeIcs($description),
            'SEQUENCE:0',
            'STATUS:CONFIRMED',
            'TRANSP:TRANSPARENT',
            'ORGANIZER;CN=' . $this->escapeIcs('SMB+' . Carbon::now()->year) . ':mailto:' . $email,
            'ATTENDEE;CN=' . $this->escapeIcs($this->attendee->name) . ';RSVP=TRUE:mailto:' . $this->attendee->email,
            'END:VEVENT',
            'END:VCALENDAR',
        ];

        return $this->foldLines(implode("\r\n", $lines) . "\r\n");
    }

    protected function escapeIcs(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        return str_replace(
            ['\\', ';', ',', "\r\n", "\n", "\r"],
            ['\\\\', '\\;', '\\,', '\\n', '\\n', '\\n'],
            $value
        );
    }

    protected function foldLines(string $ics): string
    {
        return preg_replace('/(.{74})/', "$1\r\n ", trim($ics)) . "\r\n";
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.event-ticket',
            with: [
                'attendee' => $this->attendee,
                'event'    => $this->attendee->event,
                'qrUrl'    => rtrim(config('app.url'), '/') . route('event.ticket-qr', $this->attendee->id, false),
            ],
        );
    }
}