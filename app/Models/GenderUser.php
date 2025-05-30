<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GenderUser extends Model
{
    use HasFactory;

    function user() : BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    function gender() : BelongsTo {
        return $this->belongsTo(Gender::class, 'gender_id');
    }
}
