<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\Ticket;

class TicketManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpRoles();
    }

    /** @test */
    public function manager_can_view_tickets()
    {
        $manager = $this->createUserWithRole('manager');
        $customer = Customer::factory()->create();
        Ticket::factory()->create(['customer_id' => $customer->id]);

        $response = $this->actingAs($manager)->get('/tickets');

        $response->assertStatus(200);
    }

    /** @test */
    public function manager_can_update_ticket_status()
    {
        $manager = $this->createUserWithRole('manager');
        $customer = Customer::factory()->create();
        $ticket = Ticket::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'new',
        ]);

        $response = $this->actingAs($manager)
            ->patch("/tickets/{$ticket->id}/status", [
                'status' => 'in progress',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'in progress',
        ]);
    }

    /** @test */
    public function admin_cannot_update_ticket_status()
    {
        $admin = $this->createUserWithRole('admin');
        $customer = Customer::factory()->create();
        $ticket = Ticket::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'new',
        ]);

        $response = $this->actingAs($admin)
            ->patch("/tickets/{$ticket->id}/status", [
                'status' => 'in progress',
            ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'new',
        ]);
    }
}
