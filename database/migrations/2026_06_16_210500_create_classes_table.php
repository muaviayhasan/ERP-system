<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('institution_type')->nullable();
            $table->string('academic_level')->nullable(); // Primary,Secondary,Higher Secondary,Undergraduate,Postgraduate,Doctorate
            $table->string('board')->nullable();
            $table->foreignId('campus_id')->nullable()->index();
            $table->foreignId('semester_id')->nullable()->index();
            $table->foreignId('coordinator_user_id')->nullable()->index();
            $table->integer('batch_count')->nullable();
            $table->integer('total_credit_hours')->nullable();
            $table->boolean('multi_campus_sharing')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('allow_admissions')->default(false);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
