<?php

namespace App\Http\Controllers;

use App\Http\Requests\RejectExpenseRequest;
use App\Models\Expense;
use App\Services\ExpenseApprovalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseApprovalController extends Controller
{
    public function __construct(private ExpenseApprovalService $service)
    {
    }

    public function show(Expense $expense): View
    {
        $this->authorize('approve', $expense);

        $expense->load('user');

        return view('approvals.show', compact('expense'));
    }

    public function approve(Request $request, Expense $expense): RedirectResponse
    {
        $this->authorize('approve', $expense);

        $this->service->approve($expense, $request->user());

        return redirect()->route('expenses.index')->with('status', '承認しました');
    }

    public function reject(RejectExpenseRequest $request, Expense $expense): RedirectResponse
    {
        $this->service->reject($expense, $request->user(), $request->validated('comment'));

        return redirect()->route('expenses.index')->with('status', '差し戻しました');
    }
}
