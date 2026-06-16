<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('isbn')->unique();
            $table->string('category');
            $table->string('subtitle')->nullable();
            $table->string('cover_image_url')->nullable();
            $table->integer('total_copies')->default(1);
            $table->integer('available_copies')->default(0);
            $table->string('availability_status')->default('available');
            $table->integer('borrow_count')->default(0);
            $table->foreignId('campus_id')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
