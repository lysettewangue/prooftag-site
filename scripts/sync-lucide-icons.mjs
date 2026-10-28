import { existsSync, mkdirSync, readFileSync, readdirSync, unlinkSync, writeFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const sourceDir = join(root, 'node_modules', 'lucide-static', 'icons');
const targetDir = join(root, 'resources', 'svg', 'lucide');

/**
 * Curated public icon set for step 128.
 * Add names here when a public template needs a new Lucide glyph.
 */
const icons = [
    'arrow-right',
    'chevron-down',
    'chevron-left',
    'chevron-right',
    'circle-alert',
    'circle-check',
    'circle-x',
    'download',
    'external-link',
    'inbox',
    'info',
    'languages',
    'mail',
    'map-pin',
    'menu',
    'phone',
    'search',
    'triangle-alert',
    'x',
];

if (! existsSync(sourceDir)) {
    console.error('lucide-static is missing. Run npm install first.');
    process.exit(1);
}

mkdirSync(targetDir, { recursive: true });

const missing = [];

for (const name of icons) {
    const from = join(sourceDir, `${name}.svg`);
    const to = join(targetDir, `${name}.svg`);

    if (! existsSync(from)) {
        missing.push(name);
        continue;
    }

    const svg = readFileSync(from, 'utf8')
        .replace(/<!--[\s\S]*?-->\s*/g, '')
        .replace(/\sstroke-width="2"/, ' stroke-width="1.75"')
        .trim()
        .concat('\n');

    writeFileSync(to, svg, 'utf8');
}

const stale = readdirSync(targetDir)
    .filter((file) => file.endsWith('.svg'))
    .map((file) => file.replace(/\.svg$/, ''))
    .filter((name) => ! icons.includes(name));

for (const name of stale) {
    unlinkSync(join(targetDir, `${name}.svg`));
}

if (missing.length > 0) {
    console.error(`Missing Lucide icons: ${missing.join(', ')}`);
    process.exit(1);
}

writeFileSync(
    join(targetDir, 'manifest.json'),
    `${JSON.stringify({ generated_at: new Date().toISOString(), icons }, null, 2)}\n`,
    'utf8',
);

console.log(`Synced ${icons.length} Lucide icons to resources/svg/lucide`);
