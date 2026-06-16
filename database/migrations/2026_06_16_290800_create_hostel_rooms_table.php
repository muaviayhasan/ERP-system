<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hostel_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hostel_id')->nullable()->index();
            $table->string('room_number');
            $table->string('floor')->nullable();
            $table->string('type'); // enum: [single,double,twin,quad,dormitory]
            $table->integer('capacity');
            $table->integer('available_beds')->default(0);
            $table->string('status')->default('available');
            $table->decimal('room_rate', 8, 2)->nullable();
            $table->string('rate_period')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hostel_rooms');
    }
};
