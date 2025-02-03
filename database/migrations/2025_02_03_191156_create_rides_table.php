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
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('drivers')->references('driver_id')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->references('customer_id')->onDelete('cascade');
            $table->string('pickup_location');
            $table->string('drop_location');
            $table->float('fare_price');
            $table->float('distance');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('ride_status', ['requested', 'ongoing', 'completed', 'cancelled'])->default('requested');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
