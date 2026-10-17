<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Models\Expense;
use App\Models\ExpenseApprovalHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $expenses = $user->expenses()
            ->withMax('histories', 'created_at')
            ->latest('used_on')
            ->latest('id')
            ->get();

        $waitingApprovals = $user->isManager()
            ? Expense::with('user')
                ->where('approver_id', $user->id)
                ->where('status', Expense::STATUS_PENDING)
                ->oldest()
                ->get()
            : collect();

        return view('expenses.index', compact('expenses', 'waitingApprovals'));
    }

    public function create(): View
    {
        return view('expenses.create');
    }

    public function store(StoreExpenseRequest $request): RedirectResponse
    {
        $user = $request->user();

        $expense = new Expense($request->safe()->only(['amount', 'used_on', 'description']));
        $expense->user_id = $user->id;
        $expense->approver_id = $user->manager_id;
        $expense->status = Expense::STATUS_PENDING;

        if ($request->hasFile('receipt')) {
            $expense->receipt_path = $request->file('receipt')->store('receipts', 'public');
        }

        $expense->save();

        return redirect()->route('expenses.index')->with('status', '申請を出しました');
    }

    public function show(Expense $expense): View
    {
        $this->authorize('view', $expense);

        $expense->load(['user', 'approver']);

        // 差し戻されている時だけ、最新の差し戻しの理由を出す
        $latestRejection = $expense->isRejected()
            ? $expense->histories()
                ->where('action', ExpenseApprovalHistory::ACTION_REJECTED)
                ->latest('id')
                ->first()
            : null;

        return view('expenses.show', compact('expense', 'latestRejection'));
    }

    public function edit(Expense $expense): View
    {
        $this->authorize('update', $expense);

        return view('expenses.edit', compact('expense'));
    }

    public function update(StoreExpenseRequest $request, Expense $expense): RedirectResponse
    {
        $this->authorize('update', $expense);

        $expense->fill($request->safe()->only(['amount', 'used_on', 'description']));
        $expense->status = Expense::STATUS_PENDING;

        if ($request->hasFile('receipt')) {
            $expense->receipt_path = $request->file('receipt')->store('receipts', 'public');
        }

        $expense->save();

        return redirect()->route('expenses.index')->with('status', '申請を出し直しました');
    }
}
