<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE security_points DROP CHECK security_points_sort_order_check');
        DB::statement('ALTER TABLE security_points ADD CONSTRAINT security_points_sort_order_check CHECK (sort_order BETWEEN 0 AND 12)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE security_points DROP CHECK security_points_sort_order_check');
        DB::statement('ALTER TABLE security_points ADD CONSTRAINT security_points_sort_order_check CHECK (sort_order BETWEEN 1 AND 12)');
    }
};
