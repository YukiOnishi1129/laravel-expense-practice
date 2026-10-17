<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\ExpenseApprovalHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExpenseApprovalHistory>
 */
class ExpenseApprovalHistoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'expense_id' => Expense::factory(),
            'user_id' => User::factory()->manager(),
            'action' => ExpenseApprovalHistory::ACTION_APPROVED,
        ];
    }
}
