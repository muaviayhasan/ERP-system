<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_documents', function (Blueprint $table) {
            $table->id();
            $table->string('document_code')->nullable()->unique();
            $table->foreignId('student_id')->nullable()->index();
            $table->string('document_type');
            $table->string('title');
            $table->string('file_path')->nullable();
            $table->string('file_type')->nullable();
            $table->string('status')->default('pending');
            $table->string('uploaded_by')->nullable();
            $table->text('verification_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->index();
            $table->dateTime('verified_at')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->dateTime('uploaded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_documents');
    }
};
