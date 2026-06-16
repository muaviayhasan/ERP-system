<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_number')->unique();
            $table->string('type');
            $table->integer('capacity');
            $table->integer('occupied_seats')->default(0);
            $table->foreignId('route_id')->nullable()->index();
            $table->foreignId('driver_id')->nullable()->index();
            $table->string('status')->default('operational');
            $table->foreignId('campus_id')->nullable()->index();
            $table->integer('last_service_km')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
