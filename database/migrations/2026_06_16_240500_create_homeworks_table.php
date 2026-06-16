<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homeworks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('subject_id')->nullable()->index();
            $table->foreignId('class_id')->nullable()->index();
            $table->foreignId('teacher_id')->nullable()->index();
            $table->date('due_date');
            $table->integer('total_marks')->nullable();
            $table->integer('expected_submissions')->nullable();
            $table->string('status')->default('assigned');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homeworks');
    }
};
