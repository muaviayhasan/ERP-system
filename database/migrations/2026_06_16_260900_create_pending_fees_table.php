<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pending_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->index();
            $table->foreignId('student_fee_assignment_id')->nullable()->index();
            $table->foreignId('program_id')->nullable()->index();
            $table->decimal('amount_payable', 12, 2);
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->decimal('amount_pending', 12, 2);
            $table->decimal('late_fee_amount', 12, 2)->default(0);
            $table->date('due_date')->nullable();
            $table->integer('days_overdue')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_fees');
    }
};
