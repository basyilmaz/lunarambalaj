<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('case_studies', function (Blueprint $table): void {
            $table->id();
            $table->string('client_name');
            $table->string('client_logo')->nullable();
            $table->string('industry')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('case_study_translations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('case_study_id')->constrained()->cascadeOnDelete();
            $table->string('lang', 5);
            $table->string('title');
            $table->string('slug');
            $table->text('challenge')->nullable();
            $table->text('solution')->nullable();
            $table->text('results')->nullable();
            $table->string('seo_title', 60)->nullable();
            $table->string('seo_desc', 160)->nullable();
            $table->timestamps();
            $table->unique(['case_study_id', 'lang']);
            $table->unique(['lang', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_study_translations');
        Schema::dropIfExists('case_studies');
    }
};
