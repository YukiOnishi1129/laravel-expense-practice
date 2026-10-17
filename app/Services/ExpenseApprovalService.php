<?php

namespace App\Services;

use App\Mail\ExpenseRejectedMail;
use App\Models\Expense;
use App\Models\ExpenseApprovalHistory;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ExpenseApprovalService
{
    public function approve(Expense $expense, User $approver): void
    {
        $expense->update(['status' => Expense::STATUS_APPROVED]);

        $expense->histories()->create([
            'user_id' => $approver->id,
            'action' => ExpenseApprovalHistory::ACTION_APPROVED,
        ]);
    }

    public function reject(Expense $expense, User $approver): void
    {
        $expense->update(['status' => Expense::STATUS_REJECTED]);

        $expense->histories()->create([
            'user_id' => $approver->id,
            'action' => ExpenseApprovalHistory::ACTION_REJECTED,
        ]);

        Mail::to($expense->user)->send(new ExpenseRejectedMail($expense));
    }
}
