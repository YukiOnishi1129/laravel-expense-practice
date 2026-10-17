<?php

namespace Tests\Unit;

use App\Models\Expense;
use App\Models\User;
use App\Policies\ExpensePolicy;
use Tests\TestCase;

class ExpensePolicyTest extends TestCase
{
    private function expense(string $status): Expense
    {
        return Expense::factory()->make([
            'user_id' => 1,
            'approver_id' => 2,
            'status' => $status,
        ]);
    }

    private function user(int $id): User
    {
        return User::factory()->make(['id' => $id]);
    }

    public function test_承認者は申請中の申請を承認できる(): void
    {
        $this->assertTrue((new ExpensePolicy)->approve($this->user(2), $this->expense(Expense::STATUS_PENDING)));
    }

    public function test_承認者でない人は承認できない(): void
    {
        $this->assertFalse((new ExpensePolicy)->approve($this->user(3), $this->expense(Expense::STATUS_PENDING)));
    }

    public function test_承認済みの申請はもう一度承認できない(): void
    {
        $this->assertFalse((new ExpensePolicy)->approve($this->user(2), $this->expense(Expense::STATUS_APPROVED)));
    }

    public function test_差し戻された申請だけ本人が出し直せる(): void
    {
        $policy = new ExpensePolicy;

        $this->assertTrue($policy->update($this->user(1), $this->expense(Expense::STATUS_REJECTED)));
        $this->assertFalse($policy->update($this->user(1), $this->expense(Expense::STATUS_PENDING)));
        $this->assertFalse($policy->update($this->user(3), $this->expense(Expense::STATUS_REJECTED)));
    }
}
