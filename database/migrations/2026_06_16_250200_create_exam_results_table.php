<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->nullable()->index();
            $table->foreignId('student_id')->nullable()->index();
            $table->foreignId('subject_id')->nullable()->index();
            $table->foreignId('evaluator_id')->nullable()->index();
            $table->string('attendance_status')->default('Present');
            $table->decimal('marks_obtained', 6, 2)->nullable();
            $table->integer('total_marks')->default(100);
            $table->decimal('percentage', 5, 2)->nullable();
            $table->string('grade')->nullable();
            $table->string('remarks')->nullable();
            $table->boolean('is_flagged')->default(false);
            $table->string('validation_error')->nullable();
            $table->string('entry_status')->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
