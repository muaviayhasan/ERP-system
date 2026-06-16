<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('type')->nullable(); // Core,Elective,Lab,General
            $table->text('description')->nullable();
            $table->foreignId('campus_id')->nullable()->index();
            $table->foreignId('program_id')->nullable()->index();
            $table->foreignId('department_id')->nullable()->index();
            $table->foreignId('semester_id')->nullable()->index();
            $table->integer('credit_hours')->nullable();
            $table->integer('total_marks')->default(100);
            $table->integer('passing_percentage')->default(50);
            $table->integer('weight_quiz')->default(10);
            $table->integer('weight_assignment')->default(15);
            $table->integer('weight_mid')->default(25);
            $table->integer('weight_final')->default(50);
            $table->foreignId('primary_instructor_id')->nullable()->index();
            $table->boolean('is_active')->default(true);
            $table->boolean('open_enrollment')->default(true);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
