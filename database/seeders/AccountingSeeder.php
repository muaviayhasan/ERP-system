<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\LedgerAccount;
use App\Models\LedgerEntry;
use App\Models\LedgerEntryAudit;
use App\Models\Reconciliation;
use Illuminate\Database\Seeder;

class AccountingSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Expense categories ----
        $expenseCategories = [
            ['name' => 'Salary', 'slug' => 'salary', 'budget_amount' => 500000.00, 'description' => 'Staff and faculty payroll expenses.'],
            ['name' => 'Utilities', 'slug' => 'utilities', 'budget_amount' => 60000.00, 'description' => 'Electricity, water, gas and internet bills.'],
            ['name' => 'Maintenance', 'slug' => 'maintenance', 'budget_amount' => 80000.00, 'description' => 'Building, equipment and grounds upkeep.'],
            ['name' => 'Transport', 'slug' => 'transport', 'budget_amount' => 45000.00, 'description' => 'Fuel and fleet operating costs.'],
            ['name' => 'Academic', 'slug' => 'academic', 'budget_amount' => 70000.00, 'description' => 'Lab supplies, books and academic materials.'],
        ];
        foreach ($expenseCategories as $cat) {
            ExpenseCategory::create($cat + ['is_active' => true]);
        }

        // ---- Expenses ----
        $expenses = [
            [
                'reference_no' => 'EXP-2026-0001', 'title' => 'Monthly Faculty Payroll - June',
                'category_id' => 1, 'amount' => 185000.00, 'tax_percent' => 0, 'currency' => 'USD',
                'campus_id' => 1, 'status' => 'paid', 'approver_id' => 1, 'payee' => 'Payroll Account',
                'expense_date' => '2026-06-01', 'notes' => 'Salaries disbursed via bank transfer.', 'created_by' => 1,
            ],
            [
                'reference_no' => 'EXP-2026-0002', 'title' => 'Electricity Bill - May',
                'category_id' => 2, 'amount' => 12400.50, 'tax_percent' => 5.00, 'currency' => 'USD',
                'campus_id' => 2, 'status' => 'approved', 'approver_id' => 1, 'payee' => 'City Power Co.',
                'expense_date' => '2026-05-28', 'notes' => 'Includes hostel block usage.', 'created_by' => 2,
            ],
            [
                'reference_no' => 'EXP-2026-0003', 'title' => 'HVAC Repair - Science Block',
                'category_id' => 3, 'amount' => 8750.00, 'tax_percent' => 0, 'currency' => 'USD',
                'campus_id' => 1, 'status' => 'pending', 'approver_id' => null, 'payee' => 'CoolAir Services',
                'expense_date' => '2026-06-10', 'notes' => 'Awaiting finance approval.', 'created_by' => 2,
            ],
            [
                'reference_no' => 'EXP-2026-0004', 'title' => 'Bus Fleet Fuel - Weekly',
                'category_id' => 4, 'amount' => 5320.75, 'tax_percent' => 0, 'currency' => 'USD',
                'campus_id' => 3, 'status' => 'paid', 'approver_id' => 1, 'payee' => 'GreenFuel Station',
                'expense_date' => '2026-06-12', 'notes' => 'Diesel refill for 6 buses.', 'created_by' => 1,
            ],
        ];
        foreach ($expenses as $exp) {
            Expense::create($exp);
        }

        // ---- Income categories ----
        $incomeCategories = [
            ['name' => 'Tuition', 'slug' => 'tuition', 'module_link' => 'fees', 'description' => 'Student tuition fee collections.'],
            ['name' => 'Donation', 'slug' => 'donation', 'module_link' => null, 'description' => 'Alumni and donor contributions.'],
            ['name' => 'Transport', 'slug' => 'transport', 'module_link' => 'transport', 'description' => 'Transport service charges.'],
            ['name' => 'Library Fines', 'slug' => 'library-fines', 'module_link' => 'library', 'description' => 'Overdue book fine collections.'],
            ['name' => 'Hostel', 'slug' => 'hostel', 'module_link' => 'hostel', 'description' => 'Hostel room and boarding charges.'],
        ];
        foreach ($incomeCategories as $cat) {
            IncomeCategory::create($cat + ['is_active' => true]);
        }

        // ---- Incomes ----
        $incomes = [
            [
                'reference_no' => 'INC-2026-0001', 'title' => 'Tuition Fee Collection - Semester', 'subtitle' => 'Spring intake batch',
                'category_id' => 1, 'amount' => 245000.00, 'tax_percent' => 0, 'campus_id' => 1,
                'payment_method' => 'bank_transfer', 'status' => 'confirmed', 'module_link' => 'fees',
                'income_date' => '2026-06-02', 'notes' => 'Consolidated tuition deposits.', 'created_by' => 1,
            ],
            [
                'reference_no' => 'INC-2026-0002', 'title' => 'Alumni Endowment Donation', 'subtitle' => 'Class of 2010',
                'category_id' => 2, 'amount' => 50000.00, 'tax_percent' => 0, 'campus_id' => 1,
                'payment_method' => 'check', 'status' => 'received', 'module_link' => null,
                'income_date' => '2026-06-05', 'notes' => 'Earmarked for scholarship fund.', 'created_by' => 2,
            ],
            [
                'reference_no' => 'INC-2026-0003', 'title' => 'Transport Monthly Charges', 'subtitle' => 'Route A & B',
                'category_id' => 3, 'amount' => 18600.00, 'tax_percent' => 0, 'campus_id' => 3,
                'payment_method' => 'cash', 'status' => 'pending', 'module_link' => 'transport',
                'income_date' => '2026-06-08', 'notes' => 'Partial collection pending.', 'created_by' => 2,
            ],
            [
                'reference_no' => 'INC-2026-0004', 'title' => 'Library Overdue Fines', 'subtitle' => 'May cycle',
                'category_id' => 4, 'amount' => 1240.00, 'tax_percent' => 0, 'campus_id' => 2,
                'payment_method' => 'card_payment', 'status' => 'confirmed', 'module_link' => 'library',
                'income_date' => '2026-05-31', 'notes' => 'Collected at circulation desk.', 'created_by' => 1,
            ],
        ];
        foreach ($incomes as $inc) {
            Income::create($inc);
        }

        // ---- Ledger accounts (5 heads) ----
        $accounts = [
            ['code' => '1000', 'name' => 'Cash & Bank', 'type' => 'asset', 'campus_id' => 1],
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'liability', 'campus_id' => 1],
            ['code' => '3000', 'name' => 'Tuition Revenue', 'type' => 'income', 'campus_id' => 1],
            ['code' => '4000', 'name' => 'Payroll Expense', 'type' => 'expense', 'campus_id' => 2],
            ['code' => '5000', 'name' => 'Utilities Expense', 'type' => 'expense', 'campus_id' => 3],
        ];
        foreach ($accounts as $acc) {
            LedgerAccount::create($acc + ['is_active' => true]);
        }

        // ---- Ledger entries (5) ----
        $entries = [
            [
                'reference_no' => 'LE-2026-0001', 'entry_date' => '2026-06-02', 'type' => 'fee', 'account_id' => 3,
                'debit' => 0, 'credit' => 245000.00, 'status' => 'posted', 'previous_balance' => 0.00,
                'adjusted_balance' => 245000.00, 'campus_id' => 1, 'description' => 'Tuition fee revenue posting.',
                'student_id' => 1, 'invoice_no' => 'INV-1001', 'source_module' => 'fees', 'created_by' => 1,
            ],
            [
                'reference_no' => 'LE-2026-0002', 'entry_date' => '2026-06-01', 'type' => 'salary', 'account_id' => 4,
                'debit' => 185000.00, 'credit' => 0, 'status' => 'posted', 'previous_balance' => 0.00,
                'adjusted_balance' => -185000.00, 'campus_id' => 2, 'description' => 'June payroll expense.',
                'student_id' => null, 'invoice_no' => null, 'source_module' => 'hr', 'created_by' => 1,
            ],
            [
                'reference_no' => 'LE-2026-0003', 'entry_date' => '2026-05-28', 'type' => 'expense', 'account_id' => 5,
                'debit' => 12400.50, 'credit' => 0, 'status' => 'pending', 'previous_balance' => -185000.00,
                'adjusted_balance' => -197400.50, 'campus_id' => 2, 'description' => 'Electricity bill accrual.',
                'student_id' => null, 'invoice_no' => 'UTIL-552', 'source_module' => 'accounting', 'created_by' => 2,
            ],
            [
                'reference_no' => 'LE-2026-0004', 'entry_date' => '2026-06-05', 'type' => 'other', 'account_id' => 1,
                'debit' => 50000.00, 'credit' => 0, 'status' => 'posted', 'previous_balance' => 245000.00,
                'adjusted_balance' => 295000.00, 'campus_id' => 1, 'description' => 'Donation received into cash account.',
                'student_id' => null, 'invoice_no' => null, 'source_module' => 'accounting', 'created_by' => 2,
            ],
            [
                'reference_no' => 'LE-2026-0005', 'entry_date' => '2026-06-10', 'type' => 'fee', 'account_id' => 3,
                'debit' => 0, 'credit' => 18600.00, 'status' => 'reversed', 'previous_balance' => 295000.00,
                'adjusted_balance' => 295000.00, 'campus_id' => 3, 'description' => 'Transport fee entry reversed (duplicate).',
                'student_id' => 5, 'invoice_no' => 'INV-1042', 'source_module' => 'transport', 'created_by' => 1,
            ],
        ];
        foreach ($entries as $entry) {
            LedgerEntry::create($entry);
        }

        // ---- Ledger entry audits ----
        $audits = [
            ['ledger_entry_id' => 1, 'action' => 'created', 'description' => 'Entry created from fee module.', 'performed_by' => 1, 'performed_at' => '2026-06-02 09:15:00'],
            ['ledger_entry_id' => 1, 'action' => 'posted', 'description' => 'Posted to general ledger.', 'performed_by' => 1, 'performed_at' => '2026-06-02 09:20:00'],
            ['ledger_entry_id' => 2, 'action' => 'posted', 'description' => 'Payroll batch posted.', 'performed_by' => 1, 'performed_at' => '2026-06-01 17:00:00'],
            ['ledger_entry_id' => 3, 'action' => 'created', 'description' => 'Utility accrual pending review.', 'performed_by' => 2, 'performed_at' => '2026-05-28 11:30:00'],
            ['ledger_entry_id' => 5, 'action' => 'reversed', 'description' => 'Reversed due to duplicate transport charge.', 'performed_by' => 1, 'performed_at' => '2026-06-11 10:05:00'],
        ];
        foreach ($audits as $audit) {
            LedgerEntryAudit::create($audit);
        }

        // ---- Reconciliations ----
        $reconciliations = [
            [
                'ledger_entry_id' => 1, 'bank_statement_ref' => 'BSTMT-0601-001', 'account_code' => '1000',
                'amount' => 245000.00, 'status' => 'matched', 'alert_type' => null, 'campus_id' => 1,
                'notes' => 'Auto-matched against bank deposit.',
            ],
            [
                'ledger_entry_id' => 3, 'bank_statement_ref' => null, 'account_code' => '5000',
                'amount' => 12400.50, 'status' => 'unmatched', 'alert_type' => 'missing_statement', 'campus_id' => 2,
                'notes' => 'No matching bank line found yet.',
            ],
        ];
        foreach ($reconciliations as $rec) {
            Reconciliation::create($rec);
        }
    }
}
