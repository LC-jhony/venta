<?php

namespace App\Mail;

use App\Models\Quote;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteShippedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Quote $quote
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cotización #'.$this->quote->number_quote,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.quote-shipped',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $setting = Setting::first();
        $pdf = Pdf::loadView(
            'pdf.invoice',
            [
                'quote' => $this->quote->load(
                    'quoteProducts.product',
                    'user',
                    'suppliers'
                ),
                'setting' => $setting,
            ]
        );

        return [
            Attachment::fromData(fn () => $pdf->output(), 'cotizacion-'.$this->quote->number_quote.'.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
