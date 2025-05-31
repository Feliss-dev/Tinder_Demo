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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->tinyInteger('is_admin')->default(0);

            $table->date('birth_date')->nullable();

            $table->text('bio')->nullable();

            $table->json('images')->nullable();

            $table->timestamp('last_seen_at')->nullable();

            $table->boolean('is_fake')->default(false);

            $table->unsignedInteger('warn_count')->default(0);

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();

            $table->string('google_id')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('banned_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
        Schema::dropIfExists('users');
    }
};
