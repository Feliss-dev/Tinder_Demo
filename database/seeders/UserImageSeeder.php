<?php

namespace Database\Seeders;

use App\Faker\PicsumPhotoProvider;
use App\Faker\RandomUserProvider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new RandomUserProvider($faker));
        $faker->addProvider(new PicsumPhotoProvider($faker));

        for ($i = 0; $i < 30; $i++) {
            try {
                $content = file_get_contents($faker->picsumUrl(500, 300));
                $storagePath = 'user_images/' . ((string)Str::uuid()) . '.jpg';

                Storage::disk("public")->put($storagePath, $content);
            } catch (\ErrorException) {}
        }
    }
}
