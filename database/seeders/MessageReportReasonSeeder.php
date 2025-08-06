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
            ['desc' => "report_message.reasons.harassment"],
            ['desc' => "report_message.reasons.impersonation"],
            ['desc' => "report_message.reasons.distribute_forbidden"],
            ['desc' => "report_message.reasons.hate_speech"],
            ['desc' => "report_message.reasons.unauthorized_sales"],
            ['desc' => "report_message.reasons.scams"],
            ['desc' => "report_message.reasons.others"],
        ]);
    }
}
