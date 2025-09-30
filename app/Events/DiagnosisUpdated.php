<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;

class DiagnosisUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $status;
    public ?string $userKey;
    public ?int $diagnosisId;
    public ?string $message;

    /**
     * Create a new event instance.
     *
     * @param string $status The status of the diagnosis (processing, completed, failed)
     * @param string|null $userKey The user key for channel broadcasting
     * @param int|null $diagnosisId The diagnosis ID
     * @param string|null $message Custom message to display
     */
    public function __construct(string $status, ?string $userKey = null, ?int $diagnosisId = null, ?string $message = null)
    {
        $this->status = $status;
        $this->userKey = $userKey;
        $this->diagnosisId = $diagnosisId;
        $this->message = $message ?? ($status === 'completed' ? 'Diagnosis ready' : ucfirst($status));
    }
    
    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        // Extract user ID from userKey if it's in format 'user_123'
        $userId = $this->extractUserIdFromKey($this->userKey);
        
        return new PrivateChannel('diagnosis.' . $userId);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'DiagnosisUpdated';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $url = $this->diagnosisId ? route('diagnosis.result.show', ['id' => $this->diagnosisId]) : null;
        $messagewithUrl = $this->message ? $this->message . ($url ? " <a href=\"{$url}\">View Result</a>" : '') : $this->getDefaultMessage();
        return [
            'status' => $this->status,
            'diagnosis_id' => $this->diagnosisId,
            'message' => $messagewithUrl,
            'user_key' => $this->userKey,
            'url' => $url,
        ];
    }

    private function extractUserIdFromKey(?string $userKey): string
{
    if (!$userKey) {
        return 'guest';
    }

    // If the key is already numeric, use it directly
    if (is_numeric($userKey)) {
        return (string) $userKey;
    }

    // common pattern: user_123...
    if (preg_match('/user_(\d+)/', $userKey, $m)) {
        return $m[1];
    }

    // fallback: pull the first number anywhere in the string
    if (preg_match('/(\d+)/', $userKey, $m2)) {
        return $m2[1];
    }

    // last resort: return original key (keeps behavior predictable)
    return $userKey;
}

    /**
     * Get default message based on status
     *
     * @return string
     */
    private function getDefaultMessage(): string
    {
        return match($this->status) {
            'processing' => 'Analyzing your crop images...',
            'completed' => 'Diagnosis complete! Click to view results.',
            'failed' => 'Diagnosis failed. Please try again.',
            default => ucfirst($this->status)
        };
    }
}