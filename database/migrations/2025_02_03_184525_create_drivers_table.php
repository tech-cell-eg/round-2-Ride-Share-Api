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
        Schema::create('drivers', function (Blueprint $table) {
            $table->foreignId('driver_id')->constrained('users')->references('id')->cascadeOnDelete()->unique();
            $table->string('license_number')->unique();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->tinyInteger('average_rating');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
