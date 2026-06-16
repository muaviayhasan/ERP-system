<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->string('transaction_id')->nullable();
            $table->foreignId('student_id')->nullable()->index();
            $table->foreignId('fee_payment_id')->nullable()->index();
            $table->foreignId('program_id')->nullable()->index();
            $table->foreignId('campus_id')->nullable()->index();
            $table->decimal('total_payable', 12, 2);
            $table->decimal('amount_paid', 12, 2);
            $table->decimal('balance', 12, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('reference_number')->nullable();
            $table->foreignId('collected_by')->nullable()->index();
            $table->text('notes')->nullable();
            $table->date('issued_at');
            $table->string('status')->default('paid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_receipts');
    }
};
