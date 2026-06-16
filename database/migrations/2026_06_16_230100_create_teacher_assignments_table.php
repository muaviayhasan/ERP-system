<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->nullable()->index();
            $table->string('institute_type')->nullable();
            $table->foreignId('department_id')->nullable()->index();
            $table->foreignId('program_id')->nullable()->index();
            $table->foreignId('class_id')->nullable()->index();
            $table->foreignId('subject_id')->nullable()->index();
            $table->foreignId('course_id')->nullable()->index();
            $table->foreignId('section_id')->nullable()->index();
            $table->foreignId('semester_id')->nullable()->index();
            $table->string('credits')->nullable();
            $table->decimal('lecture_hours', 4, 1)->nullable();
            $table->decimal('lab_hours', 4, 1)->nullable();
            $table->decimal('weekly_hours', 4, 1)->nullable();
            $table->decimal('max_weekly_hours', 4, 1)->default(40);
            $table->string('timetable_status')->default('pending');
            $table->boolean('has_conflict')->default(false);
            $table->string('conflict_note')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_assignments');
    }
};
