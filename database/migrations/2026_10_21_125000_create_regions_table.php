<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->char('code', 2)->unique();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE regions ADD CONSTRAINT regions_code_check CHECK (code IN ('AD', 'CE', 'ES', 'EN', 'LT', 'NO', 'NW', 'OU', 'SU', 'SW'))");

        Schema::create('region_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained()->cascadeOnDelete();
            $table->char('locale', 2);
            $table->string('name');
            $table->string('slug', 191);
            $table->timestamps();

            $table->unique(['region_id', 'locale']);
            $table->unique(['locale', 'slug']);
        });

        DB::statement("ALTER TABLE region_translations ADD CONSTRAINT region_translations_locale_check CHECK (locale IN ('fr', 'en'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('region_translations');
        Schema::dropIfExists('regions');
    }
};
