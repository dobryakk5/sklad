<?php

namespace Database\Factories;

use App\Models\Operator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Operator>
 */
class OperatorFactory extends Factory
{
    protected $model = Operator::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password123',
            'role' => 'operator',
            'token' => null,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role' => 'admin']);
    }
}
