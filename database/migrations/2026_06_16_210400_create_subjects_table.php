<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('classification')->nullable(); // Core,Elective,Practical,Optional
            $table->string('institution_type')->nullable();
            $table->foreignId('department_id')->nullable()->index();
            $table->foreignId('class_id')->nullable()->index();
            $table->foreignId('semester_id')->nullable()->index();
            $table->decimal('credits', 3, 1)->nullable();
            $table->integer('total_marks')->default(100);
            $table->integer('weight_mid')->default(30);
            $table->integer('weight_final')->default(50);
            $table->foreignId('primary_teacher_id')->nullable()->index();
            $table->text('curriculum_focus')->nullable();
            $table->boolean('prerequisites_required')->default(false);
            $table->boolean('lock_structural_changes')->default(false);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
