<?php

namespace App\Mail;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class QuotationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $quotation;

    /**
     * Create a new message instance.
     */
    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation->load('items');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $isUpdate = $this->quotation->created_at->ne($this->quotation->updated_at);
        $subject = $isUpdate 
            ? 'Updated Quotation - ' . $this->quotation->quotation_number
            : 'New Quotation - ' . $this->quotation->quotation_number;
            
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.quotation',
            with: [
                'quotation' => $this->quotation,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        try {
            // Generate PDF
            $pdf = Pdf::loadView('pdf.quotation', ['quotation' => $this->quotation]);
            
            return [
                Attachment::fromData(fn () => $pdf->output(), 'quotation-' . $this->quotation->quotation_number . '.pdf')
                    ->withMime('application/pdf'),
            ];
        } catch (\Exception $e) {
            Log::error('Failed to generate PDF attachment: ' . $e->getMessage());
            return []; // Return empty array if PDF generation fails
        }
    }
}
