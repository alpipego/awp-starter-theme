<?php

namespace Theme\Loaders;

use Alpipego\AWP\Template\TemplateInterface;

use function Theme\get_fields;

class BlockTemplateLoader
{
    public static function build(array $block): string
    {
        $blockData          = $block['data'];
        $data               = get_fields() ?: [];

        /** @noinspection PhpAutovivificationOnFalseValuesInspection -- we're ensuring this is an array */
        $data['attributes'] ??= [];
        $id                 = get_the_ID();
        array_walk($blockData, static function ($value, $key) use (&$data, $blockData, $id) {
            if (array_key_exists('_' . $key, $blockData)) {
                $field = acf_get_field($blockData['_' . $key]);
                if (!$field) {
                    return;
                }
                $data[$key] = apply_filters('acf/format_value', $value, $id, acf_get_field($blockData['_' . $key]), false);
            } elseif (str_starts_with($key, '_') && !array_key_exists(substr($key, 1), $blockData)) {
                $field                 = acf_get_field($blockData[$key]);
                $data[substr($key, 1)] = $field['default_value'] ?? $field['value'];
            }
        });

        if (is_array($block['supports'] ?? false)) {
            if ($block['supports']['align'] ?? false) {
                $data['attributes']['align'] = ($block['align'] ?? false) ?: [];
            }
            if ($block['supports']['anchor'] ?? false) {
                $data['attributes']['anchor'] = ($block['anchor'] ?? '') ? sprintf('id="%s"', $block['anchor']) : '';
            }
        }

        $pattern = self::templateInclude($block['name'], $data);

        return $pattern;
    }

    private static function templateInclude(string $name, array $data): TemplateInterface
    {
        $component = str_replace(get_template() . '/', '', $name);

        $pattern = TemplateLoader::pattern()->buildComponent($component, $data);
        $pattern->render([]);

        return $pattern;
    }
}
