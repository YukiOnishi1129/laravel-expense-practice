<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;

class ExpensePolicy
{
    /**
     * 申請の詳細を見られるのは、申請した本人と承認する上長だけ
     */
    public function view(User $user, Expense $expense): bool
    {
        return $expense->user_id === $user->id || $expense->approver_id === $user->id;
    }

    /**
     * 直して出し直せるのは、差し戻された申請を出した本人だけ
     */
    public function update(User $user, Expense $expense): bool
    {
        return $expense->user_id === $user->id && $expense->isRejected();
    }

    /**
     * 承認できるのは、その申請の承認者だけ。申請中の申請だけ
     */
    public function approve(User $user, Expense $expense): bool
    {
        return $expense->approver_id === $user->id && $expense->isPending();
    }
}
