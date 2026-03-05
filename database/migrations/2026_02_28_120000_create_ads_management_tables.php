<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ad_integrations', function (Blueprint $table): void {
            $table->id();
            $table->string('platform', 50);
            $table->string('name')->nullable();
            $table->json('credentials')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();
            $table->unique('platform');
        });

        Schema::create('tracking_events', function (Blueprint $table): void {
            $table->id();
            $table->string('event_key', 100)->unique();
            $table->string('display_name');
            $table->json('schema')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('conversion_mappings', function (Blueprint $table): void {
            $table->id();
            $table->string('platform', 50);
            $table->foreignId('tracking_event_id')->constrained('tracking_events')->cascadeOnDelete();
            $table->string('remote_conversion_id')->nullable();
            $table->string('value_rule')->nullable();
            $table->string('dedup_key')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['platform', 'tracking_event_id']);
        });

        Schema::create('campaign_snapshots', function (Blueprint $table): void {
            $table->id();
            $table->string('platform', 50);
            $table->string('campaign_id', 100);
            $table->string('campaign_name')->nullable();
            $table->date('snapshot_date');
            $table->decimal('spend', 12, 2)->default(0);
            $table->unsignedBigInteger('clicks')->default(0);
            $table->unsignedBigInteger('impressions')->default(0);
            $table->unsignedBigInteger('conversions')->default(0);
            $table->timestamps();
            $table->unique(['platform', 'campaign_id', 'snapshot_date']);
            $table->index(['platform', 'snapshot_date']);
        });

        Schema::create('attribution_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('utm_content')->nullable();
            $table->string('gclid')->nullable();
            $table->string('fbclid')->nullable();
            $table->string('landing_path')->nullable();
            $table->string('session_id')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['utm_source', 'utm_medium', 'utm_campaign']);
        });

        Schema::create('report_caches', function (Blueprint $table): void {
            $table->id();
            $table->string('report_key', 100);
            $table->string('filter_hash', 64);
            $table->json('payload');
            $table->timestamp('generated_at');
            $table->timestamps();
            $table->unique(['report_key', 'filter_hash']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_caches');
        Schema::dropIfExists('attribution_logs');
        Schema::dropIfExists('campaign_snapshots');
        Schema::dropIfExists('conversion_mappings');
        Schema::dropIfExists('tracking_events');
        Schema::dropIfExists('ad_integrations');
    }
};

