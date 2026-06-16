<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarship_application_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_application_id')->nullable()->index('sad_application_id_index');
            $table->string('file_name');
            $table->string('file_path')->nullable();
            $table->string('document_type')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarship_application_documents');
    }
};
