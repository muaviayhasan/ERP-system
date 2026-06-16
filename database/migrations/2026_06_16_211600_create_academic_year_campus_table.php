<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_year_campus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->index();
            $table->foreignId('campus_id')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_year_campus');
    }
};
