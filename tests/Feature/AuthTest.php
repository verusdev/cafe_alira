<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'order_taker']);
    }

    public function test_guest_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_login_page_loads(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_user_can_login(): void
    {
        $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ])->assertRedirect('/dashboard');
    }

    public function test_user_cannot_login_with_wrong_password(): void
    {
        $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'wrong',
        ])->assertSessionHasErrors('email');
    }

    public function test_user_can_logout(): void
    {
        $this->actingAs($this->user)
            ->post('/logout')
            ->assertStatus(302);
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $this->actingAs($this->user)
            ->get('/dashboard')
            ->assertStatus(200);
    }
}
