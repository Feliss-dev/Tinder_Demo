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
    public $searchTerm;
    public $ageFrom;
    public $ageTo;
    public $gender;
    public $users;
    public $filtersApplied = false;
    public $user;
    public $selectedLanguages = [];
    public $selectedInterests =[];
    public $selectedDatingGoals = [];

    public $profiles = false; // Property to store profile visibility status

    #[On('swipedright')]
    public function swipedRight(User $user)
    {
        //make user user is authenticated
        abort_unless(auth()->check(), 401);

        $this->createSwipe($user, 'right');
    }

    #[On('swipedleft')]
    public function swipedLeft(User $user)
    {
        //make user user is authenticated
        abort_unless(auth()->check(), 401);

        $this->createSwipe($user, 'left');
    }

    #[On('swipedup')]
    public function swipedUp(User $user)
    {
        //make user user is authenticated
        abort_unless(auth()->check(), 401);

        $this->createSwipe($user, 'up');
    }

    protected function createSwipe($swipedUser, $type)
    {
        #return null if auth user has already swiped with this person
        if (auth()->user()->hasSwiped($swipedUser)) {
            return null;
        }

        #create Swipe
        $swipe =  Swipe::create([
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

    public function applyFilters()
    {
        $this->filtersApplied = true; // Đánh dấu rằng bộ lọc đã được áp dụng
        $query = User::query()
            ->whereNotSwiped() // Điều kiện tùy chỉnh
            ->where('id', '<>', auth()->id()); // Loại bỏ người dùng hiện tại

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

        // Lấy danh sách người dùng theo các tiêu chí lọc
        $this->users = $query->limit(10)->get();
    }

    public function render()
    {
        // Return all available users if the filters aren't applied.
        if (!$this->filtersApplied) {
            $this->users = User::limit(10)
                ->whereNotSwiped()
                ->where('id', '<>', auth()->id())
                ->get();
        }

        return view('livewire.swiper.swiper', ['users' => $this->users,
            'currentUser' => Auth::user(),
            'genders' => Gender::all(),
            'interests' => Interest::all(),
            'languages' => Language::all(),
            'datingGoals' => DatingGoal::all(),
            'user' => $this->user,
            'profiles' => $this->profiles,
        ]);
    }
}
