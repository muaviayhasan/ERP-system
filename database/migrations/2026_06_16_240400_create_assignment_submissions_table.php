<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->nullable()->index();
            $table->foreignId('student_id')->nullable()->index();
            $table->dateTime('submitted_at')->nullable();
            $table->string('status')->default('pending');
            $table->integer('marks_obtained')->nullable();
            $table->string('attachment_path')->nullable();
            $table->foreignId('graded_by')->nullable()->index();
            $table->dateTime('graded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};
