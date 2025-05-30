<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $date = ['read_at'];

    public function conversation() : BelongsTo {
        return $this->belongsTo(Conversation::class);
    }

    public function reports() : HasMany {
        return $this->hasMany(MessageReport::class, 'id', 'message_id');
    }

    public function sender() : BelongsTo {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function isRead() : bool {
        return $this->read_at != null;
    }
}
