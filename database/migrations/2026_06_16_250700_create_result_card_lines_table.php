<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('result_card_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('result_card_id')->nullable()->index();
            $table->foreignId('subject_id')->nullable()->index();
            $table->string('subject_code')->nullable();
            $table->string('subject_name')->nullable();
            $table->integer('max_marks')->default(100);
            $table->decimal('marks_obtained', 6, 2)->nullable();
            $table->string('grade')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('result_card_lines');
    }
};
