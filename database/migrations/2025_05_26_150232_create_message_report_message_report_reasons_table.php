<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('message_report_message_report_reasons', function (Blueprint $table) {
            $table->id();

            $table->foreignId('report_id')->constrained('message_reports')->cascadeOnDelete();
            $table->foreignId('reason_id')->constrained('message_report_reasons')->cascadeOnDelete();

            $table->unique(['report_id', 'reason_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_report_message_report_reasons');
    }
};
