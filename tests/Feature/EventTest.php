<?php

namespace Tests\Feature;

use App\Models\Dish;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    private User $orderTaker;
    private User $cook;
    private User $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderTaker = User::factory()->create(['role' => 'order_taker']);
        $this->cook = User::factory()->create(['role' => 'cook']);
        $this->manager = User::factory()->create(['role' => 'manager']);
    }

    public function test_list_events_requires_auth(): void
    {
        $this->get('/events')->assertRedirect('/login');
    }

    public function test_order_taker_can_view_events_list(): void
    {
        Event::factory()->create(['client_name' => 'Test Client']);

        $this->actingAs($this->orderTaker)
            ->get('/events')
            ->assertStatus(200)
            ->assertSee('Test Client');
    }

    public function test_create_event_form_accessible_by_order_taker(): void
    {
        $this->actingAs($this->orderTaker)
            ->get('/events/create')
            ->assertStatus(200);
    }

    public function test_create_event_form_forbidden_for_cook(): void
    {
        $this->actingAs($this->cook)
            ->get('/events/create')
            ->assertStatus(403);
    }

    public function test_order_taker_can_store_event(): void
    {
        $this->actingAs($this->orderTaker)
            ->post('/events', [
                'client_name' => 'New Client',
                'client_phone' => '+7-999-111-22-33',
                'event_type' => 'banquet',
                'event_date' => '2026-06-15',
                'people_count' => 10,
                'status' => 'new',
            ])
            ->assertRedirect('/events');

        $this->assertDatabaseHas('events', ['client_name' => 'New Client']);
    }

    public function test_store_event_validates_required_fields(): void
    {
        $this->actingAs($this->orderTaker)
            ->post('/events', [])
            ->assertSessionHasErrors(['client_name', 'event_type', 'event_date', 'people_count']);
    }

    public function test_cook_cannot_store_event(): void
    {
        $this->actingAs($this->cook)
            ->post('/events', [
                'client_name' => 'Hacker',
                'event_type' => 'banquet',
                'event_date' => '2026-06-15',
                'people_count' => 5,
            ])
            ->assertStatus(403);
    }

    public function test_user_can_view_event(): void
    {
        $event = Event::factory()->create(['client_name' => 'Viewable']);

        $this->actingAs($this->orderTaker)
            ->get('/events/' . $event->id)
            ->assertStatus(200)
            ->assertSee('Viewable');
    }

    public function test_order_taker_can_edit_own_event(): void
    {
        $event = Event::factory()->create();

        $this->actingAs($this->orderTaker)
            ->get('/events/' . $event->id . '/edit')
            ->assertStatus(200);
    }

    public function test_order_taker_can_update_event(): void
    {
        $event = Event::factory()->create(['client_name' => 'Old Name']);

        $this->actingAs($this->orderTaker)
            ->put('/events/' . $event->id, [
                'client_name' => 'Updated Name',
                'client_phone' => '+7-999-111-22-33',
                'event_type' => 'banquet',
                'event_date' => '2026-06-15',
                'people_count' => 15,
                'status' => 'new',
            ])
            ->assertRedirect('/events');

        $this->assertDatabaseHas('events', ['client_name' => 'Updated Name']);
    }

    public function test_status_transition_valid(): void
    {
        $event = Event::factory()->create(['status' => 'new']);

        $this->actingAs($this->orderTaker)
            ->put('/events/' . $event->id, [
                'client_name' => $event->client_name,
                'client_phone' => $event->client_phone,
                'event_type' => $event->event_type,
                'event_date' => $event->event_date->format('Y-m-d'),
                'people_count' => $event->people_count,
                'status' => 'confirmed',
            ]);

        $this->assertEquals('confirmed', $event->fresh()->status);
    }

    public function test_status_transition_invalid_returns_403(): void
    {
        $event = Event::factory()->create(['status' => 'completed']);

        $this->actingAs($this->orderTaker)
            ->put('/events/' . $event->id, [
                'client_name' => $event->client_name,
                'client_phone' => $event->client_phone,
                'event_type' => $event->event_type,
                'event_date' => $event->event_date->format('Y-m-d'),
                'people_count' => $event->people_count,
                'status' => 'confirmed',
            ])
            ->assertStatus(403);
    }

    public function test_order_taker_can_delete_event(): void
    {
        $event = Event::factory()->create();

        $this->actingAs($this->orderTaker)
            ->delete('/events/' . $event->id)
            ->assertRedirect('/events');

        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    public function test_cook_cannot_delete_event(): void
    {
        $event = Event::factory()->create();

        $this->actingAs($this->cook)
            ->delete('/events/' . $event->id)
            ->assertStatus(403);
    }

    public function test_manager_has_access_to_finance(): void
    {
        $event = Event::factory()->create();

        $this->actingAs($this->manager)
            ->get('/events/' . $event->id)
            ->assertSee('Финансы');
    }

    public function test_order_taker_cannot_see_finance(): void
    {
        $event = Event::factory()->create();

        $this->actingAs($this->orderTaker)
            ->get('/events/' . $event->id)
            ->assertDontSee('Финансы');
    }

    public function test_calendar_page_loads(): void
    {
        $this->actingAs($this->orderTaker)
            ->get('/calendar')
            ->assertStatus(200);
    }

    public function test_events_index_can_be_filtered_by_status(): void
    {
        Event::factory()->create(['status' => 'confirmed', 'client_name' => 'ConfirmedClient']);
        Event::factory()->create(['status' => 'new', 'client_name' => 'NewClient']);

        $this->actingAs($this->orderTaker)
            ->get('/events?status=confirmed')
            ->assertSee('ConfirmedClient')
            ->assertDontSee('NewClient');
    }

    public function test_events_index_can_be_filtered_by_search(): void
    {
        Event::factory()->create(['client_name' => 'Иван Петров', 'client_phone' => '+7-111']);
        Event::factory()->create(['client_name' => 'Другой']);

        $this->actingAs($this->orderTaker)
            ->get('/events?search=Иван')
            ->assertSee('Иван Петров')
            ->assertDontSee('Другой');
    }
}
