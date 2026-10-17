<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'approver_id' => User::factory()->manager(),
            'amount' => fake()->numberBetween(100, 50000),
            'used_on' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'description' => fake()->randomElement(['交通費', '備品代', '書籍代', '会議費']),
            'receipt_path' => null,
            'status' => Expense::STATUS_PENDING,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => ['status' => Expense::STATUS_APPROVED]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => ['status' => Expense::STATUS_REJECTED]);
    }
}
