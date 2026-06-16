<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hostel_beds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->nullable()->index();
            $table->string('bed_label');
            $table->string('status')->default('vacant'); // enum: [vacant,occupied]
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hostel_beds');
    }
};
