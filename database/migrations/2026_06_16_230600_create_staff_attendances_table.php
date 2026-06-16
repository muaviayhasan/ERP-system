<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->nullable()->index();
            $table->foreignId('department_id')->nullable()->index();
            $table->foreignId('campus_id')->nullable()->index();
            $table->date('attendance_date');
            $table->string('shift')->nullable();
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->decimal('work_hours', 4, 1)->nullable();
            $table->string('status')->default('Present');
            $table->boolean('is_overtime')->default(false);
            $table->boolean('needs_correction')->default(false);
            $table->foreignId('marked_by')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_attendances');
    }
};
