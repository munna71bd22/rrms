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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('customer_mobile',15);
            $table->timestamp('booking_start_time')->nullable();
            $table->timestamp('booking_end_time')->nullable();
            $table->tinyInteger('guest_qty');
            $table->string('status',10)->default('pending')->comment('pending,approved,cancel');
            $table->unsignedInteger('confirmed_by')->nullable();
            $table->timestamp('confirmed_date')->nullable();
            $table->tinyText('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
