<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('low_attendance_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->index();
            $table->foreignId('class_id')->nullable()->index();
            $table->decimal('attendance_percentage', 5, 2);
            $table->decimal('required_percentage', 5, 2)->default(75);
            $table->string('risk_level'); // critical,high,moderate
            $table->integer('absents_count')->nullable();
            $table->integer('lates_count')->nullable();
            $table->decimal('trend', 5, 2)->nullable();
            $table->string('scholarship_status')->nullable();
            $table->boolean('exam_eligibility_restricted')->default(false);
            $table->boolean('sms_warning_sent')->default(false);
            $table->boolean('guardian_notified')->default(false);
            $table->dateTime('last_warning_sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('low_attendance_alerts');
    }
};
