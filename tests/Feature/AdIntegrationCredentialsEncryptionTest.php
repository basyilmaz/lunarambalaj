<?php

namespace Tests\Feature;

use App\Models\AdIntegration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdIntegrationCredentialsEncryptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_credentials_are_encrypted_at_rest_and_accessible_via_model(): void
    {
        $integration = AdIntegration::query()->create([
            'platform' => 'google_ads',
            'name' => 'Google Ads',
            'credentials' => [
                'api_token' => 'token-123',
                'customer_id' => '123456',
                'developer_token' => 'dev-xyz',
            ],
            'is_active' => true,
        ]);

        $this->assertSame('token-123', $integration->credentials['api_token'] ?? null);

        $rawEncrypted = DB::table('ad_integrations')
            ->where('id', $integration->id)
            ->value('credentials_encrypted');

        $this->assertNotNull($rawEncrypted);
        $this->assertStringNotContainsString('token-123', (string) $rawEncrypted);

        $decrypted = Crypt::decryptString((string) $rawEncrypted);
        $payload = json_decode($decrypted, true);

        $this->assertIsArray($payload);
        $this->assertSame('token-123', $payload['api_token'] ?? null);
    }

    public function test_legacy_plaintext_credentials_are_still_readable(): void
    {
        $id = DB::table('ad_integrations')->insertGetId([
            'platform' => 'meta_ads',
            'name' => 'Meta Ads Legacy',
            'credentials' => json_encode([
                'access_token' => 'legacy-token',
                'ad_account_id' => 'act_123',
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'credentials_encrypted' => null,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $integration = AdIntegration::query()->findOrFail($id);

        $this->assertSame('legacy-token', $integration->credentials['access_token'] ?? null);
        $this->assertSame('act_123', $integration->credentials['ad_account_id'] ?? null);
    }

    public function test_updating_credentials_rotates_encrypted_payload(): void
    {
        $integration = AdIntegration::query()->create([
            'platform' => 'google_ads',
            'name' => 'Google Ads',
            'credentials' => [
                'api_token' => 'token-old',
                'customer_id' => '123456',
                'developer_token' => 'dev-old',
            ],
            'is_active' => true,
        ]);

        $oldEncrypted = DB::table('ad_integrations')
            ->where('id', $integration->id)
            ->value('credentials_encrypted');

        $integration->credentials = [
            'api_token' => 'token-new',
            'customer_id' => '123456',
            'developer_token' => 'dev-new',
        ];
        $integration->save();

        $newEncrypted = DB::table('ad_integrations')
            ->where('id', $integration->id)
            ->value('credentials_encrypted');

        $this->assertNotSame($oldEncrypted, $newEncrypted);

        $integration->refresh();
        $this->assertSame('token-new', $integration->credentials['api_token'] ?? null);
        $this->assertSame('dev-new', $integration->credentials['developer_token'] ?? null);
    }
}
