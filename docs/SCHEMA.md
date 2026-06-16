# Education ERP — Database Schema (source of truth)

This document is the canonical schema derived from the Stitch design screens in
`public/stich`. Generator agents MUST follow the GLOBAL CONVENTIONS and produce
migrations, models, and seeders exactly per the table definitions below.

## GLOBAL CONVENTIONS

- **Framework**: Laravel 11. PHP 8.3+. MySQL.
- **Migrations** live in `database/migrations/`. Filename:
  `2026_06_16_<6digit-seq>_create_<table>_table.php` using the per-module
  sequence block assigned in each section (increment by 100). One table per file.
- Every table starts with `$table->id();` and ends with `$table->timestamps();`
  unless stated otherwise.
- **Foreign keys**: use `$table->foreignId('<name>_id')->nullable()->index();`
  — DO NOT use `->constrained()` (no DB-level FK constraints; relationships are
  expressed in Eloquent). Pivot FKs may be non-nullable.
- **Enums**: model as `$table->string('<col>')->default('<x>')` (NOT MySQL enum).
  Put the allowed values in a `// enum: [...]` comment.
- **Money**: `decimal(12,2)` (or `decimal(15,2)` for accounting). **Percent**:
  `decimal(5,2)`. **GPA**: `decimal(4,2)`.
- `(unique)` → add `->unique()`. `txt` → `text`. `json` → `json`. `big` →
  `unsignedBigInteger`. `dt` → `dateTime`. `bool` defaults to `false` unless a
  default is given.
- **Models** in `app/Models/`, namespace `App\Models`. Use
  `protected $guarded = [];`. Add a `casts()` method (Laravel 11 style) for
  dates, datetimes, booleans, json, and decimals. Add relationship methods
  (`belongsTo` / `hasMany` / `belongsToMany`) for every FK and pivot.
- **Reserved word**: the `classes` table maps to model **`SchoolClass`** with
  `protected $table = 'classes';`. Always reference it as `SchoolClass`.
- **Pivot tables** (listed at end of relevant module) are migration-only (no
  model); wire them via `belongsToMany` in the two related models.
- Each module provides ONE seeder `database/seeders/<Module>Seeder.php`. Do NOT
  edit `DatabaseSeeder.php` (wired centrally). Seed all lookup/config rows plus a
  few demo rows referencing core ids 1..5 (campuses/programs/students/teachers
  are seeded first by core modules).

## DSL legend
`col type [flags]` — `*`=nullable, `(unique)`=unique, `fk`=foreignId nullable
index (target inferred from name unless `fk:table` given), `d=X`=default X.

---

## MODULE 1 — Academic Structure  (seq block 210000)  → AcademicStructureSeeder

**campuses**: name str, code str(unique), institution_type str* `// University,College,School,Vocational`, description txt*, street_address str*, city str*, state_province str*, founded_year int*, status str d=active, enable_online_admissions bool d=true, centralized_fee_collection bool, hostel_management bool, primary_bank_name str*, bank_account_number str*, bank_swift_code str*

**departments**: name str, code str(unique), description txt*, institution_type str*, campus_id fk, hod_user_id fk:users, semester_system bool d=true, credit_hour_system bool d=true, is_active bool d=true, allow_admissions bool d=true

**programs**: name str, code str(unique), degree_level str* `// Bachelor,Master,PhD,Associate,Diploma`, department_id fk, faculty str*, multi_department_access bool, total_years dec(3,1)*, total_semesters int*, total_credits int*, coordinator_user_id fk:users, allow_admissions bool d=true, lock_structure bool, catalog_banner_path str*, status str d=active

**courses**: name str, code str(unique), type str* `// Core,Elective,Lab,General`, description txt*, campus_id fk, program_id fk, department_id fk, semester_id fk, credit_hours int*, total_marks int d=100, passing_percentage int d=50, weight_quiz int d=10, weight_assignment int d=15, weight_mid int d=25, weight_final int d=50, primary_instructor_id fk:teachers, is_active bool d=true, open_enrollment bool d=true, status str d=active

**subjects**: name str, code str(unique), classification str* `// Core,Elective,Practical,Optional`, institution_type str*, department_id fk, class_id fk:classes, semester_id fk, credits dec(3,1)*, total_marks int d=100, weight_mid int d=30, weight_final int d=50, primary_teacher_id fk:teachers, curriculum_focus txt*, prerequisites_required bool, lock_structural_changes bool, status str d=active

**classes** (model SchoolClass): name str, code str(unique), description txt*, institution_type str*, academic_level str* `// Primary,Secondary,Higher Secondary,Undergraduate,Postgraduate,Doctorate`, board str*, campus_id fk, semester_id fk, coordinator_user_id fk:users, batch_count int*, total_credit_hours int*, multi_campus_sharing bool, is_active bool d=true, allow_admissions bool, status str d=active

**sections**: name str, code str(unique), section_type str* `// Morning,Evening,Weekend,Batch`, class_id fk:classes, campus_id fk, institution_type str*, max_capacity int d=40, current_enrollment int*, enable_waitlist bool d=true, class_teacher_id fk:teachers, is_active bool d=true, allow_admissions bool d=true, lock_structure bool, status str d=active

**batches**: name str, code str(unique), batch_type str*, description txt*, status str d=active, institution_type str*, campus_id fk, program_id fk, class_id fk:classes, semester_id fk, start_date date*, end_date date*, weekly_days json*, max_students int d=40, allow_waitlist bool d=true, primary_instructor_id fk:teachers, fee_plan_id fk:fee_plans, attendance_tracking str*, installments_allowed bool d=true, open_for_admissions bool d=true

**semesters**: name str, code str(unique), description txt*, program_id fk, department_id fk, campus_id fk, academic_year_id fk, start_date date*, end_date date*, total_credit_hours int*, generate_fee_plan bool d=true, late_fee_rule str*, grading_system str*, is_locked bool, fee_cycle_generated bool, exam_cycle_generated bool, status str d=upcoming

**academic_years**: name str, start_date date, end_date date, scope str d=all_campuses `// all_campuses,specific_campuses`, status str d=upcoming, link_fee_structure bool d=true, auto_roll_attendance bool, fees_configured bool*, exams_configured bool*, attendance_enabled bool*, prevent_date_overlap bool d=true

**academic_settings**: academic_year_id fk, grading_system str d='GPA (4.0 Scale)', pass_mark_threshold int d=40, min_attendance_required int d=75, attendance_grace_minutes int d=15, attendance_session_limit str*, attendance_warning_threshold int d=75, attendance_critical_threshold int d=60, exam_structure str d='Semester Structure', weight_final int d=50, weight_midterm int d=30, weight_assignments_lab int d=10, weight_quizzes int d=10, approval_workflow json*, promotion_enabled bool d=true, promotion_min_gpa dec(3,2)*, promotion_max_fail_subjects int*, university_mode_enabled bool, min_credit_load int*, max_credit_load int*, year_start_month str*, makeup_class_allowance str*

Pivots (migration-only): **campus_program**(campus_id,program_id), **campus_department**(campus_id,department_id), **class_subject**(class_id,subject_id), **course_semester**(course_id,semester_id), **batch_student**(batch_id,student_id), **academic_year_campus**(academic_year_id,campus_id)

---

## MODULE 2 — Students  (seq block 220000)  → StudentSeeder

**students**: user_id fk:users, student_code str(unique), roll_number int*, first_name str, last_name str*, full_name str*, date_of_birth date*, gender str*, cnic str*, email str*, phone str*, father_name str*, photo_url str*, institute_type str*, campus_id fk, program_id fk, academic_year_id fk, current_semester_id fk:semesters, section_id fk, batch_id fk, advisor_id fk:teachers, specialization str*, current_credit_hours int*, scholarship_type str*, enrollment_session str*, status str d=active, admission_status str d=enrolled

**guardians**: user_id fk:users, full_name str, relationship str* `// father,mother,guardian,sibling`, cnic str*, phone str, email str*, residential_address txt*, photo_url str*, is_primary_fee_payer bool, is_emergency_authorized bool, phone_verified bool, status str d=active

**student_documents**: document_code str*(unique), student_id fk, document_type str, title str, file_path str*, file_type str*, status str d=pending, uploaded_by str*, verification_notes txt*, verified_by fk:users, verified_at dt*, issue_date date*, expiry_date date*, uploaded_at dt*

**student_promotion_batches**: from_academic_year_id fk:academic_years, to_academic_year_id fk:academic_years, source_program_id fk:programs, to_program_id fk:programs, to_section_id fk:sections, to_campus_id fk:campuses, min_attendance_rule bool d=true, min_attendance_threshold int*, clear_fee_arrears_rule bool d=true, manual_override_allowed bool, fee_adjustment str*, total_students int*, passed_count int*, failed_count int*, conditional_count int*, status str d=draft, executed_by fk:users, executed_at dt*

**student_promotions**: student_id fk, promotion_batch_id fk:student_promotion_batches, from_academic_year_id fk:academic_years, to_academic_year_id fk:academic_years, from_semester_id fk:semesters, to_semester_id fk:semesters, to_program_id fk:programs, to_section_id fk:sections, to_batch_id fk:batches, to_campus_id fk:campuses, attendance_percentage dec(5,2)*, result_status str*, result_detail str*, gpa dec(3,2)*, fee_status str*, fee_due_amount dec(8,2)*, eligibility str d=eligible, fee_adjustment str*, manual_override bool, promoted bool, promoted_by fk:users, promoted_at dt*

**student_activities**: student_id fk, activity_type str*, title str, description txt*, activity_date date

Pivot (migration-only): **guardian_student**(guardian_id,student_id, relationship str*, is_primary bool)

---

## MODULE 3 — Teachers, Staff & HR  (seq block 230000)  → HumanResourceSeeder

**teachers**: user_id fk:users, teacher_code str(unique), first_name str, last_name str, full_name str*, email str, phone str*, cnic str*, photo_url str*, campus_id fk, department_id fk, designation str, institute_type str*, weekly_workload_hours dec(4,1)*, max_workload_hours dec(4,1) d=40, joining_date date*, status str d=active

**teacher_assignments**: teacher_id fk, institute_type str*, department_id fk, program_id fk, class_id fk:classes, subject_id fk, course_id fk:courses, section_id fk, semester_id fk, credits str*, lecture_hours dec(4,1)*, lab_hours dec(4,1)*, weekly_hours dec(4,1)*, max_weekly_hours dec(4,1) d=40, timetable_status str d=pending, has_conflict bool, conflict_note str*, status str d=active

**teacher_activities**: teacher_id fk, activity_type str*, title str, description str*, reference_id str*, occurred_at dt

**teacher_metrics**: teacher_id fk, classes_count int*, subjects_count int*, attendance_rate dec(5,2)*, student_rating dec(3,2)*, research_papers int*, mentorship_count int*

**teacher_documents**: teacher_id fk, title str, file_url str, doc_type str*, uploaded_at dt*

**staff**: user_id fk:users, staff_code str(unique), first_name str, last_name str, full_name str*, email str*, phone str*, photo_url str*, department_id fk, campus_id fk, role str, shift str d=Morning, reporting_to_id fk:staff, joining_date date*, status str d=active

**staff_attendances**: staff_id fk, department_id fk, campus_id fk, attendance_date date, shift str*, check_in time*, check_out time*, work_hours dec(4,1)*, status str d=Present, is_overtime bool, needs_correction bool, marked_by fk:users

**salary_structures**: employee_type str*, employee_id big*, basic_salary dec(12,2), transport_allowance dec(12,2) d=0, medical_allowance dec(12,2) d=0, housing_allowance dec(12,2) d=0, overtime_rate dec(8,2)*, performance_bonus dec(12,2)*, currency str d=USD, effective_from date*

**salary_payments**: employee_type str*, employee_id big*, salary_structure_id fk:salary_structures, payroll_month str, role_label str*, department_label str*, basic dec(12,2), allowances dec(12,2) d=0, overtime_bonus dec(12,2) d=0, deductions dec(12,2) d=0, tax_deducted dec(12,2) d=0, net_salary dec(12,2), status str d=pending, transaction_ref str*, processed_at dt*

**payroll_rules**: name str, rule_type str, description str*, config json*, is_active bool d=true

Pivot (migration-only): **program_teacher**(program_id,teacher_id)

---

## MODULE 4 — Attendance & Academic Delivery  (seq block 240000)  → AttendanceAcademicSeeder

**attendances**: student_id fk, class_id fk:classes, section_id fk, subject_id fk, teacher_id fk:teachers, campus_id fk, date date, session str* d=morning, status str d=present `// present,absent,late,leave`, lecture_no str*, room str*, start_time time*, end_time time*, remarks str*, marked_by fk:users, marked_method str* d=manual_web, marked_at dt*

**low_attendance_alerts**: student_id fk, class_id fk:classes, attendance_percentage dec(5,2), required_percentage dec(5,2) d=75, risk_level str `// critical,high,moderate`, absents_count int*, lates_count int*, trend dec(5,2)*, scholarship_status str*, exam_eligibility_restricted bool, sms_warning_sent bool, guardian_notified bool, last_warning_sent_at dt*

**attendance_alert_rules**: name str, description str*, trigger_type str*, threshold_percentage dec(5,2)*, absence_count_trigger int*, is_enabled bool d=true

**assignments**: title str, code str*, description txt*, subject_id fk, class_id fk:classes, section_id fk, teacher_id fk:teachers, due_date date, total_marks int*, expected_submissions int*, status str d=active

**assignment_submissions**: assignment_id fk, student_id fk, submitted_at dt*, status str d=pending, marks_obtained int*, attachment_path str*, graded_by fk:teachers, graded_at dt*

**homeworks**: title str, code str*, description txt*, subject_id fk, class_id fk:classes, teacher_id fk:teachers, due_date date, total_marks int*, expected_submissions int*, status str d=assigned

**homework_submissions**: homework_id fk, student_id fk, status str d=not_submitted, submitted_at dt*, file_path str*, file_type str*, marks_obtained int*, total_marks int*, graded_by fk:teachers

**study_materials**: title str, description txt*, type str `// pdf,video,link,doc`, subject_id fk, class_id fk:classes, folder_id fk:study_material_folders, uploaded_by fk:teachers, file_path str*, external_url str*, file_size big*, download_count int d=0, view_count int d=0, is_active bool d=true, published_at date*

**study_material_folders**: name str, parent_id fk:study_material_folders, subject_id fk, class_id fk:classes, created_by fk:users

**timetables**: name str*, campus_id fk, program_id fk, semester_id fk, institute_type str*, week_start_date date*, week_end_date date*

**timetable_slots**: timetable_id fk, subject_id fk, teacher_id fk:teachers, section_id fk, day_of_week str, slot_date date*, period str*, start_time time, end_time time*, duration_hours dec(4,2)*, room str*, capacity int*, slot_type str* d=lecture, has_conflict bool, conflict_reason str*

---

## MODULE 5 — Exams & Results  (seq block 250000)  → ExamSeeder

**exams**: name str, code str*(unique), exam_type str `// Final,Midterm,Quiz,Practical,Supplementary,Annual`, scope_label str*, academic_year_id fk, program_id fk, department_id fk, semester_id fk, campus_id fk, start_date date*, end_date date*, start_time time*, end_time time*, total_marks int d=100, passing_marks int d=35, is_online bool, multi_set_papers bool, status str d=Scheduled, result_status str d=Pending, subjects_count int*, students_count int*, created_by fk:users

**exam_schedules**: exam_id fk, subject_id fk, program_id fk, class_label str*, exam_date date, start_time time, end_time time, duration_hours dec(4,2)*, venue str*, invigilator_id fk:teachers, exam_type str*, status str d=Draft, has_conflict bool, conflict_severity str*, conflict_note str*

**exam_results**: exam_id fk, student_id fk, subject_id fk, evaluator_id fk:teachers, attendance_status str d=Present, marks_obtained dec(6,2)*, total_marks int d=100, percentage dec(5,2)*, grade str*, remarks str*, is_flagged bool, validation_error str*, entry_status str d=Pending

**marks_entry_sessions**: exam_id fk, subject_id fk, evaluator_id fk:teachers, total_students int*, marks_entered_count int*, pending_count int*, hod_review_required bool d=true, submitted_for_approval bool, auto_publish_on_release bool, highest_mark dec(6,2)*, average_mark dec(6,2)*, lowest_mark dec(6,2)*, last_synced_at dt*

**grade_scales**: grade str, min_percent dec(5,2)*, max_percent dec(5,2)*, min_gpa dec(3,2)*, max_gpa dec(3,2)*, gpa_point dec(3,2)*, is_passing bool d=true, program_id fk

**student_gpas**: student_id fk, program_id fk, department_id fk, semester_id fk, academic_year_id fk, credits int*, gpa dec(4,2)*, cgpa dec(4,2)*, performance_status str*, academic_standing str*, last_calculated_at dt*

**result_cards**: student_id fk, exam_id fk, academic_year_id fk, class_id fk:classes, section_id fk, campus_id fk, verification_code str*(unique), cumulative_gpa dec(4,2)*, overall_grade str*, rank_in_class int*, class_size int*, result_status str d=Draft, is_published bool, is_locked bool, allow_reevaluation bool d=true, attendance_percent dec(5,2)*, fee_status str*, class_teacher_id fk:teachers, registrar_id fk:users, generated_at dt*

**result_card_lines**: result_card_id fk, subject_id fk, subject_code str*, subject_name str*, max_marks int d=100, marks_obtained dec(6,2)*, grade str*, remarks str*

**result_reevaluations**: result_card_id fk, student_id fk, subject_id fk, rechecked_by fk:teachers, status str d=Requested, note str*, requested_at dt*

**exam_schedule_conflicts**: exam_schedule_id fk, conflict_type str, severity str d=Warning, description str*, is_resolved bool

---

## MODULE 6 — Fees Core  (seq block 260000)  → FeeSeeder

**fee_categories**: name str, code str(unique), code_assignment str d=auto, description txt*, fee_type str `// one_time,monthly,annual,semester_based,quarterly`, default_amount dec(12,2) d=0, currency str d=USD, applies_to_school bool, applies_to_college bool, applies_to_university bool, late_fee_enabled bool, late_fee_type str*, late_fee_amount dec(12,2)*, grace_period_days int d=0, tax_applicable bool, tax_percentage dec(5,2)*, scholarship_eligible bool, refundable bool, auto_generate_on_admission bool, status str d=active

**fee_structures**: name str, code str(unique), campus_id fk, institute_type str*, program_id fk, level str*, academic_year_id fk, billing_cycle str, total_fee dec(12,2) d=0, scholarship_available bool, installments_enabled bool, installment_count int*, billing_day_of_month int*, status str d=draft, students_count int d=0

**fee_structure_components**: fee_structure_id fk, fee_category_id fk, name str, amount dec(12,2), frequency str, taxable bool

**fee_plans**: name str, fee_structure_id fk, schedule_type str `// installments,lump_sum,monthly,quarterly,full_payment`, number_of_payments int*, start_date date*, status str d=active

**student_fee_assignments**: student_id fk, fee_structure_id fk, fee_plan_id fk, program_id fk, semester_id fk, campus_id fk, academic_year_id fk, scholarship_id fk:scholarships, scholarship_amount dec(12,2)*, total_fee dec(12,2) d=0, final_payable dec(12,2) d=0, total_paid dec(12,2) d=0, total_pending dec(12,2) d=0, next_due_date date*, late_fee_enabled bool d=true, email_notifications_enabled bool d=true, status str d=pending

**fee_installments**: student_fee_assignment_id fk, installment_number int, label str*, due_date date, percentage dec(5,2)*, amount dec(12,2), amount_paid dec(12,2) d=0, status str d=pending, paid_at date*

**fee_payments**: student_id fk, student_fee_assignment_id fk, fee_installment_id fk, receipt_id fk:fee_receipts, transaction_id str*, amount_payable dec(12,2), amount_paid dec(12,2), balance dec(12,2) d=0, late_fee_amount dec(12,2) d=0, payment_method str `// cash,bank,card,online`, reference_number str*, auto_allocate_installments bool d=true, collected_by fk:users, paid_at dt, status str d=pending

**fee_receipts**: receipt_number str(unique), transaction_id str*, student_id fk, fee_payment_id fk, program_id fk, campus_id fk, total_payable dec(12,2), amount_paid dec(12,2), balance dec(12,2) d=0, payment_method str*, reference_number str*, collected_by fk:users, notes txt*, issued_at date, status str d=paid

**fee_ledger_entries**: student_id fk, student_fee_assignment_id fk, academic_year_id fk, entry_date date, reference_number str*, transaction_type str `// fee,payment,scholarship,fine,discount`, description str*, debit dec(12,2) d=0, credit dec(12,2) d=0, balance dec(12,2) d=0, status str d=completed, created_by str*

**pending_fees**: student_id fk, student_fee_assignment_id fk, program_id fk, amount_payable dec(12,2), amount_paid dec(12,2) d=0, amount_pending dec(12,2), late_fee_amount dec(12,2) d=0, due_date date*, days_overdue int*, status str d=pending

**fee_reminders**: pending_fee_id fk:pending_fees, student_id fk, template str, channels str*, message txt*, sent_by fk:users, sent_at dt*

---

## MODULE 7 — Fines, Refunds & Scholarships  (seq block 270000)  → ScholarshipFineSeeder

**fine_rules**: name str, type str `// library,discipline,attendance,attire`, level str*, calculation_method str d=per_day `// fixed,per_day,percentage_of_fee`, amount dec(10,2), grace_period_days int d=0, enable_max_cap bool, max_cap_amount dec(10,2)*, status str d=active

**fines**: student_id fk, fine_rule_id fk, reason str, amount dec(10,2), date_applied date, status str d=pending `// pending,overdue,paid,waived`, collected_by fk:users, collected_at dt*, waived_by fk:users, waived_at dt*

**refunds**: reference_no str(unique), student_id fk, program_id fk, semester_id fk, refund_type str `// overpayment,withdrawal,course_change`, reason str*, description txt*, payment_reference str*, total_paid dec(12,2)*, actual_due dec(12,2)*, max_eligible_refund dec(12,2)*, requested_amount dec(12,2), approved_amount dec(12,2)*, payment_verified bool, ledger_reconciled bool, status str d=pending, remarks txt*, approved_by fk:users, payment_method str*, payout_date date*, payout_reference str*, request_date date

**scholarships**: name str, code str(unique), type str `// merit,need,sports,institutional`, value_type str `// percentage,fixed_amount`, value dec(12,2), level str*, criteria txt*, estimated_liability dec(12,2)*, status str d=active

**scholarship_assignments**: student_id fk, scholarship_id fk:scholarships, discount_amount dec(12,2)*, status str d=active, assigned_by fk:users, expires_at date*

**scholarship_applications**: student_id fk, scholarship_id fk:scholarships, program_id fk, semester_id fk, institute str*, type str, requested_discount_percent dec(5,2)*, requested_value dec(12,2)*, original_fee dec(12,2)*, final_payable dec(12,2)*, reason txt*, cgpa dec(3,2)*, documents_count int*, gpa_check_passed bool*, policy_compliance_passed bool*, no_duplicate_passed bool*, priority str* d=normal, status str d=pending `// pending,under_review,approved,rejected,changes_requested`, decision_notes txt*, reviewed_by fk:users, application_date date

**scholarship_application_documents**: scholarship_application_id fk, file_name str, file_path str*, document_type str*

**scholarship_application_logs**: scholarship_application_id fk, action str, status str*, performed_by fk:users, performed_at dt

---

## MODULE 8 — Accounting / Finance  (seq block 280000)  → AccountingSeeder

**expense_categories**: name str(unique), slug str*, budget_amount dec(15,2)*, description txt*, is_active bool d=true

**expenses**: reference_no str(unique), title str, category_id fk:expense_categories, amount dec(15,2), tax_percent dec(5,2) d=0, currency str d=USD, campus_id fk, status str d=pending `// pending,approved,paid,rejected`, approver_id fk:users, payee str*, expense_date date, receipt_path str*, notes txt*, created_by fk:users

**income_categories**: name str(unique), slug str*, module_link str*, description txt*, is_active bool d=true

**incomes**: reference_no str(unique), title str, subtitle str*, category_id fk:income_categories, amount dec(15,2), tax_percent dec(5,2) d=0, campus_id fk, payment_method str `// bank_transfer,cash,check,card_payment`, status str d=pending `// received,confirmed,pending`, module_link str*, income_date date*, notes txt*, created_by fk:users

**ledger_accounts**: code str*, name str, type str* `// asset,liability,income,expense`, campus_id fk, is_active bool d=true

**ledger_entries**: reference_no str(unique), entry_date date, type str `// fee,salary,expense,other`, account_id fk:ledger_accounts, debit dec(15,2) d=0, credit dec(15,2) d=0, status str d=pending `// posted,pending,reversed`, previous_balance dec(15,2)*, adjusted_balance dec(15,2)*, campus_id fk, description txt*, student_id fk, invoice_no str*, source_module str*, created_by fk:users

**ledger_entry_audits**: ledger_entry_id fk, action str, description str*, performed_by fk:users, performed_at dt

**reconciliations**: ledger_entry_id fk, bank_statement_ref str*, account_code str*, amount dec(15,2)*, status str d=unmatched `// matched,unmatched,pending`, alert_type str*, campus_id fk, notes txt*

---

## MODULE 9 — Library, Transport & Hostel  (seq block 290000)  → FacilitySeeder

**books**: title str, author str, isbn str(unique), category str, subtitle str*, cover_image_url str*, total_copies int d=1, available_copies int d=0, availability_status str d=available, borrow_count int d=0, campus_id fk

**book_issues**: book_id fk, borrower_type str d=student, student_id fk, issued_by fk:users, issue_date date, due_date date, return_date date*, status str d=issued `// issued,returned,overdue`, fine_amount dec(8,2) d=0, fine_paid bool, renewal_count int d=0

**vehicles**: vehicle_number str(unique), type str, capacity int, occupied_seats int d=0, route_id fk:transport_routes, driver_id fk:staff, status str d=operational, campus_id fk, last_service_km int*

**vehicle_maintenance_logs**: vehicle_id fk, type str, title str, description txt*, reported_by fk:staff, due_in_days int*, status str d=pending, logged_at dt*

**transport_routes**: name str, code str(unique), start_point str*, end_point str*, vehicle_id fk:vehicles, stops_count int d=0, students_count int d=0, duration_minutes int*, monthly_fee dec(8,2)*, status str d=active, campus_id fk

**route_stops**: route_id fk:transport_routes, name str, sequence int, arrival_time time*, stop_duration_minutes int*

**transport_assignments**: student_id fk, route_id fk:transport_routes, pickup_stop_id fk:route_stops, dropoff_stop_id fk:route_stops, monthly_fee dec(8,2)*, status str d=assigned

**hostels**: name str, block str*, type str `// boys,girls,faculty_staff`, warden_id fk:staff, total_rooms int d=0, occupied_rooms int d=0, occupancy_status str d=available, campus_id fk

**hostel_rooms**: hostel_id fk, room_number str, floor str*, type str `// single,double,twin,quad,dormitory`, capacity int, available_beds int d=0, status str d=available, room_rate dec(8,2)*, rate_period str*

**hostel_beds**: room_id fk:hostel_rooms, bed_label str, status str d=vacant `// vacant,occupied`

**hostel_allocations**: student_id fk, hostel_id fk, room_id fk:hostel_rooms, bed_id fk:hostel_beds, check_in_date date*, check_out_date date*, room_rate dec(8,2)*, rate_period str*, status str d=active

**hostel_maintenance_tickets**: ticket_number str(unique), room_id fk:hostel_rooms, hostel_id fk, category str `// maintenance,incident`, issue_type str*, description txt*, priority str*, reported_by fk:users, status str d=pending

---

## MODULE 10 — Settings, Communication & System  (seq block 300000)  → SystemSettingsSeeder

**settings**: group str, key str, value txt*, type str d=string `// string,boolean,integer,float,select,json,file,time,text`  — add `->unique(['group','key'])`

**notices**: title str, category str, type str, description longText, priority str d=normal, audience json*, publish_date date*, require_acknowledgment bool, status str d=draft, created_by fk:users

**notice_acknowledgments**: notice_id fk, user_id fk, acknowledged_at dt*

**notification_templates**: name str, category str, subject str*, body txt, channels json*, status str d=draft

**notification_logs**: template_id fk:notification_templates, type_label str*, channel str, recipients_count int d=0, failed_count int d=0, status str d=pending, sent_at dt*

**reports**: name str, category str, format str*, parameters json*, generated_by fk:users, generated_at dt*

**scheduled_reports**: report_id fk:reports, name str, frequency str, run_at str*, format str, is_active bool d=true, last_run_at dt*

**activity_logs**: audit_ref str*(unique), user_id fk:users, user_name str*, role str*, module str, action str, description txt*, changes json*, ip_address str*, device str*, protocol str*, origin_id str*, mfa_status str*, geo_lat dec(10,7)*, geo_lng dec(10,7)*, status str d=success

**security_events**: user_entity str*, user_id fk:users, action_trigger str, risk_level str, ip_address str*, occurred_at dt

**backups**: name str, type str, size_bytes big*, size_label str*, storage_provider str d=local, checksum str*, created_by fk:users, is_automated bool, status str d=success

**languages**: name str, code str(unique), is_enabled bool d=true, is_default bool, is_rtl bool

**translations**: key str, default_text txt*, value txt*, language_code str, status str d=pending

**currencies**: code str(unique), name str, symbol str, is_base bool

**integrations**: provider str, type str, is_enabled bool, status str d=available, credentials json*

**webhook_events**: event_key str(unique), label str, is_enabled bool

**api_logs**: method str, endpoint str, status_code int*, latency_ms int*, called_at dt

**trusted_devices**: user_id fk, device_name str, device_type str*, ip_address str*, is_current bool, last_used_at dt*, revoked_at dt*

**login_activities**: user_id fk:users, ip_address str*, device str*, status str d=success, logged_in_at dt

**user_documents**: user_id fk, name str, file_path str, file_type str*, uploaded_at dt*

---

## CENTRAL (handled by orchestrator) — users table additions (seq 200000)

Added to `users`: username str*, avatar str*, phone str*, date_of_birth date*, gender str*, country str*, residential_address txt*, employee_id str*, campus_id fk, department_id fk, employee_tier str*, reporting_manager_id fk:users, joining_date date*, status str d=active, two_factor_enabled bool, two_factor_secret txt*, last_login_at dt*, total_logins int d=0, preferred_language str* d=EN, dark_mode bool, email_alerts bool d=true, sms_notifications bool, system_alerts bool d=true, oauth_provider str*, oauth_id str*
