<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'role' => 'order_taker',
            'email' => 'api@test.ru',
        ]);

        $this->token = $this->user->createToken('test')->plainTextToken;
    }

    public function test_login_returns_token(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'api@test.ru',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'user']);
    }

    public function test_login_fails_with_wrong_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'api@test.ru',
            'password' => 'wrong',
        ]);

        $response->assertStatus(422);
    }

    public function test_unauthenticated_cannot_access_events(): void
    {
        $this->getJson('/api/events')->assertStatus(401);
    }

    public function test_can_list_events(): void
    {
        Event::factory()->count(3)->create();

        $this->withToken($this->token)
            ->getJson('/api/events')
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) =>
                $json->has('data', 3)->has('meta')
            );
    }

    public function test_can_create_event(): void
    {
        $response = $this->withToken($this->token)
            ->postJson('/api/events', [
                'client_name' => 'API Client',
                'client_phone' => '+7-999-111-22-33',
                'event_type' => 'banquet',
                'event_date' => '2026-06-15',
                'people_count' => 20,
            ]);

        $response->assertStatus(201)
            ->assertJson(fn(AssertableJson $json) =>
                $json->has('data')->where('data.client_name', 'API Client')
            );
    }

    public function test_can_show_event(): void
    {
        $event = Event::factory()->create();

        $this->withToken($this->token)
            ->getJson('/api/events/' . $event->id)
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) =>
                $json->has('data')->has('finance')
            );
    }

    public function test_can_update_event(): void
    {
        $event = Event::factory()->create();

        $this->withToken($this->token)
            ->putJson('/api/events/' . $event->id, [
                'client_name' => 'Updated API',
                'status' => 'confirmed',
            ])
            ->assertStatus(200);

        $this->assertEquals('Updated API', $event->fresh()->client_name);
    }

    public function test_invalid_status_transition_returns_422(): void
    {
        $event = Event::factory()->create(['status' => 'completed']);

        $this->withToken($this->token)
            ->putJson('/api/events/' . $event->id, [
                'status' => 'confirmed',
            ])
            ->assertStatus(422);
    }

    public function test_can_delete_event(): void
    {
        $event = Event::factory()->create();

        $this->withToken($this->token)
            ->deleteJson('/api/events/' . $event->id)
            ->assertStatus(200);

        $this->assertModelMissing($event);
    }

    public function test_authenticated_user_can_access_self(): void
    {
        $this->withToken($this->token)
            ->getJson('/api/user')
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) =>
                $json->has('user')->where('user.email', 'api@test.ru')
            );
    }

    public function test_cookie_cannot_create_event(): void
    {
        $cook = User::factory()->create(['role' => 'cook']);
        $cookToken = $cook->createToken('test')->plainTextToken;

        $this->withToken($cookToken)
            ->postJson('/api/events', [
                'client_name' => 'Hacker',
                'event_type' => 'banquet',
                'event_date' => '2026-06-15',
                'people_count' => 5,
            ])
            ->assertStatus(403);
    }
}
