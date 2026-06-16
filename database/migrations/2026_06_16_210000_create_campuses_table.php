<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('institution_type')->nullable(); // University,College,School,Vocational
            $table->text('description')->nullable();
            $table->string('street_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state_province')->nullable();
            $table->integer('founded_year')->nullable();
            $table->string('status')->default('active');
            $table->boolean('enable_online_admissions')->default(true);
            $table->boolean('centralized_fee_collection')->default(false);
            $table->boolean('hostel_management')->default(false);
            $table->string('primary_bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_swift_code')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campuses');
    }
};
