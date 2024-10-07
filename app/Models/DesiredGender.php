<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesiredGender extends Model
{
    use HasFactory;
    public function users(){
        return $this->hasMany(User::class, 'desired_gender_id', );
    }
}
