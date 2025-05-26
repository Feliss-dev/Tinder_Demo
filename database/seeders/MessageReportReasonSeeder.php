<?php

namespace Database\Seeders;

use App\Models\MessageReportReason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageReportReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MessageReportReason::factory()->createMany([
            ['desc' => "Harassment"],
            ['desc' => "Impersonation"],
            ['desc' => "Distribution of inappropriate contents"],
            ['desc' => "Hate speech"],
            ['desc' => "Unauthorized sales"],
            ['desc' => "Scams"],
            ['desc' => "Others"],
        ]);
    }
}
