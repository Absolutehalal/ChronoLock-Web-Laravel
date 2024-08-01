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
        Schema::create('rfid_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('RFID_Code')->unique();
            $table->foreign('RFID_Code')->references('RFID_Code')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('RFID_Status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfid_accounts');
    }
};
