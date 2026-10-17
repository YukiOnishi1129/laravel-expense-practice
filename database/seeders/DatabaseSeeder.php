<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\ExpenseApprovalHistory;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * 何回動かしても、同じデータが2つできないようにしてある
     */
    public function run(): void
    {
        $sato = User::firstOrCreate(
            ['email' => 'sato@example.com'],
            ['name' => '佐藤（上長）', 'password' => 'password', 'role' => User::ROLE_MANAGER],
        );

        User::firstOrCreate(
            ['email' => 'takahashi@example.com'],
            ['name' => '高橋（別の部署の上長）', 'password' => 'password', 'role' => User::ROLE_MANAGER],
        );

        $tanaka = User::firstOrCreate(
            ['email' => 'tanaka@example.com'],
            ['name' => '田中', 'password' => 'password', 'role' => User::ROLE_EMPLOYEE, 'manager_id' => $sato->id],
        );

        $suzuki = User::firstOrCreate(
            ['email' => 'suzuki@example.com'],
            ['name' => '鈴木', 'password' => 'password', 'role' => User::ROLE_EMPLOYEE, 'manager_id' => $sato->id],
        );

        if (Expense::exists()) {
            return;
        }

        Expense::create([
            'user_id' => $tanaka->id,
            'approver_id' => $sato->id,
            'amount' => 1280,
            'used_on' => now()->subDays(2)->toDateString(),
            'description' => '交通費（客先への電車代）',
            'status' => Expense::STATUS_PENDING,
        ]);

        $approved = Expense::create([
            'user_id' => $tanaka->id,
            'approver_id' => $sato->id,
            'amount' => 3980,
            'used_on' => now()->subDays(10)->toDateString(),
            'description' => '備品代（キーボード）',
            'status' => Expense::STATUS_APPROVED,
        ]);
        $approved->histories()->create(['user_id' => $sato->id, 'action' => ExpenseApprovalHistory::ACTION_APPROVED]);

        $rejected = Expense::create([
            'user_id' => $tanaka->id,
            'approver_id' => $sato->id,
            'amount' => 15400,
            'used_on' => now()->subDays(5)->toDateString(),
            'description' => '交通費（出張の新幹線代）',
            'status' => Expense::STATUS_REJECTED,
        ]);
        $rejected->histories()->create(['user_id' => $sato->id, 'action' => ExpenseApprovalHistory::ACTION_REJECTED]);

        Expense::create([
            'user_id' => $suzuki->id,
            'approver_id' => $sato->id,
            'amount' => 2500,
            'used_on' => now()->subDay()->toDateString(),
            'description' => '書籍代（設計の本）',
            'status' => Expense::STATUS_PENDING,
        ]);
    }
}
