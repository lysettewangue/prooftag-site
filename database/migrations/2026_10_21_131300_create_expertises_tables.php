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
        Schema::create('expertises', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->unsignedTinyInteger('sort_order')->unique();
            $table->string('icon')->nullable();
            CmsSchema::publishableColumns($table);
            CmsSchema::publicationIndexes($table);
        });

        CmsSchema::checkPublication('expertises');
        CmsSchema::checkIn('expertises', 'code', [
            'RESEARCH_DEVELOPMENT',
            'TECHNICAL_SUPPORT',
            'DATA_ANALYSIS_REPORTING',
            'DOCUMENT_SECURITY',
            'ROAD_SAFETY_PREVENTION',
        ]);

        Schema::create('expertise_translations', function (Blueprint $table) {
            CmsSchema::translationKeys($table, 'expertises', 'expertise_id');
            $table->string('title');
            $table->text('summary')->nullable();
            $table->json('body')->nullable();
            CmsSchema::translationSeo($table);
            CmsSchema::translationUniques($table, 'expertise_id');
        });

        CmsSchema::checkLocale('expertise_translations');

        Schema::create('product_expertise', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('expertise_id')->constrained('expertises')->cascadeOnDelete();
            $table->unique(['product_id', 'expertise_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_expertise');
        Schema::dropIfExists('expertise_translations');
        Schema::dropIfExists('expertises');
    }
};
