<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class DiagnosisReady extends Notification implements ShouldQueue
{
    use Queueable;

    public string $status;
    public string $html;
    public string $filePath;

    public function __construct(string $status, string $html = '', string $filePath = '')
    {
        $this->status = $status;
        $this->html = $html;
        $this->filePath = $filePath;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }
    
    /** public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    } */

    public function toArray($notifiable)
    {
        return [
            'status' => $this->status,
            'message' => $this->status === 'completed' ? 'Diagnosis ready' : $this->status,
            'file' => $this->filePath,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'status' => $this->status,
            'html' => $this->html,
            'file' => $this->filePath,
        ]);
    }
}

