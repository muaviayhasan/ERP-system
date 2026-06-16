<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marks_entry_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->nullable()->index();
            $table->foreignId('subject_id')->nullable()->index();
            $table->foreignId('evaluator_id')->nullable()->index();
            $table->integer('total_students')->nullable();
            $table->integer('marks_entered_count')->nullable();
            $table->integer('pending_count')->nullable();
            $table->boolean('hod_review_required')->default(true);
            $table->boolean('submitted_for_approval')->default(false);
            $table->boolean('auto_publish_on_release')->default(false);
            $table->decimal('highest_mark', 6, 2)->nullable();
            $table->decimal('average_mark', 6, 2)->nullable();
            $table->decimal('lowest_mark', 6, 2)->nullable();
            $table->dateTime('last_synced_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marks_entry_sessions');
    }
};
