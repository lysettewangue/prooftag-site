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
        Schema::create('inspection_centres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained('regions')->restrictOnDelete();
            $table->string('operator_name');
            $table->string('city');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('verification_status', 32)->default('unverified');
            $table->timestamp('last_verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('sort_order')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['verification_status', 'region_id']);
        });

        CmsSchema::checkIn('inspection_centres', 'verification_status', [
            'unverified',
            'active',
            'temporarily_unavailable',
            'archived',
        ]);

        Schema::create('inspection_centre_translations', function (Blueprint $table) {
            CmsSchema::translationKeys($table, 'inspection_centres', 'inspection_centre_id');
            $table->string('name');
            $table->string('address_line')->nullable();
            $table->text('body')->nullable();
            CmsSchema::translationSeo($table);
            CmsSchema::translationUniques($table, 'inspection_centre_id');
        });

        CmsSchema::checkLocale('inspection_centre_translations');
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_centre_translations');
        Schema::dropIfExists('inspection_centres');
    }
};
