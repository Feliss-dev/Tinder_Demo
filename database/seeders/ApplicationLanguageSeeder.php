<?php

namespace Database\Seeders;

use App\Models\ApplicationLanguage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationLanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApplicationLanguage::factory()->createMany([
            [
                'code' => 'en',
                'description' => 'languages.en.name'
            ],
            [
                'code' => 'vi',
                'description' => 'languages.vi.name'
            ],
        ]);
    }
}
