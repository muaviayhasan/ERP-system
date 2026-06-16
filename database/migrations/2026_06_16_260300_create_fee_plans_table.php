<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('fee_structure_id')->nullable()->index();
            $table->string('schedule_type'); // enum: [installments,lump_sum,monthly,quarterly,full_payment]
            $table->integer('number_of_payments')->nullable();
            $table->date('start_date')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_plans');
    }
};
