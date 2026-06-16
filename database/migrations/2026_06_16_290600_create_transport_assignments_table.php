<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transport_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->index();
            $table->foreignId('route_id')->nullable()->index();
            $table->foreignId('pickup_stop_id')->nullable()->index();
            $table->foreignId('dropoff_stop_id')->nullable()->index();
            $table->decimal('monthly_fee', 8, 2)->nullable();
            $table->string('status')->default('assigned');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transport_assignments');
    }
};
