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
        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();
            $table->date('booking_date');
            $table->unsignedInteger('booking_id');
            $table->unsignedInteger('table_id');
            $table->json('menus')->nullable();
            $table->tinyInteger('guest_qty')->default(1);
            $table->unsignedInteger('user_id');
            $table->string('status',10)->default('pending')->comment('pending,approved,cancel');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_items');
    }
};
