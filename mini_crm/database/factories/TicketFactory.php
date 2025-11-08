<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Customer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['new', 'in progress', 'processed'];
        return [
            'customer_id' => Customer::factory(),
            'subject'     => fake()->sentence(rand(1, 5)),
            'text'        => fake()->paragraph(rand(1, 5)),
            'status'      => fake()->randomElement($statuses)
        ];
    }
}
