<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->foreignId('program_id')->nullable()->index();
            $table->foreignId('department_id')->nullable()->index();
            $table->foreignId('campus_id')->nullable()->index();
            $table->foreignId('academic_year_id')->nullable()->index();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('total_credit_hours')->nullable();
            $table->boolean('generate_fee_plan')->default(true);
            $table->string('late_fee_rule')->nullable();
            $table->string('grading_system')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->boolean('fee_cycle_generated')->default(false);
            $table->boolean('exam_cycle_generated')->default(false);
            $table->string('status')->default('upcoming');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('semesters');
    }
};
