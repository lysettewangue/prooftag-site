<?php

declare(strict_types=1);

use App\Support\CmsSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sort_order')->default(0);
            CmsSchema::publishableColumns($table);
            CmsSchema::publicationIndexes($table);
        });

        CmsSchema::checkPublication('categories');

        Schema::create('category_translations', function (Blueprint $table) {
            CmsSchema::translationKeys($table, 'categories', 'category_id');
            $table->string('name');
            CmsSchema::translationSeo($table);
            CmsSchema::translationUniques($table, 'category_id');
        });

        CmsSchema::checkLocale('category_translations');

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('kind', 32);
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            CmsSchema::publishableColumns($table);
            CmsSchema::publicationIndexes($table);
        });

        CmsSchema::checkPublication('posts');
        CmsSchema::checkIn('posts', 'kind', ['news', 'article']);

        Schema::create('post_translations', function (Blueprint $table) {
            CmsSchema::translationKeys($table, 'posts', 'post_id');
            $table->string('title');
            $table->text('summary')->nullable();
            $table->json('body')->nullable();
            CmsSchema::translationSeo($table);
            CmsSchema::translationUniques($table, 'post_id');
        });

        CmsSchema::checkLocale('post_translations');

        Schema::create('post_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->unique(['post_id', 'category_id']);
        });

        Schema::create('post_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unique(['post_id', 'product_id']);
        });

        Schema::create('post_expertise', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('expertise_id')->constrained('expertises')->cascadeOnDelete();
            $table->unique(['post_id', 'expertise_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_expertise');
        Schema::dropIfExists('post_product');
        Schema::dropIfExists('post_category');
        Schema::dropIfExists('post_translations');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('category_translations');
        Schema::dropIfExists('categories');
    }
};
