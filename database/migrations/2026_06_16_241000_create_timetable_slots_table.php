<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timetable_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('timetable_id')->nullable()->index();
            $table->foreignId('subject_id')->nullable()->index();
            $table->foreignId('teacher_id')->nullable()->index();
            $table->foreignId('section_id')->nullable()->index();
            $table->string('day_of_week');
            $table->date('slot_date')->nullable();
            $table->string('period')->nullable();
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->decimal('duration_hours', 4, 2)->nullable();
            $table->string('room')->nullable();
            $table->integer('capacity')->nullable();
            $table->string('slot_type')->default('lecture')->nullable();
            $table->boolean('has_conflict')->default(false);
            $table->string('conflict_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetable_slots');
    }
};
