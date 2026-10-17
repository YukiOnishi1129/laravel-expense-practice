<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreExpenseTest extends TestCase
{
    use RefreshDatabase;

    public function test_申請を出すと申請中で保存され承認者は自分の上長になる(): void
    {
        $manager = User::factory()->manager()->create();
        $employee = User::factory()->create(['manager_id' => $manager->id]);

        $response = $this->actingAs($employee)->post(route('expenses.store'), [
            'amount' => 1280,
            'used_on' => now()->toDateString(),
            'description' => '交通費',
        ]);

        $response->assertRedirect(route('expenses.index'));
        $this->assertDatabaseHas('expenses', [
            'user_id' => $employee->id,
            'approver_id' => $manager->id,
            'amount' => 1280,
            'status' => Expense::STATUS_PENDING,
        ]);
    }

    public function test_金額が空なら保存されない(): void
    {
        $manager = User::factory()->manager()->create();
        $employee = User::factory()->create(['manager_id' => $manager->id]);

        $response = $this->actingAs($employee)->post(route('expenses.store'), [
            'amount' => '',
            'used_on' => now()->toDateString(),
            'description' => '交通費',
        ]);

        $response->assertSessionHasErrors('amount');
        $this->assertDatabaseCount('expenses', 0);
    }
}
