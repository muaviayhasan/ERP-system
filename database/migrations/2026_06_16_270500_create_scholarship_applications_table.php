<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarship_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->index();
            $table->foreignId('scholarship_id')->nullable()->index();
            $table->foreignId('program_id')->nullable()->index();
            $table->foreignId('semester_id')->nullable()->index();
            $table->string('institute')->nullable();
            $table->string('type');
            $table->decimal('requested_discount_percent', 5, 2)->nullable();
            $table->decimal('requested_value', 12, 2)->nullable();
            $table->decimal('original_fee', 12, 2)->nullable();
            $table->decimal('final_payable', 12, 2)->nullable();
            $table->text('reason')->nullable();
            $table->decimal('cgpa', 3, 2)->nullable();
            $table->integer('documents_count')->nullable();
            $table->boolean('gpa_check_passed')->nullable();
            $table->boolean('policy_compliance_passed')->nullable();
            $table->boolean('no_duplicate_passed')->nullable();
            $table->string('priority')->default('normal')->nullable();
            $table->string('status')->default('pending'); // enum: [pending,under_review,approved,rejected,changes_requested]
            $table->text('decision_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->index();
            $table->date('application_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarship_applications');
    }
};
