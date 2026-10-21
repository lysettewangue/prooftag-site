<?php

declare(strict_types=1);

namespace App\Support;

final class PageOverview
{
    public static function toForm(mixed $blocks): ?string
    {
        if (! is_array($blocks)) {
            return null;
        }

        foreach ($blocks as $block) {
            if (! is_array($block) || ($block['type'] ?? null) !== 'rich_text') {
                continue;
            }

            $content = $block['content'] ?? $block['html'] ?? null;

            if (is_string($content) && trim($content) !== '') {
                return $content;
            }
        }

        return null;
    }

    /**
     * @return list<array{type: string, content: string}>
     */
    public static function fromForm(mixed $value): array
    {
        if (! is_string($value) || trim($value) === '') {
            return [];
        }

        return [['type' => 'rich_text', 'content' => $value]];
    }
}
