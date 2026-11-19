<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! $this->supportsEnforcedCheckConstraints()) {
            return;
        }

        DB::statement('ALTER TABLE security_points DROP CHECK security_points_sort_order_check');
        DB::statement('ALTER TABLE security_points ADD CONSTRAINT security_points_sort_order_check CHECK (sort_order BETWEEN 0 AND 12)');
    }

    public function down(): void
    {
        if (! $this->supportsEnforcedCheckConstraints()) {
            return;
        }

        DB::statement('ALTER TABLE security_points DROP CHECK security_points_sort_order_check');
        DB::statement('ALTER TABLE security_points ADD CONSTRAINT security_points_sort_order_check CHECK (sort_order BETWEEN 1 AND 12)');
    }

    private function supportsEnforcedCheckConstraints(): bool
    {
        $row = DB::selectOne('SELECT VERSION() AS version');
        $version = is_object($row) && isset($row->version) ? (string) $row->version : '';

        return ! str_contains(strtolower($version), 'mariadb')
            && version_compare($version, '8.0.16', '>=');
    }
};
