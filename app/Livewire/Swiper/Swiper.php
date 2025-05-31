<?php

namespace App\Livewire\Swiper;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Swipe;
use App\Models\Gender;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Models\Interest;
use App\Models\Language;
use App\Models\DatingGoal;
use App\Models\SwipeMatch;
use Livewire\Attributes\On;
use App\Models\Conversation;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Auth;

class Swiper extends Component {
    const int DISPLAY_AMOUNT = 3;

    public $searchTerm;
    public $ageFrom;
    public $ageTo;
    public $gender;
    public $users;
    public $selectedLanguages = [];
    public $selectedInterests = [];
    public $selectedDatingGoals = [];

    public function swipedRight(User $user)
    {
        Log::debug("swiped right: " . $user->id);

        //make user user is authenticated
        abort_unless(auth()->check(), 401);

        $this->createSwipe($user, 'right');
    }

    public function swipedLeft(User $user)
    {
        Log::debug("swiped left: " . $user->id);

        //make user user is authenticated
        abort_unless(auth()->check(), 401);

        $this->createSwipe($user, 'left');
    }

    public function swipedUp(User $user)
    {
        Log::debug("swiped up: " . $user->id);
        //make user user is authenticated
        abort_unless(auth()->check(), 401);

        $this->createSwipe($user, 'up');
    }

    protected function createSwipe($swipedUser, $type)
    {
        #return null if auth user has already swiped with this person
        if (auth()->user()->hasSwiped($swipedUser)) return;

        #create Swipe
        $swipe = Swipe::create([
            'user_id' => auth()->id(),
            'swiped_user_id' => $swipedUser->id,
            'type' => $type,
        ]);

        #before creating match we want to make sure auth user swiped Right or Up
        if ($type == 'up' || $type == 'right') {
            #creating Match
            $authUserId = auth()->id();
            $swipedUserId = $swipedUser->id;

            #Now Also check if swiped user has swipe match with authenticated user.
            $matchingSwipe =  Swipe::where('user_id', $swipedUserId)
                ->where('swiped_user_id', $authUserId)
                ->whereIn('type', ['up', 'right'])
                ->first();

            #If true, create a SwipeMatch
            if ($matchingSwipe) {
                $match = SwipeMatch::create([
                    'swipe_id_1' => $swipe->id,
                    'swipe_id_2' => $matchingSwipe->id,
                    'user_id_1' => min($authUserId, $swipedUserId), // Ensure consistent ordering
                    'user_id_2' => max($authUserId, $swipedUserId),
                ]);

                $this->dispatch('match-found', matchedUser: $swipedUser, matchId: $match->id);
            }
        }
    }

    public function applyFilters() {
        // $this->users = $this->getUserQueryBuilder()->limit(10)->get();
        // Log::debug("applyFilters");
    }

    public function getUserQueryBuilder() {
        $query = User::withoutTrashed()->withoutBanned()->whereNot('id', auth()->id())->whereNotIn('id', function ($subquery) {
            $subquery->select('swiped_user_id')->from('swipes')->where('user_id', auth()->user()->id);
        });

        // Lọc theo độ tuổi
        if ($this->ageFrom) {
            $query->where('birth_date', '<=', now()->subYears($this->ageFrom));
        }
        if ($this->ageTo) {
            $query->where('birth_date', '>=', now()->subYears($this->ageTo));
        }

        // Lọc theo giới tính
        if (!empty($this->gender)) {
            $query->whereHas('genders', function ($q) {
                $q->where('gender_id', $this->gender);
            });
        }

        // Lọc theo sở thích
        if (!empty($this->selectedInterests)) {
            $query->whereHas('interests', function ($q) {
                $q->whereIn('interest_id', $this->selectedInterests);
            });
        }

        // Lọc theo ngôn ngữ
        if (!empty($this->selectedLanguages)) {
            $query->whereHas('languages', function ($q) {
                $q->whereIn('language_id', $this->selectedLanguages);
            });
        }

        // Lọc theo mục tiêu hẹn hò
        if (!empty($this->selectedDatingGoals)) {
            $query->whereHas('datingGoals', function ($q) {
                $q->whereIn('dating_goal_id', $this->selectedDatingGoals);
            });
        }

        // Lọc theo tên người dùng
        if ($this->searchTerm) {
            $query->where('name', 'like', '%' . $this->searchTerm . '%');
        }

        return $query;
    }

    public function render()
    {
        $this->users = $this->getUserQueryBuilder()->limit(static::DISPLAY_AMOUNT)->get()->reverse();

        return view('livewire.swiper.swiper', [
            'genders' => Gender::all(),
            'interests' => Interest::all(),
            'languages' => Language::all(),
            'datingGoals' => DatingGoal::all(),
        ]);
    }
}
