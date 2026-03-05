<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            if (! Schema::hasColumn('pages', 'key')) {
                $table->string('key')->nullable()->after('type');
            }
        });

        DB::table('pages')
            ->whereNull('key')
            ->update(['key' => DB::raw('`type`')]);

        Schema::table('pages', function (Blueprint $table): void {
            $table->unique('key', 'pages_key_unique');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            $table->dropUnique('pages_key_unique');
            $table->dropColumn('key');
        });
    }
};

