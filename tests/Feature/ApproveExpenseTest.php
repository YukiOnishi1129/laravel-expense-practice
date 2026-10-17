<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\ExpenseApprovalHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApproveExpenseTest extends TestCase
{
    use RefreshDatabase;

    public function test_承認者が承認すると承認済みになり履歴が残る(): void
    {
        $expense = Expense::factory()->create();

        $response = $this->actingAs($expense->approver)->post(route('approvals.approve', $expense));

        $response->assertRedirect(route('expenses.index'));
        $this->assertSame(Expense::STATUS_APPROVED, $expense->fresh()->status);
        $this->assertDatabaseHas('expense_approval_histories', [
            'expense_id' => $expense->id,
            'user_id' => $expense->approver_id,
            'action' => ExpenseApprovalHistory::ACTION_APPROVED,
        ]);
    }

    public function test_承認者でない上長は承認できない(): void
    {
        $expense = Expense::factory()->create();
        $otherManager = User::factory()->manager()->create();

        $response = $this->actingAs($otherManager)->post(route('approvals.approve', $expense));

        $response->assertForbidden();
        $this->assertSame(Expense::STATUS_PENDING, $expense->fresh()->status);
    }
}
