<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Redirect;
use Illuminate\Database\Seeder;

/**
 * Step 167 — load docs/legacy/redirect-register.csv into redirects.
 *
 * Idempotent upsert on (from_host, from_path). Notes column is documentation only.
 */
class LegacyRedirectSeeder extends Seeder
{
    private const REGISTER = 'docs/legacy/redirect-register.csv';

    public function run(): void
    {
        $path = base_path(self::REGISTER);

        if (! is_readable($path)) {
            throw new \RuntimeException('Missing redirect register: '.self::REGISTER);
        }

        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new \RuntimeException('Cannot open redirect register: '.self::REGISTER);
        }

        try {
            $header = fgetcsv($handle);

            if ($header === false) {
                throw new \RuntimeException('Empty redirect register CSV.');
            }

            $loaded = 0;

            while (($row = fgetcsv($handle)) !== false) {
                if ($row === [null] || $row === false || count($row) < 5) {
                    continue;
                }

                $fromHost = trim((string) $row[0]);
                $fromPath = trim((string) $row[1]);
                $toPath = trim((string) $row[2]);
                $statusCode = (int) $row[3];
                $isActive = ((string) $row[4]) === '1';

                if ($fromHost === '' || $fromPath === '' || $toPath === '') {
                    continue;
                }

                Redirect::query()->updateOrCreate(
                    [
                        'from_host' => $fromHost,
                        'from_path' => $fromPath,
                    ],
                    [
                        'to_path' => $toPath,
                        'status_code' => $statusCode,
                        'is_active' => $isActive,
                    ],
                );

                $loaded++;
            }
        } finally {
            fclose($handle);
        }

        $this->command?->info("Legacy redirects upserted: {$loaded}");
    }
}
