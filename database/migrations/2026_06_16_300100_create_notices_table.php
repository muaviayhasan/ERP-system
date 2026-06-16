<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category');
            $table->string('type');
            $table->longText('description');
            $table->string('priority')->default('normal');
            $table->json('audience')->nullable();
            $table->date('publish_date')->nullable();
            $table->boolean('require_acknowledgment')->default(false);
            $table->string('status')->default('draft');
            $table->foreignId('created_by')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
