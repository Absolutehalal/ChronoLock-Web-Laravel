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
        Schema::create('class_lists', function (Blueprint $table) {
            $table->id('classID');
            $table->unsignedBigInteger('scheduleID')->unique();
            $table->foreign('scheduleID')->references('scheduleID')->on('schedules');
            $table->string('semester');
            $table->string('enrollmentKey');
            // $table->primary(['classID', 'scheduleID']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_lists');
    }
};
