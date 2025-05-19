<?php

namespace App\Livewire\Swiper;

use App\Models\Conversation;
use App\Models\SwipeMatch;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class MatchModal extends Component
{
    public User $matchedUser;
    public int $matchId;

    #[On("match-found")]
    public function matchFound(User $matchedUser, int $matchId) {
        $this->matchedUser = $matchedUser;
        $this->matchId = $matchId;

        Log::debug("Match Found: " . $this->matchId);
    }

    public function createConversation() {
        Log::debug("createConversation");

        $conversation = Conversation::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $this->matchedUser->id,
            'match_id' => $this->matchId,
        ]);

        //redirect to conversation
        $this->redirect(route('chat', $conversation->id), navigate: true);
    }

    public function render()
    {
        return view('livewire.swiper.match-modal');
    }
}
