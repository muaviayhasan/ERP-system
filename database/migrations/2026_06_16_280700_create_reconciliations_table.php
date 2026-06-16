<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reconciliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ledger_entry_id')->nullable()->index();
            $table->string('bank_statement_ref')->nullable();
            $table->string('account_code')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('status')->default('unmatched'); // enum: [matched,unmatched,pending]
            $table->string('alert_type')->nullable();
            $table->foreignId('campus_id')->nullable()->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reconciliations');
    }
};
