<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            ['name' => 'English'],
            ['name' => 'Spanish'],
            ['name' => 'French'],
            ['name' => 'German'],
            ['name' => 'Chinese'],
            ['name' => 'Japanese'],
            ['name' => 'Korean'],
            ['name' => 'Italian'],
            ['name' => 'Russian'],
            ['name' => 'Arabic'],
        ];

        // Insert languages into the database
        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}
