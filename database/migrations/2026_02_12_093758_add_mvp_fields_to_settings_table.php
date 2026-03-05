<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $table->string('gtm_id')->nullable()->after('linkedin');
            $table->string('meta_pixel_id')->nullable()->after('gtm_id');
            $table->unsignedInteger('min_order_default')->default(5000)->after('meta_pixel_id');
            $table->string('company_name_tr')->nullable()->after('min_order_default');
            $table->string('company_name_en')->nullable()->after('company_name_tr');
            $table->string('hero_h1_tr')->nullable()->after('company_name_en');
            $table->string('hero_h1_en')->nullable()->after('hero_h1_tr');
            $table->string('hero_subtitle_tr')->nullable()->after('hero_h1_en');
            $table->string('hero_subtitle_en')->nullable()->after('hero_subtitle_tr');
            $table->text('footer_short_tr')->nullable()->after('hero_subtitle_en');
            $table->text('footer_short_en')->nullable()->after('footer_short_tr');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $table->dropColumn([
                'gtm_id',
                'meta_pixel_id',
                'min_order_default',
                'company_name_tr',
                'company_name_en',
                'hero_h1_tr',
                'hero_h1_en',
                'hero_subtitle_tr',
                'hero_subtitle_en',
                'footer_short_tr',
                'footer_short_en',
            ]);
        });
    }
};
