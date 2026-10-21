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
        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('from_host');
            $table->string('from_path');
            $table->string('to_path');
            $table->unsignedSmallInteger('status_code')->default(301);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['from_host', 'from_path']);
        });

        DB::statement("ALTER TABLE redirects ADD CONSTRAINT redirects_from_host_check CHECK (from_host IN ('www.prooftagcatis.com', 'site.prooftagcatis.com', 'net.prooftagcatis.com', 'prooftagcatis.com'))");
        DB::statement('ALTER TABLE redirects ADD CONSTRAINT redirects_status_code_check CHECK (status_code IN (301, 302))');
    }

    public function down(): void
    {
        Schema::dropIfExists('redirects');
    }
};
