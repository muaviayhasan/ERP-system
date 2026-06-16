<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_gpas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->index();
            $table->foreignId('program_id')->nullable()->index();
            $table->foreignId('department_id')->nullable()->index();
            $table->foreignId('semester_id')->nullable()->index();
            $table->foreignId('academic_year_id')->nullable()->index();
            $table->integer('credits')->nullable();
            $table->decimal('gpa', 4, 2)->nullable();
            $table->decimal('cgpa', 4, 2)->nullable();
            $table->string('performance_status')->nullable();
            $table->string('academic_standing')->nullable();
            $table->dateTime('last_calculated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_gpas');
    }
};
