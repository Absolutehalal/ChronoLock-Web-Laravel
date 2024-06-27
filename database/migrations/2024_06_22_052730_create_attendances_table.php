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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id('attendanceID');
            $table->string('userID')->nullable();
            $table->foreign('userID')->references('idNumber')->on('users')->cascadeOnUpdate();
            $table->unsignedBigInteger('scheduleID');
            $table->foreign('scheduleID')->references('scheduleID')->on('class_lists')->cascadeOnUpdate();
            $table->unsignedBigInteger('classID')->nullable();
            $table->foreign('classID')->references('classID')->on('class_lists')->cascadeOnUpdate();
            $table->date('date');
            $table->time('time');
            $table->string('remark')->nullable();
            $table->softDeletes()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
