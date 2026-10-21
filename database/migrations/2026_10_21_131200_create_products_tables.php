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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('public_name');
            $table->unsignedTinyInteger('sort_order')->unique();
            $table->string('slug_fragment');
            $table->string('external_url')->nullable();
            CmsSchema::publishableColumns($table);
            CmsSchema::publicationIndexes($table, true);
        });

        CmsSchema::checkPublication('products');
        CmsSchema::checkIn('products', 'code', [
            'CERTIDOCS_CT',
            'CERTIDOCS_CT_REPORTING',
            'CT_VERIF',
            'DOSER',
            'VIDEO_ENFORCEMENT',
        ]);

        Schema::create('product_translations', function (Blueprint $table) {
            CmsSchema::translationKeys($table, 'products', 'product_id');
            $table->text('summary')->nullable();
            $table->json('body')->nullable();
            $table->string('external_label')->nullable();
            CmsSchema::translationSeo($table);
            CmsSchema::translationUniques($table, 'product_id');
        });

        CmsSchema::checkLocale('product_translations');
    }

    public function down(): void
    {
        Schema::dropIfExists('product_translations');
        Schema::dropIfExists('products');
    }
};
