<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hostel_maintenance_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('room_id')->nullable()->index();
            $table->foreignId('hostel_id')->nullable()->index();
            $table->string('category'); // enum: [maintenance,incident]
            $table->string('issue_type')->nullable();
            $table->text('description')->nullable();
            $table->string('priority')->nullable();
            $table->foreignId('reported_by')->nullable()->index();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hostel_maintenance_tickets');
    }
};
