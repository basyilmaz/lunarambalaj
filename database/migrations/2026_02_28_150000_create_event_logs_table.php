<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_logs', function (Blueprint $table): void {
            $table->id();
            $table->string('event_key', 100);
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->string('session_id')->nullable();
            $table->string('page_path')->nullable();
            $table->string('locale', 10)->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['event_key', 'created_at']);
            $table->index(['session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_logs');
    }
};

