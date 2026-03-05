<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackingEventEndpointTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_tracking_event_endpoint_stores_event_log(): void
    {
        $this->postJson('/track/event', [
            'event_key' => 'click_phone',
            'payload' => [
                'location' => 'footer',
            ],
            'page_path' => '/iletisim',
        ])->assertOk()->assertJson(['ok' => true]);

        $this->assertDatabaseHas('event_logs', [
            'event_key' => 'click_phone',
            'page_path' => '/iletisim',
        ]);
    }

    public function test_tracking_event_endpoint_rejects_unknown_event_key(): void
    {
        $this->postJson('/track/event', [
            'event_key' => 'unknown_event',
        ])->assertStatus(422);
    }
}
