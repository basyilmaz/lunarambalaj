<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseHealthCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_db_health_command_passes_with_required_tables(): void
    {
        $this->artisan('ops:db-health')
            ->assertExitCode(0);
    }

    public function test_db_health_command_fails_when_required_table_is_missing(): void
    {
        Schema::dropIfExists('leads');

        $this->artisan('ops:db-health')
            ->assertExitCode(1);
    }

    public function test_db_health_expect_seeded_requires_baseline_data(): void
    {
        $this->artisan('ops:db-health --expect-seeded')
            ->assertExitCode(1);

        $this->seed();

        $this->artisan('ops:db-health --expect-seeded')
            ->assertExitCode(0);
    }
}
