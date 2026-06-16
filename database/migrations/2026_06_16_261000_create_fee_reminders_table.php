<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pending_fee_id')->nullable()->index();
            $table->foreignId('student_id')->nullable()->index();
            $table->string('template');
            $table->string('channels')->nullable();
            $table->text('message')->nullable();
            $table->foreignId('sent_by')->nullable()->index();
            $table->dateTime('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_reminders');
    }
};
