<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grade_scales', function (Blueprint $table) {
            $table->id();
            $table->string('grade');
            $table->decimal('min_percent', 5, 2)->nullable();
            $table->decimal('max_percent', 5, 2)->nullable();
            $table->decimal('min_gpa', 3, 2)->nullable();
            $table->decimal('max_gpa', 3, 2)->nullable();
            $table->decimal('gpa_point', 3, 2)->nullable();
            $table->boolean('is_passing')->default(true);
            $table->foreignId('program_id')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_scales');
    }
};
