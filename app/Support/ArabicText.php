<?php

namespace App\Support;

final class ArabicText
{
    public static function normalize(?string $text): string
    {
        if ($text === null || $text === '') {
            return '';
        }

        $text = preg_replace(
            '/[\x{0610}-\x{061A}\x{064B}-\x{065F}\x{0670}\x{06D6}-\x{06ED}]/u',
            '',
            $text
        ) ?? $text;

        $text = str_replace(
            ['أ', 'إ', 'آ', 'ٱ', 'ة', 'ى', 'ـ'],
            ['ا', 'ا', 'ا', 'ا', 'ه', 'ي', ''],
            $text
        );

        return trim(preg_replace('/\s+/u', ' ', $text) ?? $text);
    }

    /**
     * @return list<string>
     */
    public static function terms(?string $text): array
    {
        $normalized = self::normalize($text);

        if ($normalized === '') {
            return [];
        }

        return preg_split('/\s+/u', $normalized, flags: PREG_SPLIT_NO_EMPTY) ?: [];
    }

    public static function escapeLike(string $text): string
    {
        return str_replace(
            ['!', '%', '_'],
            ['!!', '!%', '!_'],
            $text
        );
    }
}
