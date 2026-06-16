<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notice_acknowledgments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notice_id')->nullable()->index();
            $table->foreignId('user_id')->nullable()->index();
            $table->dateTime('acknowledged_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notice_acknowledgments');
    }
};
