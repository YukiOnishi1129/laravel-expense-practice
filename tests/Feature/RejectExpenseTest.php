<?php

namespace Tests\Feature;

use App\Mail\ExpenseRejectedMail;
use App\Models\Expense;
use App\Models\ExpenseApprovalHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RejectExpenseTest extends TestCase
{
    use RefreshDatabase;

    public function test_差し戻すと差し戻しになり履歴が残って申請者にメールが届く(): void
    {
        Mail::fake();
        $expense = Expense::factory()->create();

        $response = $this->actingAs($expense->approver)->post(route('approvals.reject', $expense));

        $response->assertRedirect(route('expenses.index'));
        $this->assertSame(Expense::STATUS_REJECTED, $expense->fresh()->status);
        $this->assertDatabaseHas('expense_approval_histories', [
            'expense_id' => $expense->id,
            'user_id' => $expense->approver_id,
            'action' => ExpenseApprovalHistory::ACTION_REJECTED,
        ]);
        Mail::assertSent(ExpenseRejectedMail::class, fn ($mail) => $mail->hasTo($expense->user->email));
    }
}
