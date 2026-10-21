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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('regulatory_status', 32);
            $table->string('authority')->nullable();
            $table->date('issued_on')->nullable();
            $table->boolean('relates_to_ct')->default(false);
            CmsSchema::publishableColumns($table);
            CmsSchema::publicationIndexes($table);
        });

        CmsSchema::checkPublication('resources');
        CmsSchema::checkIn('resources', 'regulatory_status', ['current', 'historical', 'archive']);

        Schema::create('resource_translations', function (Blueprint $table) {
            CmsSchema::translationKeys($table, 'resources', 'resource_id');
            $table->string('title');
            $table->text('summary')->nullable();
            CmsSchema::translationSeo($table);
            CmsSchema::translationUniques($table, 'resource_id');
        });

        CmsSchema::checkLocale('resource_translations');

        Schema::create('resource_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unique(['resource_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_product');
        Schema::dropIfExists('resource_translations');
        Schema::dropIfExists('resources');
    }
};
