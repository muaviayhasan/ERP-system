<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->string('provider');
            $table->string('type');
            $table->boolean('is_enabled')->default(false);
            $table->string('status')->default('available');
            // Stored encrypted (see Integration model cast) — ciphertext, not JSON.
            $table->text('credentials')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};
