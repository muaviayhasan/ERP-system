<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_payments', function (Blueprint $table) {
            $table->id();
            $table->string('employee_type')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->foreignId('salary_structure_id')->nullable()->index();
            $table->string('payroll_month');
            $table->string('role_label')->nullable();
            $table->string('department_label')->nullable();
            $table->decimal('basic', 12, 2);
            $table->decimal('allowances', 12, 2)->default(0);
            $table->decimal('overtime_bonus', 12, 2)->default(0);
            $table->decimal('deductions', 12, 2)->default(0);
            $table->decimal('tax_deducted', 12, 2)->default(0);
            $table->decimal('net_salary', 12, 2);
            $table->string('status')->default('pending');
            $table->string('transaction_ref')->nullable();
            $table->dateTime('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_payments');
    }
};
