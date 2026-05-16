<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table): void {
            if (! Schema::hasColumn('leads', 'gclid')) {
                $table->string('gclid', 255)->nullable()->after('email');
            }
            if (! Schema::hasColumn('leads', 'estimated_value')) {
                $table->decimal('estimated_value', 12, 2)->nullable()->after('notes');
            }
        });

        Schema::table('leads', function (Blueprint $table): void {
            if (Schema::hasColumn('leads', 'gclid')) {
                $table->index('gclid', 'leads_gclid_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table): void {
            if (Schema::hasColumn('leads', 'gclid')) {
                $table->dropIndex('leads_gclid_index');
                $table->dropColumn('gclid');
            }
            if (Schema::hasColumn('leads', 'estimated_value')) {
                $table->dropColumn('estimated_value');
            }
        });
    }
};
