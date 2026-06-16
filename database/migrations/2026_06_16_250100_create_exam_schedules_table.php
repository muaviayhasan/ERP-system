<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->nullable()->index();
            $table->foreignId('subject_id')->nullable()->index();
            $table->foreignId('program_id')->nullable()->index();
            $table->string('class_label')->nullable();
            $table->date('exam_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('duration_hours', 4, 2)->nullable();
            $table->string('venue')->nullable();
            $table->foreignId('invigilator_id')->nullable()->index();
            $table->string('exam_type')->nullable();
            $table->string('status')->default('Draft');
            $table->boolean('has_conflict')->default(false);
            $table->string('conflict_severity')->nullable();
            $table->string('conflict_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_schedules');
    }
};
