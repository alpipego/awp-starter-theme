<?php

declare(strict_types=1);

namespace Theme\Plugins;

/**
 * Wrapper/Helper class for ACF functionality
 */
class Acf
{
    public static function isFieldSettings(): bool
    {
        return function_exists('get_current_screen') && (get_current_screen()->id ?? '') === 'acf-field-group';
    }

    public static function fillBlockFieldsByGroup(array $data, string $group): array
    {
        $fields = acf_get_fields($group);
        $id     = get_the_ID();
        foreach ($fields as $field) {
            if (isset($data[$field['name']])) {
                continue;
            }

            $data[$field['name']] = apply_filters('acf/format_value', $field['default_value'] ?? $field['value'], $id, $field, false);
        }

        return $data;
    }

    public static function fillReapeater(array $data, string $repeater): array
    {
        uksort($data, static fn($a, $b) => strlen($a) - strlen($b));
        $sorted = [];
        foreach ($data as $key => $datum) {
            if (
                $key === $repeater
                || !preg_match("/{$repeater}_(?<iter>\d+)_(?<name>.+)/", $key, $matches)
            ) {
                $sorted[$key] = $datum;
                continue;
            }

            $sorted[$repeater][$matches['iter']][$matches['name']] = $datum;
        }

        return $sorted;
    }

    /**
     * Every group pattern matches also repeater patterns, therefore repeaters have to be expanded first.
     */
    public static function fillGroup(array $data, string $group): array
    {
        uksort($data, static fn($a, $b) => strlen($a) - strlen($b));
        $sorted = [];
        foreach ($data as $key => $datum) {
            if (
                $key === $group
                || !preg_match("/{$group}_(?<name>.+)/", $key, $matches)
            ) {
                $sorted[$key] = $datum;
                continue;
            }

            $sorted[$group][$matches['name']] = $datum;
        }

        return $sorted;
    }

    public static function get_field(string $selector, int|false|null $postId = false, bool $formatValue = true, bool $escapeHtml = false): mixed
    {
        if (function_exists('get_field')) {
            return get_field($selector, $postId, $formatValue, $escapeHtml);
        }

        return get_post_meta(($postId ?? false) ?: get_the_ID(), $selector, true);
    }
}
