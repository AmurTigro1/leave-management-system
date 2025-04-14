<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use App\Models\Leave;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeaveApprovalMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $leave;
    public $status;

    /**
     * Create a new message instance.
     */
    public function __construct(Leave $leave, $status)
    {
        $this->leave = $leave;
        $this->status = $status;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Leave Approval Mail',
        );
    }

    public function build()
    {
        $subject = $this->status === 'approved'
        ? 'Your CTO Request Has Been Approved'
        : 'Your CTO Request Has Been Rejected';

        return $this->subject('Leave Request Approval Notification')
                    ->view('emails.leave_approval')
                    ->with([
                        'leave' => $this->leave,
                        'status' => $this->status,
                    ]);
    }
    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.leave_approval',
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
