<?php

namespace Tests\Feature;

use App\Models\AttributionLog;
use App\Models\EventLog;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_contact_form_stores_lead(): void
    {
        $this->get('/iletisim?utm_source=google&utm_medium=cpc&utm_campaign=brand&gclid=test-gclid')->assertOk();

        $response = $this->post('/iletisim', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '05551234567',
            'message' => 'Merhaba',
            'kvkk' => '1',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('leads', [
            'type' => 'contact',
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $lead = Lead::query()->where('type', 'contact')->where('email', 'test@example.com')->firstOrFail();
        $attribution = AttributionLog::query()->where('lead_id', $lead->id)->first();
        $this->assertNotNull($attribution);
        $this->assertSame('google', $attribution->utm_source);
        $this->assertSame('cpc', $attribution->utm_medium);
        $this->assertSame('brand', $attribution->utm_campaign);
        $this->assertSame('test-gclid', $attribution->gclid);
        $this->assertDatabaseHas('event_logs', [
            'event_key' => 'lead_submit',
            'lead_id' => $lead->id,
        ]);
    }

    public function test_quote_form_stores_lead_and_redirects_to_thank_you(): void
    {
        $this->get('/teklif-al?utm_source=meta&utm_medium=paid-social&utm_campaign=winter&fbclid=test-fbclid')->assertOk();

        $response = $this->post('/teklif-al', [
            'name' => 'Quote User',
            'company' => 'Demo Cafe',
            'phone' => '05550000000',
            'email' => 'quote@example.com',
            'product_category' => 'Pipet',
            'product' => 'Baskili Kagit Pipet',
            'quantity' => 7500,
            'print_needed' => 'yes',
            'wrapping_needed' => 'no',
            'delivery_city' => 'Istanbul',
            'message' => 'Hizli teklif rica ederim',
            'kvkk' => '1',
        ]);

        $response->assertRedirect('/teklif-al/tesekkurler');

        $lead = Lead::query()->where('type', 'quote')->where('email', 'quote@example.com')->first();

        $this->assertNotNull($lead);
        $this->assertSame('Pipet', $lead->meta['product_category'] ?? null);
        $this->assertSame(7500, $lead->meta['quantity'] ?? null);

        $attribution = AttributionLog::query()->where('lead_id', $lead->id)->first();
        $this->assertNotNull($attribution);
        $this->assertSame('meta', $attribution->utm_source);
        $this->assertSame('paid-social', $attribution->utm_medium);
        $this->assertSame('winter', $attribution->utm_campaign);
        $this->assertSame('test-fbclid', $attribution->fbclid);
        $this->assertDatabaseHas('event_logs', [
            'event_key' => 'lead_submit',
            'lead_id' => $lead->id,
        ]);
    }

    public function test_attribution_uses_last_touch_and_keeps_first_touch_meta(): void
    {
        $this->get('/iletisim?utm_source=google&utm_medium=cpc&utm_campaign=first')->assertOk();
        $this->get('/iletisim?utm_source=meta&utm_medium=paid-social&utm_campaign=last')->assertOk();

        $response = $this->post('/iletisim', [
            'name' => 'Touch User',
            'email' => 'touch@example.com',
            'phone' => '05559876543',
            'message' => 'Attribution test',
            'kvkk' => '1',
        ]);

        $response->assertSessionHas('success');

        $lead = Lead::query()->where('type', 'contact')->where('email', 'touch@example.com')->firstOrFail();
        $attribution = AttributionLog::query()->where('lead_id', $lead->id)->firstOrFail();

        $this->assertSame('meta', $attribution->utm_source);
        $this->assertSame('paid-social', $attribution->utm_medium);
        $this->assertSame('last', $attribution->utm_campaign);
        $this->assertSame('google', data_get($attribution->meta, 'first_touch.params.utm_source'));
        $this->assertSame('meta', data_get($attribution->meta, 'last_touch.params.utm_source'));
        $this->assertNotNull(EventLog::query()->where('lead_id', $lead->id)->where('event_key', 'lead_submit')->first());
    }

    public function test_ru_quote_form_redirects_to_ru_thank_you(): void
    {
        $response = $this->post('/ru/get-quote', [
            'name' => 'RU Quote User',
            'company' => 'Demo Cafe RU',
            'phone' => '05550000001',
            'email' => 'quote-ru@example.com',
            'product_category' => 'Pipet',
            'product' => 'Baskili Kagit Pipet',
            'quantity' => 7500,
            'print_needed' => 'yes',
            'wrapping_needed' => 'no',
            'delivery_city' => 'Istanbul',
            'message' => 'RU quote request',
            'kvkk' => '1',
        ]);

        $response->assertRedirect('/ru/get-quote/thank-you');
    }

    public function test_ar_quote_form_redirects_to_ar_thank_you(): void
    {
        $response = $this->post('/ar/get-quote', [
            'name' => 'AR Quote User',
            'company' => 'Demo Cafe AR',
            'phone' => '05550000002',
            'email' => 'quote-ar@example.com',
            'product_category' => 'Pipet',
            'product' => 'Baskili Kagit Pipet',
            'quantity' => 7500,
            'print_needed' => 'yes',
            'wrapping_needed' => 'no',
            'delivery_city' => 'Istanbul',
            'message' => 'AR quote request',
            'kvkk' => '1',
        ]);

        $response->assertRedirect('/ar/get-quote/thank-you');
    }
}

