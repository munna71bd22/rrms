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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('title',100);
            $table->string('tbl_id',20);
            $table->string('type',20)->default('table');
            $table->string('room_no',20)->nullable();
            $table->string('tbl_type',20)->default('square');
            $table->string('canvas_obj')->nullable();
            $table->unsignedInteger('floor_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
