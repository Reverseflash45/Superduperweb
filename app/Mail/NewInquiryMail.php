<?php

namespace App\Mail;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Inquiry $inquiry) {}

    public function envelope(): Envelope
    {
        $from = $this->inquiry->business_name ?: $this->inquiry->name;

        return new Envelope(
            subject: "Brief baru dari {$from} - {$this->inquiry->service_type}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.inquiry-baru',
            with: ['inquiry' => $this->inquiry],
        );
    }
}
