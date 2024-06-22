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
            $table->unsignedBigInteger('id'); // Use unsignedBigInteger for foreign key
            $table->date('date');
            $table->time('time');
            $table->string('remark');
            // $table->timestamps();

            // Define foreign key constraint
            $table->foreign('id')->references('id')->on('users')->onDelete('cascade');
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
