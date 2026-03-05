<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ad_integrations', function (Blueprint $table): void {
            if (! Schema::hasColumn('ad_integrations', 'last_sync_status')) {
                $table->string('last_sync_status', 20)->nullable()->after('last_sync_at');
            }
            if (! Schema::hasColumn('ad_integrations', 'last_sync_error')) {
                $table->text('last_sync_error')->nullable()->after('last_sync_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ad_integrations', function (Blueprint $table): void {
            if (Schema::hasColumn('ad_integrations', 'last_sync_error')) {
                $table->dropColumn('last_sync_error');
            }
            if (Schema::hasColumn('ad_integrations', 'last_sync_status')) {
                $table->dropColumn('last_sync_status');
            }
        });
    }
};

