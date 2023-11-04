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
            $table->string('name',100);
            $table->string('email',100);
            $table->string('customer_mobile',20);
            $table->timestamp('booking_date')->nullable();
            $table->tinyInteger('guest_qty');
            $table->json('tbl_id');
            $table->json('menus');
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
