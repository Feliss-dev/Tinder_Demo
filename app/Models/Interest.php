<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    use HasFactory;
    public function users(){
        return $this->belongsToMany(User::class, 'interest_users', 'interest_id', 'user_id');
    }
}
