<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpRoles();
    }

    /** @test */
    public function user_can_login()
    {
        $user = $this->createUserWithRole('manager');

        $response = $this->post('/login', [
            'email'     => $user->email,
            'password'  => 'password',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function admin_redirects_to_users_page_after_login()
    {
        $admin = $this->createUserWithRole('admin');

        $response = $this->post('/login', [
            'email'     => $admin->email,
            'password'  => 'password',
        ]);

        $response->assertRedirect(route('users.index'));
    }

    /** @test */
    public function manager_redirects_to_tickets_page_after_login()
    {
        $manager = $this->createUserWithRole('manager');

        $response = $this->post('/login', [
            'email'     => $manager->email,
            'password'  => 'password',
        ]);

        $response->assertRedirect(route('tickets.index'));
    }

    /** @test */
    public function unauthenticated_user_cannot_access_protected_routes()
    {
        $response = $this->get('/tickets');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function manager_cannot_access_users_page()
    {
        $manager = $this->createUserWithRole('manager');

        $response = $this->actingAs($manager)->get('/users');

        $response->assertRedirect(route('tickets.index'));
    }

    /** @test */
    public function admin_can_access_users_page()
    {
        $admin = $this->createUserWithRole('admin');

        $response = $this->actingAs($admin)->get('/users');

        $response->assertStatus(200);
    }
}
