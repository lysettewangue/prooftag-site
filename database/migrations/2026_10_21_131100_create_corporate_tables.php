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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            CmsSchema::publishableColumns($table);
            CmsSchema::publicationIndexes($table);
        });

        CmsSchema::checkPublication('pages');
        CmsSchema::checkIn('pages', 'code', [
            'home', 'about', 'organisation', 'history', 'team', 'partners',
            'careers', 'contact', 'legal_notice', 'privacy', 'technical_inspection',
        ]);

        Schema::create('page_translations', function (Blueprint $table) {
            CmsSchema::translationKeys($table, 'pages', 'page_id');
            $table->string('title');
            $table->json('blocks');
            CmsSchema::translationSeo($table);
            CmsSchema::translationUniques($table, 'page_id');
        });

        CmsSchema::checkLocale('page_translations');

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->unsignedInteger('sort_order')->default(0);
            CmsSchema::publishableColumns($table);
            CmsSchema::publicationIndexes($table);
        });

        CmsSchema::checkPublication('team_members');

        Schema::create('team_member_translations', function (Blueprint $table) {
            CmsSchema::translationKeys($table, 'team_members', 'team_member_id');
            $table->string('title');
            $table->text('bio')->nullable();
            CmsSchema::translationSeo($table);
            CmsSchema::translationUniques($table, 'team_member_id');
        });

        CmsSchema::checkLocale('team_member_translations');

        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('url')->nullable();
            CmsSchema::publishableColumns($table);
            CmsSchema::publicationIndexes($table);
        });

        CmsSchema::checkPublication('partners');

        Schema::create('partner_translations', function (Blueprint $table) {
            CmsSchema::translationKeys($table, 'partners', 'partner_id');
            $table->text('blurb')->nullable();
            CmsSchema::translationSeo($table);
            CmsSchema::translationUniques($table, 'partner_id');
        });

        CmsSchema::checkLocale('partner_translations');

        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('kind', 32);
            $table->timestamp('closes_at')->nullable();
            $table->string('application_url')->nullable();
            CmsSchema::publishableColumns($table);
            CmsSchema::publicationIndexes($table);
        });

        CmsSchema::checkPublication('careers');
        CmsSchema::checkIn('careers', 'kind', ['job', 'internship']);

        Schema::create('career_translations', function (Blueprint $table) {
            CmsSchema::translationKeys($table, 'careers', 'career_id');
            $table->string('title');
            $table->json('body')->nullable();
            CmsSchema::translationSeo($table);
            CmsSchema::translationUniques($table, 'career_id');
        });

        CmsSchema::checkLocale('career_translations');
    }

    public function down(): void
    {
        Schema::dropIfExists('career_translations');
        Schema::dropIfExists('careers');
        Schema::dropIfExists('partner_translations');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('team_member_translations');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('page_translations');
        Schema::dropIfExists('pages');
    }
};
