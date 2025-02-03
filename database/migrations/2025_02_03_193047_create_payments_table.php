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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ride_id')->constrained('rides')->references('id')->onDelete('cascade');
            $table->foreignId('driver_id')->constrained('drivers')->references('driver_id')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->references('customer_id')->onDelete('cascade');
            $table->float('amount');
            $table->enum('method', ['paypal', 'stripe', 'paymob']);
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid');
            $table->integer('transaction_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
