<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ad_integrations', function (Blueprint $table): void {
            if (! Schema::hasColumn('ad_integrations', 'credentials_encrypted')) {
                $table->longText('credentials_encrypted')->nullable();
            }
        });

        DB::table('ad_integrations')
            ->select('id', 'credentials', 'credentials_encrypted')
            ->orderBy('id')
            ->chunkById(100, function ($rows): void {
                foreach ($rows as $row) {
                    if (! empty($row->credentials_encrypted) || empty($row->credentials)) {
                        continue;
                    }

                    $credentials = is_string($row->credentials)
                        ? json_decode($row->credentials, true)
                        : (array) $row->credentials;

                    if (! is_array($credentials) || $credentials === []) {
                        continue;
                    }

                    DB::table('ad_integrations')
                        ->where('id', $row->id)
                        ->update([
                            'credentials_encrypted' => Crypt::encryptString(
                                json_encode($credentials, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                            ),
                            'updated_at' => now(),
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('ad_integrations', function (Blueprint $table): void {
            if (Schema::hasColumn('ad_integrations', 'credentials_encrypted')) {
                $table->dropColumn('credentials_encrypted');
            }
        });
    }
};

