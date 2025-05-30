<?php

namespace Database\Seeders;

use App\Faker\PicsumPhotoProvider;
use App\Faker\RandomUserProvider;
use App\Models\Avatar;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class AvatarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new RandomUserProvider($faker));
        $faker->addProvider(new PicsumPhotoProvider($faker));

        for ($i = 0; $i < 20; $i++) {
            $storagePath = 'avatar/' . ((string)Str::uuid()) . '.jpg';

            $content = file_get_contents($faker->randomUserUrl("women"));

            Storage::disk("public")->put($storagePath, $content);
        }
    }
}
