<?php

namespace App\Mail;

use App\Models\OvertimeRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CTOApprovalMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $cto;
    public $status;
    /**
     * Create a new message instance.
     */
    public function __construct(OvertimeRequest $cto, $status)
    {
        $this->cto = $cto;
        $this->status = $status;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'C T O Approval Mail',
        );
    }
    public function build()
    {
        $subject = $this->status === 'approved'
        ? 'Your CTO Request Has Been Approved'
        : 'Your CTO Request Has Been Rejected';

        return $this->subject('CTO Request Approval Notification')
                    ->view('emails.cto_approval')
                    ->with([
                        'cto' => $this->cto,
                        'status' => $this->status,
                    ]);
    }
    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.cto_approval',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
