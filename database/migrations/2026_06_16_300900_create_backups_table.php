<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->string('size_label')->nullable();
            $table->string('storage_provider')->default('local');
            $table->string('checksum')->nullable();
            $table->foreignId('created_by')->nullable()->index();
            $table->boolean('is_automated')->default(false);
            $table->string('status')->default('success');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backups');
    }
};
