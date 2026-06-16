<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_structure_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_structure_id')->nullable()->index();
            $table->foreignId('fee_category_id')->nullable()->index();
            $table->string('name');
            $table->decimal('amount', 12, 2);
            $table->string('frequency');
            $table->boolean('taxable')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_structure_components');
    }
};
