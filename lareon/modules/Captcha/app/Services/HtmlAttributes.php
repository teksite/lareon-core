<?php

namespace Lareon\Modules\Captcha\App\Services;

/**
 * Builds an HTML attribute string safely.
 */
final class HtmlAttributes
{
    /**
     * Values are escaped; null/false skips an attribute and true renders it without a value.
     * Attribute names that are not valid are dropped (guards against attribute-name injection).
     */
    public static function build(array $attributes): string
    {
        $parts = [];

        foreach ($attributes as $name => $value) {
            if ($value === null || $value === false) {
                continue;
            }

            if (!preg_match('/^[a-zA-Z_:][-a-zA-Z0-9_:.]*$/', (string)$name)) {
                continue;
            }

            $parts[] = $value === true ? $name : $name . '="' . e((string)$value) . '"';
        }

        return implode(' ', $parts);
    }
}
