<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat', function ($user) {
    // return true if this user can listen
    return $user != null; // or more strict check
});

Broadcast::channel('diagnosis.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
