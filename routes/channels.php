<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return $user->id === (int)$id;
});

Broadcast::channel('users.{id}', function ($user, $id) {
    return $user->id === (int)$id;
});

Broadcast::channel('notifications.{id}', function ($user, $id) {
    return $user->id === (int)$id;
});
