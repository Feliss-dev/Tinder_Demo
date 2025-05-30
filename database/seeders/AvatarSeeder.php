<?php

namespace Database\Seeders;

use App\Models\Avatar;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class AvatarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed avatars.
        $avatarFiles = Storage::disk("public")->allFiles("avatar");
        Avatar::factory(User::count())->state(new Sequence(function (Sequence $sequence) use ($avatarFiles) {
            return [
                'user_id' => $sequence->index + 1,
                'path' => $avatarFiles[rand(0, count($avatarFiles) - 1)],
            ];
        }))->create([
            'is_active' => 1,
        ]);
    }
}
