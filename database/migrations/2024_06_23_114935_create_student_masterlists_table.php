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
        Schema::create('student_masterlists', function (Blueprint $table) {
            $table->id('MIT_ID');
            $table->unsignedBigInteger('userID');
            $table->foreign('userID')->references('id')->on('users')->cascadeOnUpdate();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('classID');
            $table->foreign('classID')->references('classID')->on('class_lists')->cascadeOnUpdate();;
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_masterlists');
    }
};
