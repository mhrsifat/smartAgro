<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class DiagnosisListener extends Component
{
    public $status = '';
    public $html = '';
    public $file = null;
    public $userId;

    public function mount()
    {
        $this->userId = Auth::id() ?? 'guest';
    }

    /**
     * Return dynamic listeners so we can bind to private Echo channels for this user.
     * (Livewire uses "echo-private:channel,Event" format.)
     */
    public function getListeners()
    {
        return [
            // listens to your DiagnosisUpdated event on private channel "diagnosis.{userId}"
            'echo-private:diagnosis.' . $this->userId . ',DiagnosisUpdated' => 'onDiagnosisUpdated',

            // listens to default notification broadcast channel for the User model,
            // which fires BroadcastNotificationCreated when a notification is sent
            'echo-private:App.Models.User.' . $this->userId . ',Illuminate\Notifications.Events.BroadcastNotificationCreated' => 'onNotificationReceived',
        ];
    }

    public function onDiagnosisUpdated($payload)
    {
        $this->status = $payload['status'] ?? ($payload['diagnosis'] ? 'completed' : 'unknown');
        $this->html = $payload['diagnosis'] ?? $payload['html'] ?? '';
        $this->file = $payload['file'] ?? null;

        // dispatch a browser event so non-Livewire JS (your jQuery UI) can update too
        $this->dispatchBrowserEvent('diagnosis-updated', [
            'status' => $this->status,
            'html' => $this->html,
            'file' => $this->file,
        ]);
    }

    public function onNotificationReceived($payload)
    {
        $notification = $payload['notification'] ?? $payload;
        // forward to browser for a toast and let other components refresh if needed
        $this->dispatchBrowserEvent('notification-received', ['notification' => $notification]);
        // optional: let any Livewire instance refresh notification lists
        $this->emitSelf('reloadNotifications');
    }

    public function render()
    {
        return view('livewire.diagnosis-listener');
    }
}