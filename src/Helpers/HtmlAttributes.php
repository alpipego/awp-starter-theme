<?php

declare(strict_types=1);

namespace Theme\Helpers;

class HtmlAttributes
{
    private static function implode(iterable $values): string
    {
        return implode(' ', array_unique((array)$values));
    }

    public static function parseAttributes(array $attributes, array $keys = null): string
    {
        if (!isset($keys) && count(array_filter(array_keys($attributes), 'is_string')) === 0) {
            return self::implode($attributes);
        }

        return implode(
            ' ',
            array_map(static function ($value, $name) {
                if (is_iterable($value)) {
                    $value = self::implode($value);
                }

                return sprintf('%s="%s"', $name, $value);
            }, $attributes, $keys ?? array_keys($attributes))
        );
    }

    public static function className(string $field_group) :string
    {
        return str_replace('_', '-', sanitize_html_class($field_group));
    }
}
