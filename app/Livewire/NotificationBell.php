<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationBell extends Component
{
    public $open = false;
    public $mobile = false;

    protected $listeners = ['closeAll' => 'close'];

    public function toggle()
    {
        $this->open = !$this->open;
    }

    public function close()
    {
        $this->open = false;
    }

    public function render()
    {
        $user = Auth::user();

        // Get latest unread notifications
        $notifications = $user
            ? $user->unreadNotifications()->take(10)->get()
            : collect(); // empty if guest

        return view('livewire.notification-bell', [
            'notifications' => $notifications,
        ]);
    }
}