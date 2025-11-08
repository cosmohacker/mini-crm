<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\Ticket;
use Carbon\Carbon;

class TicketApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpRoles();
    }

    /** @test */
    public function it_can_create_a_ticket()
    {
        $customer = Customer::factory()->create();

        $response = $this->postJson('/api/tickets', [
            'customer_id' => $customer->id,
            'subject'     => 'Test Subject',
            'text'        => 'Test description',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tickets', [
            'customer_id' => $customer->id,
            'subject'     => 'Test Subject',
        ]);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $response = $this->postJson('/api/tickets', []);

        $response->assertStatus(422)->assertJsonValidationErrors(['customer_id', 'subject', 'text']);
    }

    /** @test */
    public function it_enforces_rate_limit()
    {
        $customer = Customer::factory()->create();

        $this->postJson('/api/tickets', [
            'customer_id' => $customer->id,
            'subject'     => 'First',
            'text'        => 'First description',
        ])->assertStatus(201);

        $response = $this->postJson('/api/tickets', [
            'customer_id' => $customer->id,
            'subject'     => 'Second',
            'text'        => 'Second description',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['customer_id']);
    }

    /** @test */
    public function it_returns_statistics()
    {
        $customer = Customer::factory()->create();

        Ticket::factory()->create([
            'customer_id' => $customer->id,
            'status'      => 'new',
            'created_at'  => Carbon::today(),
        ]);

        $response = $this->getJson('/api/tickets/statistics');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'day'   => ['total', 'new', 'in_progress', 'processed'],
                    'week'  => ['total', 'new', 'in_progress', 'processed'],
                    'month' => ['total', 'new', 'in_progress', 'processed'],
                ],
            ]);
    }
}
