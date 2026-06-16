<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarship_application_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_application_id')->nullable()->index();
            $table->string('action');
            $table->string('status')->nullable();
            $table->foreignId('performed_by')->nullable()->index();
            $table->dateTime('performed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarship_application_logs');
    }
};
