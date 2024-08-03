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
            $table->id('id');
            $table->string('accountName', 50)->nullable();
            $table->string('firstName', 50)->nullable();
            $table->string('lastName', 50)->nullable();
            $table->string('email', 50)->unique();
            $table->string('idNumber', 50)->nullable()->unique();
            $table->string('userType', 50)->nullable();
            $table->string('password', 100)->nullable()->unique();
            $table->string('avatar', 100)->nullable();
            $table->string('google_id', 50)->nullable();
            $table->string('RFID_Code', 50)->nullable()->unique();
            $table->rememberToken();
            $table->timestamps();
            $table->timestamp('email_verified_at')->nullable();
            $table->softDeletes()->nullable();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 50)->primary();
            $table->string('token', 50);
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 50)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
