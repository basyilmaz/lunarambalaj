<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 5)->unique();
            $table->string('name');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table): void {
            $table->id();
            $table->string('type')->unique();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('page_translations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->string('lang', 5);
            $table->string('title');
            $table->string('slug');
            $table->longText('body');
            $table->string('seo_title', 60)->nullable();
            $table->string('seo_desc', 160)->nullable();
            $table->timestamps();
            $table->unique(['page_id', 'lang']);
            $table->unique(['lang', 'slug']);
        });

        Schema::create('service_items', function (Blueprint $table): void {
            $table->id();
            $table->string('icon')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('service_item_translations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('service_item_id')->constrained()->cascadeOnDelete();
            $table->string('lang', 5);
            $table->string('title');
            $table->text('body')->nullable();
            $table->timestamps();
            $table->unique(['service_item_id', 'lang']);
        });

        Schema::create('product_categories', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_category_translations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_category_id')->constrained()->cascadeOnDelete();
            $table->string('lang', 5);
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
            $table->unique(['product_category_id', 'lang']);
            $table->unique(['lang', 'slug']);
        });

        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->constrained('product_categories')->cascadeOnDelete();
            $table->unsignedInteger('min_order')->default(5000);
            $table->boolean('has_print')->default(false);
            $table->boolean('has_wrapping')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('product_translations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('lang', 5);
            $table->string('name');
            $table->string('slug');
            $table->text('short_desc')->nullable();
            $table->longText('description')->nullable();
            $table->string('seo_title', 60)->nullable();
            $table->string('seo_desc', 160)->nullable();
            $table->timestamps();
            $table->unique(['product_id', 'lang']);
            $table->unique(['lang', 'slug']);
        });

        Schema::create('posts', function (Blueprint $table): void {
            $table->id();
            $table->string('cover')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('post_translations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->string('lang', 5);
            $table->string('title');
            $table->string('slug');
            $table->longText('body');
            $table->string('seo_title', 60)->nullable();
            $table->string('seo_desc', 160)->nullable();
            $table->timestamps();
            $table->unique(['post_id', 'lang']);
            $table->unique(['lang', 'slug']);
        });

        Schema::create('faqs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('faq_translations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('faq_id')->constrained()->cascadeOnDelete();
            $table->string('lang', 5);
            $table->string('question');
            $table->text('answer');
            $table->timestamps();
            $table->unique(['faq_id', 'lang']);
        });

        Schema::create('references', function (Blueprint $table): void {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('url')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table): void {
            $table->id();
            $table->string('type', 20);
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('message')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('settings', function (Blueprint $table): void {
            $table->id();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('working_hours')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('references');
        Schema::dropIfExists('faq_translations');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('post_translations');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('product_translations');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_category_translations');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('service_item_translations');
        Schema::dropIfExists('service_items');
        Schema::dropIfExists('page_translations');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('languages');
    }
};
