<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->date('entry_date');
            $table->string('type'); // enum: [fee,salary,expense,other]
            $table->foreignId('account_id')->nullable()->index();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->string('status')->default('pending'); // enum: [posted,pending,reversed]
            $table->decimal('previous_balance', 15, 2)->nullable();
            $table->decimal('adjusted_balance', 15, 2)->nullable();
            $table->foreignId('campus_id')->nullable()->index();
            $table->text('description')->nullable();
            $table->foreignId('student_id')->nullable()->index();
            $table->string('invoice_no')->nullable();
            $table->string('source_module')->nullable();
            $table->foreignId('created_by')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
    }
};
