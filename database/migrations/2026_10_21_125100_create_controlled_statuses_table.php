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
        Schema::create('controlled_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('set', 64);
            $table->string('code', 64);
            $table->unsignedSmallInteger('sort_order');
            $table->timestamps();

            $table->unique(['set', 'code']);
        });

        DB::statement("ALTER TABLE controlled_statuses ADD CONSTRAINT controlled_statuses_set_check CHECK (`set` IN ('publication', 'centre_verification', 'resource_regulatory', 'equipment_lifecycle', 'career_kind', 'post_kind', 'contact_request'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('controlled_statuses');
    }
};
