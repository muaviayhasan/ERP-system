<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('scope')->default('all_campuses'); // all_campuses,specific_campuses
            $table->string('status')->default('upcoming');
            $table->boolean('link_fee_structure')->default(true);
            $table->boolean('auto_roll_attendance')->default(false);
            $table->boolean('fees_configured')->nullable();
            $table->boolean('exams_configured')->nullable();
            $table->boolean('attendance_enabled')->nullable();
            $table->boolean('prevent_date_overlap')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};
