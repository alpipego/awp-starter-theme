<?php

namespace Theme\Loaders;

use Alpipego\AWP\Template\TemplateInterface;

class BlockTemplateLoader
{
    public static function applyInnerBlockClasses($block_content, $block)
    {
        // Only process theme blocks
        if (!str_starts_with($block['blockName'] ?? '', 'theme/')) {
            return $block_content;
        }

        $block_name     = str_replace('theme/', '', $block['blockName']);
        $class_mappings = apply_filters("theme/inner_block_classes/{$block_name}", []);

        if (empty($class_mappings)) {
            return $block_content;
        }

        // Apply classes using targeted string replacement with deduplication
        foreach ($class_mappings as $block_type => $classes) {
            if (empty($classes)) {
                continue;
            }

            $tag = str_replace('core/', '', $block_type);

            // Use regex to capture existing classes and deduplicate
            $pattern       = '/class="(wp-block-' . preg_quote($tag, '/') . '[^"]*?)"/';
            $block_content = preg_replace_callback($pattern, static function ($matches) use ($classes) {
                $existing_classes = $matches[1];
                $all_classes      = $existing_classes . ' ' . $classes;

                // Split into array, remove duplicates, rejoin
                $class_array = array_filter(array_unique(explode(' ', $all_classes)));

                return 'class="' . implode(' ', $class_array) . '"';
            }, $block_content);
        }

        return $block_content;
    }


    public static function build($block, $content = '', $is_preview = false, $post_id = 0): string
    {
        $blockData = $block['data'];
        $data      = get_fields() ?: [];

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
        $component = str_replace('theme/', '', $name);

        $pattern = TemplateLoader::pattern()->buildComponent($component, $data);
        $pattern->render([]);

        return $pattern;
    }


    public static function editorBlocks(array $blockStructure): string
    {
        return esc_attr(wp_json_encode([$blockStructure]));
    }

    public static function getAllInnerBlockClassMappings(): array
    {
        $allMappings   = [];
        $componentsDir = sprintf(
            '%s/%s/%s',
            get_template_directory(),
            apply_filters('awp/template/pattern/data', '_data'),
            apply_filters('awp/template/pattern/path/components', '_components'),
        );

        if (!is_dir($componentsDir)) {
            return $allMappings;
        }

        // Get all PHP files from components directory only
        $dataTransformers = glob($componentsDir . '/*.php');

        foreach ($dataTransformers as $file) {
            $blockName = pathinfo($file, PATHINFO_FILENAME);

            // Parse file content to extract inner block class mappings
            $content = file_get_contents($file);

            // Look for the add_filter pattern for inner block classes
            $pattern = '/add_filter\s*\(\s*[\'"]theme\/inner_block_classes\/([^\'"]+)[\'"][^;]*?(\$[a-zA-Z_][a-zA-Z0-9_]*)/';
            if (preg_match($pattern, $content, $matches)) {
                $variableName = $matches[2];

                // Look for the variable definition
                $varPattern = '/\s*' . preg_quote($variableName, '/') . '\s*=\s*\[(.*?)\];/s';
                if (preg_match($varPattern, $content, $varMatches)) {
                    // Parse the array content
                    $arrayContent  = $varMatches[1];
                    $classMappings = [];

                    // Simple regex to extract key-value pairs
                    $kvPattern = '/[\'"]([^\'"]+)[\'"]\s*=>\s*[\'"]([^\'"]*)[\'"]/';
                    if (preg_match_all($kvPattern, $arrayContent, $kvMatches, PREG_SET_ORDER)) {
                        foreach ($kvMatches as $kvMatch) {
                            $classMappings[$kvMatch[1]] = $kvMatch[2];
                        }
                    }

                    if (!empty($classMappings)) {
                        $allMappings["theme/{$blockName}"] = $classMappings;
                    }
                }
            }
        }

        return $allMappings;
    }
}
