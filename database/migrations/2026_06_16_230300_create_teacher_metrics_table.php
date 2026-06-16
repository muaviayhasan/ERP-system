<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->nullable()->index();
            $table->integer('classes_count')->nullable();
            $table->integer('subjects_count')->nullable();
            $table->decimal('attendance_rate', 5, 2)->nullable();
            $table->decimal('student_rating', 3, 2)->nullable();
            $table->integer('research_papers')->nullable();
            $table->integer('mentorship_count')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_metrics');
    }
};
