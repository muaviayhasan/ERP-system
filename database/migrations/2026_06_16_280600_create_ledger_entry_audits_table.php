<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ledger_entry_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ledger_entry_id')->nullable()->index();
            $table->string('action');
            $table->string('description')->nullable();
            $table->foreignId('performed_by')->nullable()->index();
            $table->dateTime('performed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledger_entry_audits');
    }
};
