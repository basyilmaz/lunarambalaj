<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelAuditFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_access_new_admin_audit_pages(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk();

        $this->actingAs($admin)
            ->get('/admin/lead-pipeline')
            ->assertOk()
            ->assertSee('Lead pipeline');

        $this->actingAs($admin)
            ->get('/admin/seo-health')
            ->assertOk()
            ->assertSee('SEO detay listesi');

        $this->actingAs($admin)
            ->get('/admin/translation-coverage')
            ->assertOk()
            ->assertSee('Kapsama ozeti');

        $this->actingAs($admin)
            ->get('/admin/ads-insights')
            ->assertOk()
            ->assertSee('Kategori Bazli Donusum')
            ->assertSee('UTM Hygiene');
    }

    public function test_viewer_cannot_access_user_management_resource(): void
    {
        $viewer = User::factory()->create([
            'role' => User::ROLE_VIEWER,
        ]);

        $this->actingAs($viewer)
            ->get('/admin/users')
            ->assertForbidden();

        $this->actingAs($viewer)
            ->get('/admin/lead-pipeline')
            ->assertForbidden();
    }

    public function test_marketing_manager_can_access_ads_and_lead_pipeline_but_not_users(): void
    {
        $manager = User::factory()->create([
            'role' => User::ROLE_MARKETING_MANAGER,
        ]);

        $this->actingAs($manager)
            ->get('/admin/ads-insights')
            ->assertOk();

        $this->actingAs($manager)
            ->get('/admin/lead-pipeline')
            ->assertOk();

        $this->actingAs($manager)
            ->get('/admin/ads-sync-logs')
            ->assertOk();

        $this->actingAs($manager)
            ->get('/admin/users')
            ->assertForbidden();
    }

    public function test_developer_can_access_ads_and_settings_but_not_users_or_lead_pipeline(): void
    {
        $developer = User::factory()->create([
            'role' => User::ROLE_DEVELOPER,
        ]);

        $this->actingAs($developer)
            ->get('/admin/ads-insights')
            ->assertOk();

        $this->actingAs($developer)
            ->get('/admin/settings')
            ->assertStatus(302);

        $this->actingAs($developer)
            ->get('/admin/ads-sync-logs')
            ->assertOk();

        $this->actingAs($developer)
            ->get('/admin/users')
            ->assertForbidden();

        $this->actingAs($developer)
            ->get('/admin/lead-pipeline')
            ->assertForbidden();
    }
}
