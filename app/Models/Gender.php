<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    use HasFactory;
    public function users(){
        return $this->belongsToMany(User::class,'gender_users', 'user_id', 'gender_id');
    }
}