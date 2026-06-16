<?php

namespace Tests\Feature;

use App\Models\Integration;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SecuritySecretsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRbac();
    }

    public function test_integration_credentials_are_encrypted_at_rest(): void
    {
        Integration::create([
            'provider' => 'Stripe',
            'type' => 'payment',
            'is_enabled' => true,
            'status' => 'connected',
            'credentials' => ['secret_key' => 'sk_live_SUPERSECRET'],
        ]);

        $raw = DB::table('integrations')->value('credentials');

        // Stored ciphertext must not contain the plaintext secret...
        $this->assertStringNotContainsString('sk_live_SUPERSECRET', (string) $raw);
        // ...but the model decrypts it back to the original array.
        $this->assertSame(['secret_key' => 'sk_live_SUPERSECRET'], Integration::first()->credentials);
    }

    public function test_integration_credentials_are_masked_in_api_responses(): void
    {
        Integration::create([
            'provider' => 'Stripe', 'type' => 'payment', 'is_enabled' => true,
            'status' => 'connected', 'credentials' => ['secret_key' => 'sk_live_SUPERSECRET'],
        ]);

        $this->actingAsRole('admin');

        $response = $this->getJson('/api/v1/integrations');
        $response->assertOk();

        $this->assertStringNotContainsString('sk_live_SUPERSECRET', $response->getContent());
        $this->assertSame('********', $response->json('data.0.credentials.secret_key'));
    }

    public function test_sensitive_settings_are_masked_in_api_responses(): void
    {
        Setting::create(['group' => 'integration', 'key' => 'smtp_password', 'value' => 'topsecret', 'type' => 'string']);
        Setting::create(['group' => 'general', 'key' => 'institution_name', 'value' => 'GIU', 'type' => 'string']);

        $this->actingAsRole('admin');

        $response = $this->getJson('/api/v1/settings?per_page=100');
        $response->assertOk();

        $this->assertStringNotContainsString('topsecret', $response->getContent());

        $rows = collect($response->json('data'));
        $this->assertSame('********', $rows->firstWhere('key', 'smtp_password')['value']);
        $this->assertSame('GIU', $rows->firstWhere('key', 'institution_name')['value']);
    }
}
