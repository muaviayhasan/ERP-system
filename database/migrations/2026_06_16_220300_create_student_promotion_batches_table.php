<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_promotion_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_academic_year_id')->nullable()->index();
            $table->foreignId('to_academic_year_id')->nullable()->index();
            $table->foreignId('source_program_id')->nullable()->index();
            $table->foreignId('to_program_id')->nullable()->index();
            $table->foreignId('to_section_id')->nullable()->index();
            $table->foreignId('to_campus_id')->nullable()->index();
            $table->boolean('min_attendance_rule')->default(true);
            $table->integer('min_attendance_threshold')->nullable();
            $table->boolean('clear_fee_arrears_rule')->default(true);
            $table->boolean('manual_override_allowed')->default(false);
            $table->string('fee_adjustment')->nullable();
            $table->integer('total_students')->nullable();
            $table->integer('passed_count')->nullable();
            $table->integer('failed_count')->nullable();
            $table->integer('conditional_count')->nullable();
            $table->string('status')->default('draft');
            $table->foreignId('executed_by')->nullable()->index();
            $table->dateTime('executed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_promotion_batches');
    }
};
