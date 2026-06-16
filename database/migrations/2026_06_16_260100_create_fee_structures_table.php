<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->foreignId('campus_id')->nullable()->index();
            $table->string('institute_type')->nullable();
            $table->foreignId('program_id')->nullable()->index();
            $table->string('level')->nullable();
            $table->foreignId('academic_year_id')->nullable()->index();
            $table->string('billing_cycle');
            $table->decimal('total_fee', 12, 2)->default(0);
            $table->boolean('scholarship_available')->default(false);
            $table->boolean('installments_enabled')->default(false);
            $table->integer('installment_count')->nullable();
            $table->integer('billing_day_of_month')->nullable();
            $table->string('status')->default('draft');
            $table->integer('students_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
};
