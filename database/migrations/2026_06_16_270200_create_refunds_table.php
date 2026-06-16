<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->foreignId('student_id')->nullable()->index();
            $table->foreignId('program_id')->nullable()->index();
            $table->foreignId('semester_id')->nullable()->index();
            $table->string('refund_type'); // enum: [overpayment,withdrawal,course_change]
            $table->string('reason')->nullable();
            $table->text('description')->nullable();
            $table->string('payment_reference')->nullable();
            $table->decimal('total_paid', 12, 2)->nullable();
            $table->decimal('actual_due', 12, 2)->nullable();
            $table->decimal('max_eligible_refund', 12, 2)->nullable();
            $table->decimal('requested_amount', 12, 2);
            $table->decimal('approved_amount', 12, 2)->nullable();
            $table->boolean('payment_verified')->default(false);
            $table->boolean('ledger_reconciled')->default(false);
            $table->string('status')->default('pending');
            $table->text('remarks')->nullable();
            $table->foreignId('approved_by')->nullable()->index();
            $table->string('payment_method')->nullable();
            $table->date('payout_date')->nullable();
            $table->string('payout_reference')->nullable();
            $table->date('request_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
