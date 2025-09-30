<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register the /broadcasting/auth endpoint
        Broadcast::routes();

        // Load channel authorization callbacks in routes/channels.php
        require base_path('routes/channels.php');
    }
}

