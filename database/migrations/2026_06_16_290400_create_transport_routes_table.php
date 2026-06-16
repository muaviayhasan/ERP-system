<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transport_routes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('start_point')->nullable();
            $table->string('end_point')->nullable();
            $table->foreignId('vehicle_id')->nullable()->index();
            $table->integer('stops_count')->default(0);
            $table->integer('students_count')->default(0);
            $table->integer('duration_minutes')->nullable();
            $table->decimal('monthly_fee', 8, 2)->nullable();
            $table->string('status')->default('active');
            $table->foreignId('campus_id')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transport_routes');
    }
};
