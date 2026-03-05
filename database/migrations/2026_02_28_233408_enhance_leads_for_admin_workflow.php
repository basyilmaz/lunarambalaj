<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table): void {
            if (! Schema::hasColumn('leads', 'status')) {
                $table->string('status', 20)->default('new')->after('type');
            }
            if (! Schema::hasColumn('leads', 'notes')) {
                $table->text('notes')->nullable()->after('message');
            }
            if (! Schema::hasColumn('leads', 'assigned_to')) {
                $table->foreignId('assigned_to')->nullable()->after('email')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table): void {
            if (Schema::hasColumn('leads', 'assigned_to')) {
                $table->dropConstrainedForeignId('assigned_to');
            }
            if (Schema::hasColumn('leads', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('leads', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
