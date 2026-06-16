<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('result_reevaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('result_card_id')->nullable()->index();
            $table->foreignId('student_id')->nullable()->index();
            $table->foreignId('subject_id')->nullable()->index();
            $table->foreignId('rechecked_by')->nullable()->index();
            $table->string('status')->default('Requested');
            $table->string('note')->nullable();
            $table->dateTime('requested_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('result_reevaluations');
    }
};
