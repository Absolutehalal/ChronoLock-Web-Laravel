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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id('scheduleID');
            $table->string('courseCode', 50)->nullable()->unique();
            $table->string('courseName', 50)->nullable();
            $table->string('userID', 50)->nullable();
            $table->foreign('userID', 50)->references('idNumber')->on('users')->cascadeOnUpdate();
            $table->string('instFirstName', 50)->nullable();
            $table->string('instLastName', 50)->nullable();
            $table->string('program', 50)->nullable();
            $table->string('section', 50)->nullable();
            $table->string('year', 50)->nullable();
            $table->time('startTime')->nullable();
            $table->time('endTime')->nullable();
            $table->date('startDate')->nullable();
            $table->date('endDate')->nullable();
            $table->string('day', 50)->nullable();
            $table->string('scheduleStatus', 50)->nullable();
            $table->string('scheduleTitle', 50)->nullable()->unique();
            $table->string('scheduleType', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
