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
    public ?string $diagnosis;
    public ?string $userKey;

    public function __construct(string $status, ?string $diagnosis = null, ?string $userKey = null)
    {
        $this->status = $status;
        $this->diagnosis = $diagnosis;
        $this->userKey = $userKey;
    }

    public function broadcastOn(): Channel
{
    return new PrivateChannel('diagnosis.' . ($this->userKey ?? 'guest'));
}
}