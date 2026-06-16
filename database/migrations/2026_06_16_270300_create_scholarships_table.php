<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('type'); // enum: [merit,need,sports,institutional]
            $table->string('value_type'); // enum: [percentage,fixed_amount]
            $table->decimal('value', 12, 2);
            $table->string('level')->nullable();
            $table->text('criteria')->nullable();
            $table->decimal('estimated_liability', 12, 2)->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};
