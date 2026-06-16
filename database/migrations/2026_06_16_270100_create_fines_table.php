<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->index();
            $table->foreignId('fine_rule_id')->nullable()->index();
            $table->string('reason');
            $table->decimal('amount', 10, 2);
            $table->date('date_applied');
            $table->string('status')->default('pending'); // enum: [pending,overdue,paid,waived]
            $table->foreignId('collected_by')->nullable()->index();
            $table->dateTime('collected_at')->nullable();
            $table->foreignId('waived_by')->nullable()->index();
            $table->dateTime('waived_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};
