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
        Schema::create('schedule_notes', function (Blueprint $table) {
            $table->id('noteID');
            $table->unsignedBigInteger('scheduleID');
            $table->foreign('scheduleID')->references('scheduleID')->on('schedules')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('note',500);
            $table->date('date');
            $table->time('time');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_notes');
    }
};
