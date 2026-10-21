<?php

declare(strict_types=1);

use App\Support\CmsSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('sort_order')->unique();
            CmsSchema::publishableColumns($table);
            CmsSchema::publicationIndexes($table);
        });

        CmsSchema::checkPublication('security_points');
        DB::statement('ALTER TABLE security_points ADD CONSTRAINT security_points_sort_order_check CHECK (sort_order BETWEEN 1 AND 12)');

        Schema::create('security_point_translations', function (Blueprint $table) {
            CmsSchema::translationKeys($table, 'security_points', 'security_point_id', false);
            $table->string('title');
            $table->text('body');
            $table->timestamps();
            CmsSchema::translationUniques($table, 'security_point_id', false);
        });

        CmsSchema::checkLocale('security_point_translations');

        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('lifecycle', 32);
            $table->unsignedInteger('sort_order')->default(0);
            CmsSchema::publishableColumns($table);
            CmsSchema::publicationIndexes($table);
        });

        CmsSchema::checkPublication('equipment');
        CmsSchema::checkIn('equipment', 'lifecycle', ['current', 'legacy']);

        Schema::create('equipment_translations', function (Blueprint $table) {
            CmsSchema::translationKeys($table, 'equipment', 'equipment_id');
            $table->string('title');
            $table->json('body')->nullable();
            CmsSchema::translationSeo($table);
            CmsSchema::translationUniques($table, 'equipment_id');
        });

        CmsSchema::checkLocale('equipment_translations');
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_translations');
        Schema::dropIfExists('equipment');
        Schema::dropIfExists('security_point_translations');
        Schema::dropIfExists('security_points');
    }
};
