<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('section_type')->nullable(); // Morning,Evening,Weekend,Batch
            $table->foreignId('class_id')->nullable()->index();
            $table->foreignId('campus_id')->nullable()->index();
            $table->string('institution_type')->nullable();
            $table->integer('max_capacity')->default(40);
            $table->integer('current_enrollment')->nullable();
            $table->boolean('enable_waitlist')->default(true);
            $table->foreignId('class_teacher_id')->nullable()->index();
            $table->boolean('is_active')->default(true);
            $table->boolean('allow_admissions')->default(true);
            $table->boolean('lock_structure')->default(false);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
