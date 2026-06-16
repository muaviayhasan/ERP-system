<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->index();
            $table->foreignId('student_fee_assignment_id')->nullable()->index();
            $table->foreignId('fee_installment_id')->nullable()->index();
            $table->foreignId('receipt_id')->nullable()->index();
            $table->string('transaction_id')->nullable();
            $table->decimal('amount_payable', 12, 2);
            $table->decimal('amount_paid', 12, 2);
            $table->decimal('balance', 12, 2)->default(0);
            $table->decimal('late_fee_amount', 12, 2)->default(0);
            $table->string('payment_method'); // enum: [cash,bank,card,online]
            $table->string('reference_number')->nullable();
            $table->boolean('auto_allocate_installments')->default(true);
            $table->foreignId('collected_by')->nullable()->index();
            $table->dateTime('paid_at');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_payments');
    }
};
