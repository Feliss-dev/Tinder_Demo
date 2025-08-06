<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ChatbotMessage extends Model
{
    use HasFactory;

    public $timestamps = ["created_at"];
    const UPDATED_AT = null;

    protected $guarded = [];

    function user(): BelongsTo {
        return $this->belongsTo(User::class);

        // return $this->hasOne(User::class, 'id', 'user_id');
    }
}
