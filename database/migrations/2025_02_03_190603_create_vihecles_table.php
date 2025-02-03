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
        Schema::create('vihecles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('drivers')->references('driver_id')->onDelete('cascade');
            $table->string('license_plate');
            $table->enum('type', ['car', 'bike', 'cycle', 'taxi'])->default('car');
            $table->int('fuel');
            $table->string('color');
            $table->float('model');
            $table->int('manifactur_year');
            $table->string('manifactur_company');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vihecles');
    }
};
