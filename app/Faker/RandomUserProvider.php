<?php

namespace App\Faker;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;

class RandomUserProvider extends Base
{
    const string PORTRAITS_URL = "https://randomuser.me/api/portraits/%s/%u.jpg";

    public function randomUserUrl(string $gender = null) {
        if ($gender == null) {
            $gender = "men";
        }

        return sprintf(RandomUserProvider::PORTRAITS_URL, $gender, rand(0, 99));
    }
}
