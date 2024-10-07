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

            $table->date('birth_date')->required();

            $table->text('bio')->required();

            $table->unsignedBigInteger('gender_id')->nullable();
            $table->foreign('gender_id')->references('id')->on('genders')->onUpdate('cascade');

            $table->unsignedBigInteger('desired_gender_id')->nullable();
            $table->foreign('desired_gender_id')->references('id')->on('desired_genders')->onUpdate('cascade');

            $table->unsignedBigInteger('dating_goal_id')->nullable();
            $table->foreign('dating_goal_id')->references('id')->on('dating_goals')->onUpdate('cascade');



            $table->string('images')->required();
            $table->boolean('is_fake')->default(false);
            $table->timestamp('email_verified_at')->required();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
