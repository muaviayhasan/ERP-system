<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_fee_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->index();
            $table->foreignId('fee_structure_id')->nullable()->index();
            $table->foreignId('fee_plan_id')->nullable()->index();
            $table->foreignId('program_id')->nullable()->index();
            $table->foreignId('semester_id')->nullable()->index();
            $table->foreignId('campus_id')->nullable()->index();
            $table->foreignId('academic_year_id')->nullable()->index();
            $table->foreignId('scholarship_id')->nullable()->index();
            $table->decimal('scholarship_amount', 12, 2)->nullable();
            $table->decimal('total_fee', 12, 2)->default(0);
            $table->decimal('final_payable', 12, 2)->default(0);
            $table->decimal('total_paid', 12, 2)->default(0);
            $table->decimal('total_pending', 12, 2)->default(0);
            $table->date('next_due_date')->nullable();
            $table->boolean('late_fee_enabled')->default(true);
            $table->boolean('email_notifications_enabled')->default(true);
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_fee_assignments');
    }
};
