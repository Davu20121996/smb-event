<?php

namespace App\Mail;

use App\Attendee;
use App\Voucher;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VoucherAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Voucher $voucher;
    public Attendee $attendee;
    public ?string $note;

    public function __construct(Voucher $voucher, Attendee $attendee, ?string $note = null)
    {
        $this->voucher = $voucher;
        $this->attendee = $attendee;
        $this->note = $note;
    }

    public function envelope(): Envelope
    {
        $eventName = $this->voucher->event?->name ?? $this->attendee->event?->name ?? __('frontend.nav_event');

        return new Envelope(
            subject: '🎁 Bạn nhận được voucher ưu đãi từ ' . $eventName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.voucher_assigned',
            with: [
                'voucher'  => $this->voucher,
                'attendee' => $this->attendee,
                'note'     => $this->note,
                'event'    => $this->voucher->event ?? $this->attendee->event,
            ],
        );
    }
}