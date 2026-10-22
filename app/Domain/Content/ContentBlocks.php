<?php

declare(strict_types=1);

namespace App\Domain\Content;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\ContentBlockType;
use Illuminate\Support\Arr;

final class ContentBlocks
{
    /**
     * @var list<string>
     */
    private const FORBIDDEN_KEYS = [
        'color',
        'colour',
        'font',
        'font_family',
        'font_size',
        'margin',
        'padding',
        'width',
        'max_width',
        'spacing',
        'nav',
        'navigation',
        'brand',
        'primary_nav',
        'background',
    ];

    /**
     * @return list<array<string, mixed>>
     */
    public static function normalize(mixed $blocks): array
    {
        if ($blocks === null) {
            return [];
        }

        if (! is_array($blocks)) {
            throw new DomainRuleViolation('Page blocks must be an array.');
        }

        $normalized = [];

        foreach ($blocks as $block) {
            if (! is_array($block)) {
                throw new DomainRuleViolation('Each content block must be an object.');
            }

            $type = $block['type'] ?? null;

            if (! is_string($type) || ContentBlockType::tryFrom($type) === null) {
                throw new DomainRuleViolation('Unknown or missing content block type.');
            }

            $payload = $block['data'] ?? Arr::except($block, ['type', 'data', 'uuid']);

            if (! is_array($payload)) {
                throw new DomainRuleViolation('Content block payload must be an object.');
            }

            self::assertNoBrandKeys($payload);

            $normalized[] = array_merge(['type' => $type], $payload);
        }

        return $normalized;
    }

    /**
     * @param  list<mixed>|array<string, mixed>|null  $blocks
     */
    public static function assert(mixed $blocks): void
    {
        self::normalize($blocks);
    }

    /**
     * @param  list<mixed>|array<string, mixed>|null  $blocks
     * @return list<array{type: string, data: array<string, mixed>}>
     */
    public static function toForm(mixed $blocks): array
    {
        if (! is_array($blocks)) {
            return [];
        }

        $items = [];

        foreach ($blocks as $block) {
            if (! is_array($block) || ! isset($block['type']) || ! is_string($block['type'])) {
                continue;
            }

            $items[] = [
                'type' => $block['type'],
                'data' => Arr::except($block, ['type']),
            ];
        }

        return $items;
    }

    /**
     * @param  array<int|string, mixed>  $payload
     */
    private static function assertNoBrandKeys(array $payload): void
    {
        foreach ($payload as $key => $value) {
            if (is_string($key) && in_array($key, self::FORBIDDEN_KEYS, true)) {
                throw new DomainRuleViolation('Brand, type, spacing, and navigation are not editable in content blocks.');
            }

            if (is_array($value)) {
                self::assertNoBrandKeys($value);
            }
        }
    }
}
