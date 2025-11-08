<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $phone = '+' . fake()->numberBetween(1, 90) . fake()->numberBetween(100000000, 999999999);

        return [
            'name'  => fake()->name(),
            'phone' => $phone,
            'email' => fake()->unique()->safeEmail(),
        ];
    }
}
