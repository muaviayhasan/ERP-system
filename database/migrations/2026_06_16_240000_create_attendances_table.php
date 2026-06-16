<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->index();
            $table->foreignId('class_id')->nullable()->index();
            $table->foreignId('section_id')->nullable()->index();
            $table->foreignId('subject_id')->nullable()->index();
            $table->foreignId('teacher_id')->nullable()->index();
            $table->foreignId('campus_id')->nullable()->index();
            $table->date('date');
            $table->string('session')->default('morning')->nullable();
            $table->string('status')->default('present'); // present,absent,late,leave
            $table->string('lecture_no')->nullable();
            $table->string('room')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('remarks')->nullable();
            $table->foreignId('marked_by')->nullable()->index();
            $table->string('marked_method')->default('manual_web')->nullable();
            $table->dateTime('marked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
