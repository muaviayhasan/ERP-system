<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('institution_type')->nullable();
            $table->foreignId('campus_id')->nullable()->index();
            $table->foreignId('hod_user_id')->nullable()->index();
            $table->boolean('semester_system')->default(true);
            $table->boolean('credit_hour_system')->default(true);
            $table->boolean('is_active')->default(true);
            $table->boolean('allow_admissions')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
