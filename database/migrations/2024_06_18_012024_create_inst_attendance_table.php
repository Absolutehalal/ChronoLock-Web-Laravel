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
        Schema::create('inst_attendances', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('time');
            $table->string('instructor_name');
            $table->string('instructor_id');
            $table->string('status');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inst_attendances');
    }
};
