<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable()->unique();
            $table->string('exam_type'); // Final,Midterm,Quiz,Practical,Supplementary,Annual
            $table->string('scope_label')->nullable();
            $table->foreignId('academic_year_id')->nullable()->index();
            $table->foreignId('program_id')->nullable()->index();
            $table->foreignId('department_id')->nullable()->index();
            $table->foreignId('semester_id')->nullable()->index();
            $table->foreignId('campus_id')->nullable()->index();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('total_marks')->default(100);
            $table->integer('passing_marks')->default(35);
            $table->boolean('is_online')->default(false);
            $table->boolean('multi_set_papers')->default(false);
            $table->string('status')->default('Scheduled');
            $table->string('result_status')->default('Pending');
            $table->integer('subjects_count')->nullable();
            $table->integer('students_count')->nullable();
            $table->foreignId('created_by')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
