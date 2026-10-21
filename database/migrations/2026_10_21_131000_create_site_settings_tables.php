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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('legal_name');
            $table->string('brand');
            $table->string('building');
            $table->string('city');
            $table->string('country');
            $table->string('postal_box');
            $table->string('telephone');
            $table->string('email');
            $table->string('canonical_url');
            $table->string('social_linkedin')->nullable();
            $table->string('social_facebook')->nullable();
            $table->string('social_x')->nullable();
            $table->string('social_youtube')->nullable();
            $table->string('social_instagram')->nullable();
            $table->decimal('geo_latitude', 10, 7)->nullable();
            $table->decimal('geo_longitude', 10, 7)->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE site_settings ADD CONSTRAINT site_settings_singleton_check CHECK (id = 1)');

        Schema::create('site_setting_translations', function (Blueprint $table) {
            CmsSchema::translationKeys($table, 'site_settings', 'site_setting_id', false);
            $table->string('tagline')->nullable();
            CmsSchema::translationSeo($table);
            CmsSchema::translationUniques($table, 'site_setting_id', false);
        });

        CmsSchema::checkLocale('site_setting_translations');
    }

    public function down(): void
    {
        Schema::dropIfExists('site_setting_translations');
        Schema::dropIfExists('site_settings');
    }
};
