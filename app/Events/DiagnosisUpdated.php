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

    public function __construct(string $status, ?string $userKey = null, ?int $diagnosisId = null)
    {
        $this->status = $status;
        $this->userKey = $userKey;
        $this->diagnosisId = $diagnosisId;
    }
    
     public function broadcastOn()
    {
        return new PrivateChannel('diagnosis.' . ($this->userKey ?? 'guest'));
    }

    public function broadcastWith()
    {
        return [
            'status' => $this->status,
            'diagnosis_id' => $this->diagnosisId,
            'message' => $this->status === 'completed' ? 'Diagnosis ready' : $this->status,
        ];
    }
}
