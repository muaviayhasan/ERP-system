<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->index();
            $table->foreignId('promotion_batch_id')->nullable()->index();
            $table->foreignId('from_academic_year_id')->nullable()->index();
            $table->foreignId('to_academic_year_id')->nullable()->index();
            $table->foreignId('from_semester_id')->nullable()->index();
            $table->foreignId('to_semester_id')->nullable()->index();
            $table->foreignId('to_program_id')->nullable()->index();
            $table->foreignId('to_section_id')->nullable()->index();
            $table->foreignId('to_batch_id')->nullable()->index();
            $table->foreignId('to_campus_id')->nullable()->index();
            $table->decimal('attendance_percentage', 5, 2)->nullable();
            $table->string('result_status')->nullable();
            $table->string('result_detail')->nullable();
            $table->decimal('gpa', 3, 2)->nullable();
            $table->string('fee_status')->nullable();
            $table->decimal('fee_due_amount', 8, 2)->nullable();
            $table->string('eligibility')->default('eligible');
            $table->string('fee_adjustment')->nullable();
            $table->boolean('manual_override')->default(false);
            $table->boolean('promoted')->default(false);
            $table->foreignId('promoted_by')->nullable()->index();
            $table->dateTime('promoted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_promotions');
    }
};
