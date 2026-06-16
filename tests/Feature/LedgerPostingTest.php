<?php

namespace Tests\Feature;

use App\Models\ExpenseCategory;
use App\Models\IncomeCategory;
use App\Models\LedgerEntry;
use App\Services\Finance\ExpenseService;
use App\Services\Finance\IncomeService;
use App\Services\Finance\SalaryPaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LedgerPostingTest extends TestCase
{
    use RefreshDatabase;

    public function test_expense_posts_a_debit_to_the_ledger(): void
    {
        $category = ExpenseCategory::create(['name' => 'Utilities', 'is_active' => true]);

        app(ExpenseService::class)->create([
            'reference_no' => 'EX-100', 'title' => 'Electricity', 'category_id' => $category->id,
            'amount' => 500, 'expense_date' => '2026-03-01', 'status' => 'paid',
        ]);

        $entry = LedgerEntry::where('source_module', 'expenses')->first();
        $this->assertNotNull($entry);
        $this->assertEquals(500, (float) $entry->debit);
        $this->assertEquals(0, (float) $entry->credit);
        $this->assertSame('expense', $entry->type);
        $this->assertDatabaseHas('ledger_accounts', ['name' => 'Expense — Utilities']);
    }

    public function test_income_posts_a_credit_to_the_ledger(): void
    {
        $category = IncomeCategory::create(['name' => 'Donations', 'is_active' => true]);

        app(IncomeService::class)->create([
            'reference_no' => 'IN-100', 'title' => 'Alumni Gift', 'category_id' => $category->id,
            'amount' => 1200, 'income_date' => '2026-03-01', 'payment_method' => 'bank_transfer', 'status' => 'received',
        ]);

        $entry = LedgerEntry::where('source_module', 'incomes')->first();
        $this->assertNotNull($entry);
        $this->assertEquals(1200, (float) $entry->credit);
        $this->assertEquals(0, (float) $entry->debit);
    }

    public function test_salary_payment_posts_a_debit_to_staff_payroll(): void
    {
        app(SalaryPaymentService::class)->create([
            'employee_type' => 'teacher', 'employee_id' => 1, 'payroll_month' => 'June 2026',
            'basic' => 4000, 'net_salary' => 4500, 'status' => 'paid', 'transaction_ref' => 'SAL-1',
        ]);

        $entry = LedgerEntry::where('source_module', 'salary-payments')->first();
        $this->assertNotNull($entry);
        $this->assertEquals(4500, (float) $entry->debit);
        $this->assertSame('salary', $entry->type);
        $this->assertDatabaseHas('ledger_accounts', ['name' => 'Staff Payroll']);
    }

    public function test_running_balance_accumulates_per_account(): void
    {
        $category = ExpenseCategory::create(['name' => 'Maintenance', 'is_active' => true]);
        $service = app(ExpenseService::class);

        $service->create(['reference_no' => 'EX-1', 'title' => 'A', 'category_id' => $category->id, 'amount' => 500, 'expense_date' => '2026-03-01']);
        $service->create(['reference_no' => 'EX-2', 'title' => 'B', 'category_id' => $category->id, 'amount' => 300, 'expense_date' => '2026-03-02']);

        $entries = LedgerEntry::where('source_module', 'expenses')->orderBy('id')->get();
        $this->assertCount(2, $entries);
        $this->assertEquals(500, (float) $entries[0]->adjusted_balance);
        $this->assertEquals(500, (float) $entries[1]->previous_balance);
        $this->assertEquals(800, (float) $entries[1]->adjusted_balance);
    }
}
