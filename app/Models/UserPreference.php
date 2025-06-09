<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;
    protected $guarded =[]; //guarded =[];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function language() {
        return $this->hasOne(ApplicationLanguage::class, 'id', 'language_id');
    }
}
