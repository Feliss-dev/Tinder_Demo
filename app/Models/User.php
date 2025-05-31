<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Cog\Contracts\Ban\Bannable as BannableInterface;
use Cog\Laravel\Ban\Traits\Bannable;
use DateTime;

use App\Models\Swipe;
use App\Models\UserImage;
use App\Models\SwipeMatch;
use App\Enums\BasicGroupEnum;
use App\Models\UserPreference;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;
use Laravel\Sanctum\HasApiTokens;
use App\Enums\RelationshipGoalsEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail, BannableInterface
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Bannable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];



    public function isFake()
    {
        return $this->is_fake;
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'language_users', 'user_id', 'language_id');
    }

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'interest_users', 'user_id', 'interest_id');
    }

    public function datingGoals()
    {
        return $this->belongsToMany(DatingGoal::class, 'dating_goal_users', 'user_id', 'dating_goal_id');
    }
    public function desiredGenders()
    {
        return $this->belongsToMany(Gender::class, 'desired_gender_users', 'user_id', 'desired_gender_id');
    }
    public function genders()
    {
        return $this->belongsToMany(Gender::class, 'gender_users', 'user_id', 'gender_id');
    }

    public function avatars()
    {
        return $this->hasMany(Avatar::class);
    }

    public function activeAvatar()
    {
        return $this->hasOne(Avatar::class)->where('is_active', true);
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_seen_at' => 'datetime',
    ];

    public function getAgeAttribute()
    {
        $birthDate = new DateTime($this->birth_date);
        $today = new DateTime();
        return $today->diff($birthDate)->y;
    }

    protected static function booted() {}

    //Swipe models relationshop
    /* user has many swipes */
    public function swipes()
    {
        return $this->hasMany(Swipe::class, 'user_id');
    }

    // Allow to check if user has swiped with another user
    /* Allows you to check if a user has swiped with another user */
    public function hasSwiped(User $user, $type = null): bool
    {
        $query = $this->swipes()->where('swiped_user_id', $user->id);

        if ($type !== null) {
            $query->where('type', $type);
        }
        return $query->exists();
    }

    /** Scope to exclude users who have already been swiped by the authenticated user. */
    public function scopeWhereNotSwiped($query)
    {
        // Exclude users whose IDs are in the result of the subquery
        return $query->whereNotIn('id', function ($subquery) {
            // Select the swiped_user_id from the swipes table where user_id is the authenticated user's ID
            $subquery->select('swiped_user_id')
                ->from('swipes')
                ->where('user_id', auth()->id());
        });
    }

    public function matches()
    {
        return $this->hasManyThrough(
            SwipeMatch::class,
            Swipe::class,
            'user_id',
            'swipe_id_1',
            'id',
            'id'
        )->orWhereHas('swipe2', function ($query) {
            $query->where('user_id', $this->id);
        });
    }

    public function hasMatchWith(User $user): bool
    {
        return $this->matches()
            ->where(function ($query) use ($user) {
                $query->where('swipe_id_1', $user->id)->orWhere('swipe_id_2', $user->id);
            })
            ->exists();
    }

    //User can have many conversations
    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'sender_id')->orWhere('receiver_id', $this->id);
    }

    // Unread Messages count
    function unReadMessagesCount(): int
    {
        return $this->hasMany(Message::class, 'receiver_id')->where('read_at', null)->count();
    }

    /**
     * The channels the user receives notification broadcasts on.
     */
    public function receivesBroadcastNotificationsOn(): string
    {
        return 'users.' . $this->id;
    }
}
