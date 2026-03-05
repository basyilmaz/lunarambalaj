<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ads_sync_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('ad_integration_id')->nullable()->constrained('ad_integrations')->nullOnDelete();
            $table->string('platform', 50);
            $table->string('status', 20); // success|failed|skipped
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->unsignedInteger('fetched')->default(0);
            $table->unsignedInteger('upserted')->default(0);
            $table->text('error_message')->nullable();
            $table->json('context')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['platform', 'created_at']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ads_sync_logs');
    }
};

