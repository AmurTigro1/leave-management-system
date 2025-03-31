<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class LeaveStatusNotification extends Notification
{
    use Queueable;

    public $request;
    public $message;
    public $type; // 'leave' or 'overtime'

    /**
     * Create a new notification instance.
     */
    public function __construct($request, $message, $type = 'leave')
    {
        $this->request = $request;
        $this->message = $message;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast']; 
    }

    /**
     * Store notification in the database.
     */
    public function toDatabase($notifiable)
    {
        if ($this->type === 'leave') {
            return [
                'type' => 'leave',
                'leave_id' => $this->request->id,
                'message' => $this->message,
                'user_id' => $this->request->user_id,
                'start_date' => $this->request->start_date,
                'end_date' => $this->request->end_date,
                'status' => $this->request->status,
            ];
        } else {
            return [
                'type' => 'overtime',
                'overtime_id' => $this->request->id,
                'message' => $this->message,
                'user_id' => $this->request->user_id,
                'date_filed' => $this->request->date_filed,
                'working_hours_applied' => $this->request->working_hours_applied,
                'inclusive_dates' => $this->request->inclusive_dates,
                'status' => $this->request->status,
            ];
        }
    }

    /**
     * Broadcast notification event.
     */
    public function toBroadcast($notifiable)
    {
        if ($this->type === 'leave') {
            return new BroadcastMessage([
                'type' => 'leave',
                'leave_id' => $this->request->id,
                'message' => $this->message,
                'user_id' => $this->request->user_id,
                'start_date' => $this->request->start_date,
                'end_date' => $this->request->end_date,
                'status' => $this->request->status,
            ]);
        } else {
            return new BroadcastMessage([
                'type' => 'overtime',
                'overtime_id' => $this->request->id,
                'message' => $this->message,
                'user_id' => $this->request->user_id,
                'date_filed' => $this->request->date_filed,
                'working_hours_applied' => $this->request->working_hours_applied,
                'inclusive_dates' => $this->request->inclusive_dates,
                'status' => $this->request->status,
            ]);
        }
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line("Notification regarding your {$this->type} request.")
                    ->action('View Details', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->type,
            'request_id' => $this->request->id,
            'message' => $this->message,
        ];
    }
}
