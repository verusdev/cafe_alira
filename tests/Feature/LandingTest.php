<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_loads(): void
    {
        $this->get('/')->assertStatus(200)->assertSee('Ваше идеальное');
    }

    public function test_landing_form_creates_event(): void
    {
        $this->post('/', [
            'client_name' => 'Landed Client',
            'client_phone' => '+7-999-555-66-77',
            'event_type' => 'wedding',
            'event_date' => '2026-07-01',
            'people_count' => 30,
        ])->assertRedirect('/');

        $this->assertDatabaseHas('events', [
            'client_name' => 'Landed Client',
            'status' => 'new',
        ]);
    }

    public function test_landing_form_validates_required(): void
    {
        $this->post('/', [])->assertSessionHasErrors([
            'client_name', 'event_type', 'event_date', 'people_count',
        ]);
    }

    public function test_landing_form_creates_notifications(): void
    {
        User::factory()->create(['role' => 'order_taker', 'email' => 'order@test.ru']);
        User::factory()->create(['role' => 'manager', 'email' => 'manager@test.ru']);

        Notification::fake();

        $this->post('/', [
            'client_name' => 'Notify Client',
            'client_phone' => '+7-999-555-66-77',
            'event_type' => 'banquet',
            'event_date' => '2026-07-01',
            'people_count' => 20,
        ]);

        Notification::assertCount(2);
    }
}
