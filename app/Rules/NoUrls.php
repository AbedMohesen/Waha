<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoUrls implements ValidationRule
{
    /**
     * @var list<string>
     */
    private const URL_PATTERNS = [
        '~https?://~iu',
        '~www\.~iu',
        '~<\s*a\b[^>]*>~iu',
        '~\b(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,63}(?:[/?:#][^\s]*)?~iu',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            return;
        }

        foreach (self::URL_PATTERNS as $pattern) {
            if (preg_match($pattern, $value) === 1) {
                $fail('لا يُسمح بإضافة روابط داخل رسالة التعزية.');

                return;
            }
        }
    }
}
