<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->string('title');
            $table->foreignId('category_id')->nullable()->index();
            $table->decimal('amount', 15, 2);
            $table->decimal('tax_percent', 5, 2)->default(0);
            $table->string('currency')->default('USD');
            $table->foreignId('campus_id')->nullable()->index();
            $table->string('status')->default('pending'); // enum: [pending,approved,paid,rejected]
            $table->foreignId('approver_id')->nullable()->index();
            $table->string('payee')->nullable();
            $table->date('expense_date');
            $table->string('receipt_path')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
