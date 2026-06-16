<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->nullable()->index();
            $table->string('type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('reported_by')->nullable()->index();
            $table->integer('due_in_days')->nullable();
            $table->string('status')->default('pending');
            $table->dateTime('logged_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_maintenance_logs');
    }
};
