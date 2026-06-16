<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('result_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->index();
            $table->foreignId('exam_id')->nullable()->index();
            $table->foreignId('academic_year_id')->nullable()->index();
            $table->foreignId('class_id')->nullable()->index();
            $table->foreignId('section_id')->nullable()->index();
            $table->foreignId('campus_id')->nullable()->index();
            $table->string('verification_code')->nullable()->unique();
            $table->decimal('cumulative_gpa', 4, 2)->nullable();
            $table->string('overall_grade')->nullable();
            $table->integer('rank_in_class')->nullable();
            $table->integer('class_size')->nullable();
            $table->string('result_status')->default('Draft');
            $table->boolean('is_published')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->boolean('allow_reevaluation')->default(true);
            $table->decimal('attendance_percent', 5, 2)->nullable();
            $table->string('fee_status')->nullable();
            $table->foreignId('class_teacher_id')->nullable()->index();
            $table->foreignId('registrar_id')->nullable()->index();
            $table->dateTime('generated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('result_cards');
    }
};
