<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\SwipeMatch;
use App\Models\User;
use Livewire\Component;

class ProfileCard extends Component
{
    public User $user;
    public Conversation $conversation;

    public function mount(User $user, Conversation $conversation) {
        $this->user = $user;
        $this->conversation = $conversation;
    }

    function deleteMatch() {
        abort_unless(auth()->check(), 401);

        //Make sure user belong to match
        $belongsToMatch = auth()->user()->matches()->where('swipe_matches.id', $this->conversation->match_id)->exists();
        abort_unless($belongsToMatch, 403);

        // Delete match
        SwipeMatch::where('id', $this->conversation->match_id)->delete();

        //Redirect
        $this->redirect(route("dashboard"), navigate: true);
    }

    public function render() {
        return view('livewire.chat.profile-card');
    }
}
