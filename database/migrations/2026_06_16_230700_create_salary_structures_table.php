<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_structures', function (Blueprint $table) {
            $table->id();
            $table->string('employee_type')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->decimal('basic_salary', 12, 2);
            $table->decimal('transport_allowance', 12, 2)->default(0);
            $table->decimal('medical_allowance', 12, 2)->default(0);
            $table->decimal('housing_allowance', 12, 2)->default(0);
            $table->decimal('overtime_rate', 8, 2)->nullable();
            $table->decimal('performance_bonus', 12, 2)->nullable();
            $table->string('currency')->default('USD');
            $table->date('effective_from')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_structures');
    }
};
