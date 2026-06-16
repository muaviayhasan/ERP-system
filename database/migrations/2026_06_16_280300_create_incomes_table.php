<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->foreignId('category_id')->nullable()->index();
            $table->decimal('amount', 15, 2);
            $table->decimal('tax_percent', 5, 2)->default(0);
            $table->foreignId('campus_id')->nullable()->index();
            $table->string('payment_method'); // enum: [bank_transfer,cash,check,card_payment]
            $table->string('status')->default('pending'); // enum: [received,confirmed,pending]
            $table->string('module_link')->nullable();
            $table->date('income_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
