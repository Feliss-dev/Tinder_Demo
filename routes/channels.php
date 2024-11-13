<?php

use App\Models\User;
use App\Models\Conversation;
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

Broadcast::channel('conversation.{id}', function($user, $conversationID) {
    $conversation = Conversation::where('id', (int)$conversationID)->first();

    return $conversation->sender_id === $user->id || $conversation->receiver_id === $user->id;
});
