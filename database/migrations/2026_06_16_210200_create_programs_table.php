<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('degree_level')->nullable(); // Bachelor,Master,PhD,Associate,Diploma
            $table->foreignId('department_id')->nullable()->index();
            $table->string('faculty')->nullable();
            $table->boolean('multi_department_access')->default(false);
            $table->decimal('total_years', 3, 1)->nullable();
            $table->integer('total_semesters')->nullable();
            $table->integer('total_credits')->nullable();
            $table->foreignId('coordinator_user_id')->nullable()->index();
            $table->boolean('allow_admissions')->default(true);
            $table->boolean('lock_structure')->default(false);
            $table->string('catalog_banner_path')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
