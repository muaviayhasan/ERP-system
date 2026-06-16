<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->nullable()->index();
            $table->string('grading_system')->default('GPA (4.0 Scale)');
            $table->integer('pass_mark_threshold')->default(40);
            $table->integer('min_attendance_required')->default(75);
            $table->integer('attendance_grace_minutes')->default(15);
            $table->string('attendance_session_limit')->nullable();
            $table->integer('attendance_warning_threshold')->default(75);
            $table->integer('attendance_critical_threshold')->default(60);
            $table->string('exam_structure')->default('Semester Structure');
            $table->integer('weight_final')->default(50);
            $table->integer('weight_midterm')->default(30);
            $table->integer('weight_assignments_lab')->default(10);
            $table->integer('weight_quizzes')->default(10);
            $table->json('approval_workflow')->nullable();
            $table->boolean('promotion_enabled')->default(true);
            $table->decimal('promotion_min_gpa', 3, 2)->nullable();
            $table->integer('promotion_max_fail_subjects')->nullable();
            $table->boolean('university_mode_enabled')->default(false);
            $table->integer('min_credit_load')->nullable();
            $table->integer('max_credit_load')->nullable();
            $table->string('year_start_month')->nullable();
            $table->string('makeup_class_allowance')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_settings');
    }
};
