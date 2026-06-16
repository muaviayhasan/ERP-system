<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('full_name');
            $table->string('relationship')->nullable(); // father,mother,guardian,sibling
            $table->string('cnic')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('residential_address')->nullable();
            $table->string('photo_url')->nullable();
            $table->boolean('is_primary_fee_payer')->default(false);
            $table->boolean('is_emergency_authorized')->default(false);
            $table->boolean('phone_verified')->default(false);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guardians');
    }
};
