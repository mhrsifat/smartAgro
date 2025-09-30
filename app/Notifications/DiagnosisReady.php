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
    public ?int $diagnosisId;
    public ?string $message;

    /**
     * Create a new notification instance.
     *
     * @param string $status
     * @param int|null $diagnosisId  
     * @param string|null $message
     */
    public function __construct(string $status, ?int $diagnosisId = null, ?string $message = null)
    {
        $this->status = $status;
        $this->diagnosisId = $diagnosisId;
        $this->message = $message ?? ($status === 'completed' ? 'Diagnosis ready' : ucfirst($status));
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification for database.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $url = $this->diagnosisId ? route('diagnosis.result.show', ['id' => $this->diagnosisId]) : null;
        $messagewithUrl = $this->message ? $this->message . ($url ? " <a href=\"{$url}\">View Result</a>" : '') : ($this->status === 'completed' ? 'Diagnosis ready' . ($url ? " <a href=\"{$url}\">View Result</a>" : '') : ucfirst($this->status));
        return [
            'status' => $this->status,
            'message' => $messagewithUrl,
            'diagnosis_id' => $this->diagnosisId,
            'url' => $url,
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param mixed $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        $url = $this->diagnosisId ? route('diagnosis.result.show', ['id' => $this->diagnosisId]) : null;
        $messagewithUrl = $this->message ? $this->message . ($url ? " <a href=\"{$url}\">View Result</a>" : '') : ($this->status === 'completed' ? 'Diagnosis ready' . ($url ? " <a href=\"{$url}\">View Result</a>" : '') : ucfirst($this->status));
        return [
            'status' => $this->status,
            'message' => $messagewithUrl,
            'diagnosis_id' => $this->diagnosisId,
            'url' => $url,
        ];
    }
}
