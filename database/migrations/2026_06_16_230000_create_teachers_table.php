<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('teacher_code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('cnic')->nullable();
            $table->string('photo_url')->nullable();
            $table->foreignId('campus_id')->nullable()->index();
            $table->foreignId('department_id')->nullable()->index();
            $table->string('designation');
            $table->string('institute_type')->nullable();
            $table->decimal('weekly_workload_hours', 4, 1)->nullable();
            $table->decimal('max_workload_hours', 4, 1)->default(40);
            $table->date('joining_date')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
