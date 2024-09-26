<?php

namespace App\Livewire\Swiper;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Swipe;
use Livewire\Component;
use App\Models\SwipeMatch;
use Livewire\Attributes\On;
use App\Models\Conversation;
use Livewire\Attributes\Locked;

class Swiper extends Component
{

    public $ageFrom;
    public $ageTo;
    public $gender;

    #[Locked]
    public $currentMatchId;

    #[Locked]
    public $swipedUserId;

    #[On('swipedright')]
    public function swipedRight(User $user)
    {
        //make user user is authenticated
        abort_unless(auth()->check(),401);

         #create Swipe Right
         $this->createSwipe($user,'right');

    }

    #[On('swipedleft')]
    public function swipedLeft(User $user)
    {
        //make user user is authenticated
        abort_unless(auth()->check(),401);

         #create Swipe Right
         $this->createSwipe($user,'left');
    }

    #[On('swipedup')]
    public function swipedUp(User $user)
    {
        //make user user is authenticated
        abort_unless(auth()->check(),401);

         #create Swipe Right
         $this->createSwipe($user,'up');
    }


    protected function createSwipe($user,$type){

        //reset properties
        $this->reset('swipedUserId', 'currentMatchId');

        #return null if auth user has already swiped with  $user
        if (auth()->user()->hasSwiped($user)) {
            return null;
        }

        #create Swipe
        $swipe =  Swipe::create([
            'user_id'=>auth()->id(),
            'swiped_user_id'=>$user->id,
            'type'=>$type,
        ]);

        #before creating match we want to make sure auth user swiped Right or  Up
        if ($type=='up'||$type=='right') {
            # code...

        #creating Match
        $authUserId = auth()->id();
        $this->swipedUserId = $user->id;

        #Now Also check if swiped user  has swipe match with authenticated user.
        $matchingSwipe =  Swipe::where('user_id', $this->swipedUserId)
                            ->where('swiped_user_id', $authUserId)
                            ->whereIn('type',['up','right'])
                            ->first();


        #If true, create a SwipeMatch
        if ($matchingSwipe) {
            $match = SwipeMatch::create([
                'swipe_id_1' => $swipe->id,
                'swipe_id_2' => $matchingSwipe->id,
            ]);


        //Show match found alert
        $this->dispatch('match-found');

        $this->currentMatchId=$match->id;
        }

    }

    }

    public function applyFilters(){
        $query = User::query();

        // Apply age filtering
        if ($this->ageFrom) {
            $query->whereDate('birth_date', '<=', Carbon::now()->subYears($this->ageFrom)->toDateString());
        }

        if ($this->ageTo) {
            $query->whereDate('birth_date', '>=', Carbon::now()->subYears($this->ageTo)->toDateString());
        }
        if($this->gender){
            $query->where('gender', $this->gender);
        }

         // Ensure not to show users who have already been swiped
         $query->whereNotSwiped()->where('id', '<>', auth()->id());

         // Get the filtered users
         $users = $query->limit(10)->get();

         return view('livewire.swiper.swiper', ['users' => $users]);
    }

    public function createConversation(){
        $conversation=Conversation::create([
            'sender_id'=>auth()->id(),
            'receiver_id'=>$this->swipedUserId,
            'match_id'=>$this->currentMatchId,
        ]);

        // dispatch an event
        $this->dispatch('close-match-modal');

        //reset properties
        $this->reset('swipedUserId', 'currentMatchId');

        //redirect to conversation
          $this->redirect(route('chat', $conversation->id), navigate:true);
        }

    public function render()
    {
        $query = User::query();

         // Apply age filtering
         if ($this->ageFrom) {
            $query->whereDate('birth_date', '<=', Carbon::now()->subYears($this->ageFrom)->toDateString());
        }

        if ($this->ageTo) {
            $query->whereDate('birth_date', '>=', Carbon::now()->subYears($this->ageTo)->toDateString());
        }

        if ($this->gender) {
            $query->where('gender', $this->gender);
        }
       // dd(auth()->user()->matches()->get());

       // dd(SwipeMatch::first()->swipe2);
        $users=User::limit(10)->whereNotSwiped()->where('id','<>',auth()->id())->get();
        return view('livewire.swiper.swiper',['users'=>$users]);
        //<button wire:click="refreshUsers">Refresh Users</button>
    }
}
