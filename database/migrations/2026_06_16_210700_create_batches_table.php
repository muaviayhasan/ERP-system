<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('batch_type')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->string('institution_type')->nullable();
            $table->foreignId('campus_id')->nullable()->index();
            $table->foreignId('program_id')->nullable()->index();
            $table->foreignId('class_id')->nullable()->index();
            $table->foreignId('semester_id')->nullable()->index();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->json('weekly_days')->nullable();
            $table->integer('max_students')->default(40);
            $table->boolean('allow_waitlist')->default(true);
            $table->foreignId('primary_instructor_id')->nullable()->index();
            $table->foreignId('fee_plan_id')->nullable()->index();
            $table->string('attendance_tracking')->nullable();
            $table->boolean('installments_allowed')->default(true);
            $table->boolean('open_for_admissions')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
