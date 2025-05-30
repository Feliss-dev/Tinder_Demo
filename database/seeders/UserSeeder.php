<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userImageFiles = collect(Storage::disk("public")->allFiles('user_images'));

        // Seed fake users.
        User::factory(100)->state(new Sequence(function (Sequence $sequence) use ($userImageFiles) {
            Storage::disk("public")->allFiles('user_images');

            return [
                'birth_date' => Carbon::createFromTimestamp(rand(Carbon::now()->subYears(60)->timestamp, Carbon::now()->subYears(20)->timestamp)),
                'created_at' => Carbon::createFromTimestamp(rand(Carbon::now()->subMonths(2)->timestamp, Carbon::now()->subMonths(50)->timestamp)),
                'images' => json_encode($userImageFiles->random(rand(3, 8))),
            ];
        }))->create([
            'is_fake' => true,
            'is_admin' => false,
        ]);
    }
}
