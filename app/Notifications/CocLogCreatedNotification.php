<?php

namespace App\Notifications;

use App\Models\CocLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class CocLogCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $cocLog;

    /**
     * Create a new notification instance.
     */
    public function __construct(CocLog $cocLog)
    {
        $this->cocLog = $cocLog;
    }

    /**
     * Determine which channels the notification should be sent through.
     */
    public function via($notifiable)
    {
        return ['database', 'mail']; // Sends to database and email
    }

    /**
     * Format the notification for database storage.
     */
    public function toDatabase($notifiable)
    {
        return new DatabaseMessage([
            'message' => 'A new COC Log has been created for you.',
            'activity_name' => $this->cocLog->activity_name,
            'activity_date' => $this->cocLog->activity_date,
            'coc_earned' => $this->cocLog->coc_earned,
            'created_by' => $this->cocLog->creator->name,
            'url' => route('coc.logs.show', $this->cocLog->id),
        ]);
    }

    /**
     * Format the notification for email.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New COC Log Created')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new COC Log has been created for you.')
            ->line('**Activity:** ' . $this->cocLog->activity_name)
            ->line('**Date:** ' . $this->cocLog->activity_date)
            ->line('**COC Earned:** ' . $this->cocLog->coc_earned)
            ->line('**Issued By:** ' . $this->cocLog->creator->name)
            ->action('View COC Log', route('coc.logs.show', $this->cocLog->id))
            ->line('Thank you.');
    }
}
