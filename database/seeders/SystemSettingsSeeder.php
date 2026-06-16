<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\ApiLog;
use App\Models\Backup;
use App\Models\Currency;
use App\Models\Integration;
use App\Models\Language;
use App\Models\Notice;
use App\Models\NotificationLog;
use App\Models\NotificationTemplate;
use App\Models\Report;
use App\Models\ScheduledReport;
use App\Models\SecurityEvent;
use App\Models\Setting;
use App\Models\Translation;
use App\Models\WebhookEvent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SystemSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSettings();
        $this->seedNotices();
        $this->seedNotificationTemplates();
        $this->seedReports();
        $this->seedActivityAndSecurity();
        $this->seedBackups();
        $this->seedLanguagesAndTranslations();
        $this->seedCurrencies();
        $this->seedIntegrations();
        $this->seedWebhookEvents();
        $this->seedApiLogs();
    }

    private function seedSettings(): void
    {
        $settings = [
            // general
            ['general', 'institution_name', 'Greenfield International University', 'string'],
            ['general', 'institution_short_name', 'GIU', 'string'],
            ['general', 'support_email', 'support@greenfield.edu', 'string'],
            ['general', 'support_phone', '+1-202-555-0142', 'string'],
            ['general', 'maintenance_mode', '0', 'boolean'],
            // localization
            ['localization', 'default_language', 'EN', 'select'],
            ['localization', 'timezone', 'America/New_York', 'select'],
            ['localization', 'date_format', 'Y-m-d', 'select'],
            ['localization', 'base_currency', 'USD', 'select'],
            // seo
            ['seo', 'meta_title', 'Greenfield International University', 'string'],
            ['seo', 'meta_description', 'Empowering future leaders through quality education.', 'text'],
            ['seo', 'meta_keywords', 'university,education,admissions,erp', 'string'],
            // integration
            ['integration', 'google_analytics_id', 'G-XXXXXXXXXX', 'string'],
            ['integration', 'recaptcha_enabled', '1', 'boolean'],
            ['integration', 'payment_gateway', 'stripe', 'select'],
            // security
            ['security', 'two_factor_required', '0', 'boolean'],
            ['security', 'password_min_length', '8', 'integer'],
            ['security', 'session_timeout_minutes', '30', 'integer'],
            ['security', 'max_login_attempts', '5', 'integer'],
            // notifications
            ['notifications', 'email_enabled', '1', 'boolean'],
            ['notifications', 'sms_enabled', '0', 'boolean'],
            ['notifications', 'push_enabled', '1', 'boolean'],
            ['notifications', 'digest_frequency', 'daily', 'select'],
            // academic
            ['academic', 'grading_system', 'GPA (4.0 Scale)', 'select'],
            ['academic', 'min_attendance_required', '75', 'integer'],
            ['academic', 'pass_mark_threshold', '40', 'integer'],
            ['academic', 'academic_year_start_month', 'September', 'select'],
            // financial
            ['financial', 'invoice_prefix', 'INV-', 'string'],
            ['financial', 'tax_percentage', '5.00', 'float'],
            ['financial', 'late_fee_grace_days', '7', 'integer'],
            ['financial', 'fiscal_year_start', '07-01', 'string'],
            // backup
            ['backup', 'auto_backup_enabled', '1', 'boolean'],
            ['backup', 'backup_frequency', 'daily', 'select'],
            ['backup', 'backup_retention_days', '30', 'integer'],
            ['backup', 'storage_provider', 's3', 'select'],
            // user_defaults
            ['user_defaults', 'default_role', 'student', 'select'],
            ['user_defaults', 'dark_mode', '0', 'boolean'],
            ['user_defaults', 'email_alerts', '1', 'boolean'],
            ['user_defaults', 'preferred_language', 'EN', 'select'],
        ];

        foreach ($settings as [$group, $key, $value, $type]) {
            Setting::create([
                'group' => $group,
                'key' => $key,
                'value' => $value,
                'type' => $type,
            ]);
        }
    }

    private function seedNotices(): void
    {
        Notice::create([
            'title' => 'Fall Semester Registration Now Open',
            'category' => 'Academic',
            'type' => 'announcement',
            'description' => 'Registration for the Fall 2026 semester is now open. All students must complete course registration through the student portal before the deadline. Late registrations will incur an additional fee.',
            'priority' => 'high',
            'audience' => ['students', 'guardians'],
            'publish_date' => Carbon::parse('2026-06-01'),
            'require_acknowledgment' => true,
            'status' => 'published',
            'created_by' => 1,
        ]);

        Notice::create([
            'title' => 'Campus Maintenance Notice',
            'category' => 'Facilities',
            'type' => 'alert',
            'description' => 'The main library will be closed for scheduled maintenance from June 20 to June 22. Online resources remain accessible during this period.',
            'priority' => 'normal',
            'audience' => ['students', 'teachers', 'staff'],
            'publish_date' => Carbon::parse('2026-06-10'),
            'require_acknowledgment' => false,
            'status' => 'published',
            'created_by' => 1,
        ]);

        Notice::create([
            'title' => 'Annual Sports Day - Save the Date',
            'category' => 'Events',
            'type' => 'event',
            'description' => 'Our Annual Sports Day will be held on July 15, 2026. Students interested in participating should register with their respective sports coordinators by July 1.',
            'priority' => 'low',
            'audience' => ['all'],
            'publish_date' => Carbon::parse('2026-06-15'),
            'require_acknowledgment' => false,
            'status' => 'draft',
            'created_by' => 1,
        ]);
    }

    private function seedNotificationTemplates(): void
    {
        $admission = NotificationTemplate::create([
            'name' => 'Admission Confirmation',
            'category' => 'Admissions',
            'subject' => 'Welcome to {{institution_name}}',
            'body' => 'Dear {{student_name}}, your admission to {{program_name}} has been confirmed. Your student code is {{student_code}}.',
            'channels' => ['email', 'sms'],
            'status' => 'active',
        ]);

        $feeReminder = NotificationTemplate::create([
            'name' => 'Fee Payment Reminder',
            'category' => 'Finance',
            'subject' => 'Upcoming Fee Payment Due',
            'body' => 'Dear {{student_name}}, your fee installment of {{amount}} is due on {{due_date}}. Please pay to avoid late fees.',
            'channels' => ['email', 'sms', 'push'],
            'status' => 'active',
        ]);

        NotificationTemplate::create([
            'name' => 'Exam Schedule Published',
            'category' => 'Examinations',
            'subject' => 'Your Exam Schedule is Available',
            'body' => 'Dear {{student_name}}, the exam schedule for {{exam_name}} has been published. Please check the portal for details.',
            'channels' => ['email', 'push'],
            'status' => 'draft',
        ]);

        NotificationLog::create([
            'template_id' => $admission->id,
            'type_label' => 'Admission Confirmation',
            'channel' => 'email',
            'recipients_count' => 120,
            'failed_count' => 2,
            'status' => 'sent',
            'sent_at' => Carbon::parse('2026-06-12 09:30:00'),
        ]);

        NotificationLog::create([
            'template_id' => $feeReminder->id,
            'type_label' => 'Fee Payment Reminder',
            'channel' => 'sms',
            'recipients_count' => 340,
            'failed_count' => 11,
            'status' => 'sent',
            'sent_at' => Carbon::parse('2026-06-14 08:00:00'),
        ]);
    }

    private function seedReports(): void
    {
        $reports = [
            ['Student Enrollment Summary', 'Academic', 'pdf', ['academic_year' => '2025-2026', 'campus' => 'Main']],
            ['Fee Collection Report', 'Finance', 'xlsx', ['from' => '2026-01-01', 'to' => '2026-06-30']],
            ['Attendance Overview', 'Attendance', 'pdf', ['month' => 'June', 'year' => 2026]],
            ['Examination Results Analysis', 'Examinations', 'pdf', ['exam' => 'Midterm 2026']],
            ['Staff Payroll Statement', 'HR', 'xlsx', ['month' => 'May', 'year' => 2026]],
            ['Outstanding Dues Report', 'Finance', 'csv', ['status' => 'overdue']],
        ];

        $reportModels = [];
        foreach ($reports as [$name, $category, $format, $params]) {
            $reportModels[] = Report::create([
                'name' => $name,
                'category' => $category,
                'format' => $format,
                'parameters' => $params,
                'generated_by' => 1,
                'generated_at' => Carbon::parse('2026-06-15 14:00:00'),
            ]);
        }

        ScheduledReport::create([
            'report_id' => $reportModels[1]->id,
            'name' => 'Monthly Fee Collection',
            'frequency' => 'monthly',
            'run_at' => '01 00:00',
            'format' => 'xlsx',
            'is_active' => true,
            'last_run_at' => Carbon::parse('2026-06-01 00:00:00'),
        ]);

        ScheduledReport::create([
            'report_id' => $reportModels[2]->id,
            'name' => 'Weekly Attendance Digest',
            'frequency' => 'weekly',
            'run_at' => 'Monday 07:00',
            'format' => 'pdf',
            'is_active' => true,
            'last_run_at' => Carbon::parse('2026-06-09 07:00:00'),
        ]);
    }

    private function seedActivityAndSecurity(): void
    {
        ActivityLog::create([
            'audit_ref' => 'AUD-2026-000001',
            'user_id' => 1,
            'user_name' => 'System Administrator',
            'role' => 'admin',
            'module' => 'Settings',
            'action' => 'update',
            'description' => 'Updated security settings.',
            'changes' => ['session_timeout_minutes' => ['old' => '60', 'new' => '30']],
            'ip_address' => '192.168.1.10',
            'device' => 'Chrome on Windows',
            'protocol' => 'HTTPS',
            'origin_id' => 'web-portal',
            'mfa_status' => 'verified',
            'geo_lat' => 38.8951000,
            'geo_lng' => -77.0364000,
            'status' => 'success',
        ]);

        ActivityLog::create([
            'audit_ref' => 'AUD-2026-000002',
            'user_id' => 1,
            'user_name' => 'System Administrator',
            'role' => 'admin',
            'module' => 'Users',
            'action' => 'create',
            'description' => 'Created a new staff account.',
            'changes' => ['email' => 'newstaff@greenfield.edu'],
            'ip_address' => '192.168.1.10',
            'device' => 'Chrome on Windows',
            'protocol' => 'HTTPS',
            'origin_id' => 'web-portal',
            'mfa_status' => 'verified',
            'geo_lat' => 38.8951000,
            'geo_lng' => -77.0364000,
            'status' => 'success',
        ]);

        SecurityEvent::create([
            'user_entity' => 'admin@greenfield.edu',
            'user_id' => 1,
            'action_trigger' => 'failed_login',
            'risk_level' => 'medium',
            'ip_address' => '203.0.113.45',
            'occurred_at' => Carbon::parse('2026-06-14 23:12:00'),
        ]);

        SecurityEvent::create([
            'user_entity' => 'admin@greenfield.edu',
            'user_id' => 1,
            'action_trigger' => 'new_device_login',
            'risk_level' => 'low',
            'ip_address' => '198.51.100.22',
            'occurred_at' => Carbon::parse('2026-06-15 08:45:00'),
        ]);
    }

    private function seedBackups(): void
    {
        Backup::create([
            'name' => 'daily-backup-2026-06-15',
            'type' => 'full',
            'size_bytes' => 524288000,
            'size_label' => '500 MB',
            'storage_provider' => 's3',
            'checksum' => 'a1b2c3d4e5f6a7b8c9d0e1f2a3b4c5d6',
            'created_by' => 1,
            'is_automated' => true,
            'status' => 'success',
        ]);

        Backup::create([
            'name' => 'manual-backup-2026-06-16',
            'type' => 'database',
            'size_bytes' => 104857600,
            'size_label' => '100 MB',
            'storage_provider' => 'local',
            'checksum' => 'f6e5d4c3b2a1098f7e6d5c4b3a2f1e0d',
            'created_by' => 1,
            'is_automated' => false,
            'status' => 'success',
        ]);
    }

    private function seedLanguagesAndTranslations(): void
    {
        Language::create(['name' => 'English', 'code' => 'EN', 'is_enabled' => true, 'is_default' => true, 'is_rtl' => false]);
        Language::create(['name' => 'Spanish', 'code' => 'ES', 'is_enabled' => true, 'is_default' => false, 'is_rtl' => false]);
        Language::create(['name' => 'French', 'code' => 'FR', 'is_enabled' => true, 'is_default' => false, 'is_rtl' => false]);
        Language::create(['name' => 'Arabic', 'code' => 'AR', 'is_enabled' => true, 'is_default' => false, 'is_rtl' => true]);
        Language::create(['name' => 'German', 'code' => 'DE', 'is_enabled' => false, 'is_default' => false, 'is_rtl' => false]);

        $translations = [
            ['dashboard.welcome', 'Welcome', 'Bienvenido', 'ES', 'approved'],
            ['dashboard.welcome', 'Welcome', 'Bienvenue', 'FR', 'approved'],
            ['dashboard.welcome', 'Welcome', 'مرحبا', 'AR', 'approved'],
            ['menu.students', 'Students', 'Estudiantes', 'ES', 'approved'],
            ['menu.fees', 'Fees', 'Frais', 'FR', 'pending'],
        ];

        foreach ($translations as [$key, $default, $value, $lang, $status]) {
            Translation::create([
                'key' => $key,
                'default_text' => $default,
                'value' => $value,
                'language_code' => $lang,
                'status' => $status,
            ]);
        }
    }

    private function seedCurrencies(): void
    {
        Currency::create(['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'is_base' => true]);
        Currency::create(['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'is_base' => false]);
        Currency::create(['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£', 'is_base' => false]);
        Currency::create(['code' => 'PKR', 'name' => 'Pakistani Rupee', 'symbol' => '₨', 'is_base' => false]);
        Currency::create(['code' => 'AED', 'name' => 'UAE Dirham', 'symbol' => 'د.إ', 'is_base' => false]);
    }

    private function seedIntegrations(): void
    {
        Integration::create([
            'provider' => 'Stripe',
            'type' => 'payment',
            'is_enabled' => true,
            'status' => 'connected',
            'credentials' => ['public_key' => 'pk_test_xxx', 'secret_key' => 'sk_test_xxx'],
        ]);

        Integration::create([
            'provider' => 'Twilio',
            'type' => 'sms',
            'is_enabled' => true,
            'status' => 'connected',
            'credentials' => ['account_sid' => 'ACxxxx', 'auth_token' => 'xxxx', 'from' => '+12025550100'],
        ]);

        Integration::create([
            'provider' => 'SendGrid',
            'type' => 'email',
            'is_enabled' => true,
            'status' => 'connected',
            'credentials' => ['api_key' => 'SG.xxxx'],
        ]);

        Integration::create([
            'provider' => 'Amazon S3',
            'type' => 'storage',
            'is_enabled' => true,
            'status' => 'connected',
            'credentials' => ['bucket' => 'giu-erp-storage', 'region' => 'us-east-1', 'access_key' => 'AKIAxxxx'],
        ]);

        Integration::create([
            'provider' => 'Firebase FCM',
            'type' => 'push',
            'is_enabled' => false,
            'status' => 'available',
            'credentials' => null,
        ]);
    }

    private function seedWebhookEvents(): void
    {
        $events = [
            ['student.created', 'Student Created', true],
            ['student.updated', 'Student Updated', true],
            ['fee.paid', 'Fee Paid', true],
            ['exam.published', 'Exam Results Published', false],
            ['admission.approved', 'Admission Approved', true],
            ['user.login', 'User Login', false],
        ];

        foreach ($events as [$key, $label, $enabled]) {
            WebhookEvent::create([
                'event_key' => $key,
                'label' => $label,
                'is_enabled' => $enabled,
            ]);
        }
    }

    private function seedApiLogs(): void
    {
        ApiLog::create([
            'method' => 'GET',
            'endpoint' => '/api/v1/students',
            'status_code' => 200,
            'latency_ms' => 142,
            'called_at' => Carbon::parse('2026-06-16 10:15:00'),
        ]);

        ApiLog::create([
            'method' => 'POST',
            'endpoint' => '/api/v1/fees/payments',
            'status_code' => 201,
            'latency_ms' => 318,
            'called_at' => Carbon::parse('2026-06-16 10:18:00'),
        ]);
    }
}
