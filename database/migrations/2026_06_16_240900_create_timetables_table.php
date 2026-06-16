<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->foreignId('campus_id')->nullable()->index();
            $table->foreignId('program_id')->nullable()->index();
            $table->foreignId('semester_id')->nullable()->index();
            $table->string('institute_type')->nullable();
            $table->date('week_start_date')->nullable();
            $table->date('week_end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
