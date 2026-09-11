<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewPaymentProofMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Payment $payment) {}

    public function envelope(): Envelope
    {
        $client = $this->payment->project?->client?->name ?? 'Klien';

        return new Envelope(
            subject: "Bukti pembayaran baru dari {$client} - ".rupiah($this->payment->amount),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.bukti-pembayaran-baru',
            with: ['payment' => $this->payment],
        );
    }
}
