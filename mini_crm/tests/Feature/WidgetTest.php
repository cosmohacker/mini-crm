<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Customer;

class WidgetTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpRoles();
    }

    /** @test */
    public function it_displays_widget_page()
    {
        $response = $this->get('/widget');

        $response->assertStatus(200)->assertViewIs('widgets.index');
    }

    /** @test */
    public function it_can_submit_form()
    {
        $customer = Customer::factory()->create();

        $response = $this->postJson('/api/tickets', [
            'customer_id' => $customer->id,
            'subject'     => 'Widget Subject',
            'text'        => 'Widget description',
        ]);

        $response->assertStatus(201);
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

        $response->assertStatus(422);
    }
}
