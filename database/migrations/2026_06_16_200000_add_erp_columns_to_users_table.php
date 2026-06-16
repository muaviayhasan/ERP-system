<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('name');
            $table->string('avatar')->nullable();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable(); // male, female, other
            $table->string('country')->nullable();
            $table->text('residential_address')->nullable();
            $table->string('employee_id')->nullable();
            $table->foreignId('campus_id')->nullable()->index();
            $table->foreignId('department_id')->nullable()->index();
            $table->string('employee_tier')->nullable();
            $table->foreignId('reporting_manager_id')->nullable()->index();
            $table->date('joining_date')->nullable();
            $table->string('status')->default('active'); // active, inactive, suspended, pending
            $table->boolean('two_factor_enabled')->default(false);
            $table->text('two_factor_secret')->nullable();
            $table->dateTime('last_login_at')->nullable();
            $table->integer('total_logins')->default(0);
            $table->string('preferred_language')->nullable()->default('EN');
            $table->boolean('dark_mode')->default(false);
            $table->boolean('email_alerts')->default(true);
            $table->boolean('sms_notifications')->default(false);
            $table->boolean('system_alerts')->default(true);
            $table->string('oauth_provider')->nullable(); // google, microsoft
            $table->string('oauth_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username', 'avatar', 'phone', 'date_of_birth', 'gender', 'country',
                'residential_address', 'employee_id', 'campus_id', 'department_id',
                'employee_tier', 'reporting_manager_id', 'joining_date', 'status',
                'two_factor_enabled', 'two_factor_secret', 'last_login_at', 'total_logins',
                'preferred_language', 'dark_mode', 'email_alerts', 'sms_notifications',
                'system_alerts', 'oauth_provider', 'oauth_id',
            ]);
        });
    }
};
