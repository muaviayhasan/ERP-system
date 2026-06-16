<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('student_code')->unique();
            $table->integer('roll_number')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('full_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('cnic')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('father_name')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('institute_type')->nullable();
            $table->foreignId('campus_id')->nullable()->index();
            $table->foreignId('program_id')->nullable()->index();
            $table->foreignId('academic_year_id')->nullable()->index();
            $table->foreignId('current_semester_id')->nullable()->index();
            $table->foreignId('section_id')->nullable()->index();
            $table->foreignId('batch_id')->nullable()->index();
            $table->foreignId('advisor_id')->nullable()->index();
            $table->string('specialization')->nullable();
            $table->integer('current_credit_hours')->nullable();
            $table->string('scholarship_type')->nullable();
            $table->string('enrollment_session')->nullable();
            $table->string('status')->default('active');
            $table->string('admission_status')->default('enrolled');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
