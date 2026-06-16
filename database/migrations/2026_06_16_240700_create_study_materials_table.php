<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('study_materials', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type'); // pdf,video,link,doc
            $table->foreignId('subject_id')->nullable()->index();
            $table->foreignId('class_id')->nullable()->index();
            $table->foreignId('folder_id')->nullable()->index();
            $table->foreignId('uploaded_by')->nullable()->index();
            $table->string('file_path')->nullable();
            $table->string('external_url')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->integer('download_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->date('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_materials');
    }
};
