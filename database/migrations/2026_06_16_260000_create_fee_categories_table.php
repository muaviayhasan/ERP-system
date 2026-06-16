<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('code_assignment')->default('auto');
            $table->text('description')->nullable();
            $table->string('fee_type'); // enum: [one_time,monthly,annual,semester_based,quarterly]
            $table->decimal('default_amount', 12, 2)->default(0);
            $table->string('currency')->default('USD');
            $table->boolean('applies_to_school')->default(false);
            $table->boolean('applies_to_college')->default(false);
            $table->boolean('applies_to_university')->default(false);
            $table->boolean('late_fee_enabled')->default(false);
            $table->string('late_fee_type')->nullable();
            $table->decimal('late_fee_amount', 12, 2)->nullable();
            $table->integer('grace_period_days')->default(0);
            $table->boolean('tax_applicable')->default(false);
            $table->decimal('tax_percentage', 5, 2)->nullable();
            $table->boolean('scholarship_eligible')->default(false);
            $table->boolean('refundable')->default(false);
            $table->boolean('auto_generate_on_admission')->default(false);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_categories');
    }
};
