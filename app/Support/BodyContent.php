<?php

declare(strict_types=1);

namespace App\Support;

final class BodyContent
{
    public static function toForm(mixed $body): ?string
    {
        if ($body === null) {
            return null;
        }

        if (is_string($body)) {
            return $body;
        }

        if (is_array($body)) {
            if (isset($body['html']) && is_string($body['html'])) {
                return $body['html'];
            }

            if (isset($body['content']) && is_string($body['content'])) {
                return $body['content'];
            }
        }

        return null;
    }

    /**
     * @return array{html: string}|null
     */
    public static function fromForm(mixed $value): ?array
    {
        if (is_array($value)) {
            if (isset($value['html']) && is_string($value['html']) && trim($value['html']) !== '') {
                return ['html' => $value['html']];
            }

            if (isset($value['content']) && is_string($value['content']) && trim($value['content']) !== '') {
                return ['html' => $value['content']];
            }

            return null;
        }

        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        return ['html' => $value];
    }
}
