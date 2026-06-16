<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->index();
            $table->foreignId('student_fee_assignment_id')->nullable()->index();
            $table->foreignId('academic_year_id')->nullable()->index();
            $table->date('entry_date');
            $table->string('reference_number')->nullable();
            $table->string('transaction_type'); // enum: [fee,payment,scholarship,fine,discount]
            $table->string('description')->nullable();
            $table->decimal('debit', 12, 2)->default(0);
            $table->decimal('credit', 12, 2)->default(0);
            $table->decimal('balance', 12, 2)->default(0);
            $table->string('status')->default('completed');
            $table->string('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_ledger_entries');
    }
};
