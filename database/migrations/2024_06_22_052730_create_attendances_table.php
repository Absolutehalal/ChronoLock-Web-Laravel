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
            $table->string('userID', 50)->nullable();
            $table->foreign('userID')->references('idNumber')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->unsignedBigInteger('classID')->nullable();
            $table->foreign('classID')->references('classID')->on('class_lists')->cascadeOnUpdate()->nullOnDelete();
            $table->date('date');
            $table->time('time')->nullable();
            $table->string('remark', 50)->nullable();
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
