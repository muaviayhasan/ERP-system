<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fine_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // enum: [library,discipline,attendance,attire]
            $table->string('level')->nullable();
            $table->string('calculation_method')->default('per_day'); // enum: [fixed,per_day,percentage_of_fee]
            $table->decimal('amount', 10, 2);
            $table->integer('grace_period_days')->default(0);
            $table->boolean('enable_max_cap')->default(false);
            $table->decimal('max_cap_amount', 10, 2)->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fine_rules');
    }
};
