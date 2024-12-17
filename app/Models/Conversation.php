<?php

namespace App\Models;

use App\Models\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model {
    use HasFactory;

    protected $guarded =[];

    function messages() : HasMany {
        return $this->hasMany(Message::class);
    }

    function match() : BelongsTo {
        return $this->belongsTo(SwipeMatch::class);
    }

    function sender(): HasOne {
        return $this->hasOne(User::class, 'id', 'sender_id');
    }

    function receiver(): HasOne {
        return $this->hasOne(User::class, 'id', 'receiver_id');
    }

    public function getReceiver() {
        if ($this->sender_id==auth()->id()) {
            return User::firstWhere('id', $this->receiver_id);
        } else {
            return User::firstWhere('id', $this->sender_id);
        }
    }
}
