<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Strips draft / VER gate wording from public-facing copy so thin CMS records
 * do not render as raw editorial notes.
 */
final class PublicEditorialCopy
{
    private const BLOCKED = '/VER-|brouillon|draft\b|publication après|rewrite required|thème à confirmer|theme to confirm|URL publique n’est pas|URL publique n\'est pas|public URL is not|reste en brouillon|remains a draft|tant que VER|while VER-|Pas d’<code>|No <code>|réécriture requise|Rewrite required/iu';

    public static function summary(?string $summary): ?string
    {
        if ($summary === null) {
            return null;
        }

        $trimmed = trim($summary);

        if ($trimmed === '' || preg_match(self::BLOCKED, $trimmed) === 1) {
            return null;
        }

        return $trimmed;
    }

    public static function html(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return null;
        }

        $parts = preg_split('/(?=<p\b)/iu', $html) ?: [];
        $kept = [];

        foreach ($parts as $part) {
            $chunk = trim($part);
            if ($chunk === '') {
                continue;
            }
            if (preg_match(self::BLOCKED, $chunk) === 1) {
                continue;
            }
            $kept[] = $chunk;
        }

        $clean = trim(implode("\n", $kept));

        return $clean !== '' ? $clean : null;
    }

    public static function firstParagraphText(?string $html): ?string
    {
        $paragraphs = self::paragraphs($html);

        return $paragraphs[0] ?? null;
    }

    /**
     * @return list<string>
     */
    public static function paragraphs(?string $html): array
    {
        $clean = self::html($html);
        if ($clean === null) {
            return [];
        }

        preg_match_all('/<p\b[^>]*>(.*?)<\/p>/is', $clean, $matches);

        $out = [];
        foreach ($matches[1] as $inner) {
            $text = trim(html_entity_decode(strip_tags((string) $inner), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            if ($text !== '' && preg_match(self::BLOCKED, $text) !== 1) {
                $out[] = $text;
            }
        }

        return $out;
    }

    /**
     * @return list<string>
     */
    public static function listItems(?string $html): array
    {
        $clean = self::html($html);
        if ($clean === null) {
            return [];
        }

        preg_match_all('/<li\b[^>]*>(.*?)<\/li>/is', $clean, $matches);

        $out = [];
        foreach ($matches[1] as $inner) {
            $text = trim(html_entity_decode(strip_tags((string) $inner), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            if ($text !== '' && preg_match(self::BLOCKED, $text) !== 1) {
                $out[] = $text;
            }
        }

        return $out;
    }
}
