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
            $table->string('courseCode')->nullable()->unique();
            $table->string('courseName')->nullable();
            $table->string('userID')->nullable();
            $table->foreign('userID')->references('idNumber')->on('users')->cascadeOnUpdate();
            $table->string('instFirstName')->nullable();
            $table->string('instLastName')->nullable();
            $table->string('program')->nullable();
            $table->string('section')->nullable();
            $table->string('year')->nullable();
            $table->time('startTime')->nullable();
            $table->time('endTime')->nullable();
            $table->date('startDate')->nullable();
            $table->date('endDate')->nullable();
            $table->string('day')->nullable();
            $table->string('scheduleStatus')->nullable();
            $table->string('scheduleTitle')->nullable()->unique();
            $table->string('scheduleType')->nullable();
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
