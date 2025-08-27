<?php

use Theme\Loaders\BlockTemplateLoader;

// Initialize the inner block class system
add_filter('render_block', [BlockTemplateLoader::class, 'applyInnerBlockClasses'], 10, 2);

/**
 * Register all ACF-enabled blocks with block.json
 */

add_action('init', static function (): void {
    foreach (glob(__DIR__ . '/../blocks/*') as $dir) {
        if (!is_dir($dir)) {
            continue;
        }
        register_block_type($dir);
    }
});

add_action('after_setup_theme', static function () {
    $theme = wp_get_theme(get_stylesheet());

    add_filter('block_categories_all', static fn(array $categories) => array_merge(
        [
            [
                'slug'  => 'theme',
                'title' => $theme->get('ThemeName'),
            ],
        ],
        $categories,
    ));
});

/**
 * Pass inner block class mappings to editor JavaScript
 */
add_action('enqueue_block_assets', static function () {
    if (!is_admin()) {
        return;
    }

    wp_localize_script(
        wp_get_theme()->get_template() . '-editor',
        'themeInnerBlockClasses',
        BlockTemplateLoader::getAllInnerBlockClassMappings(),
    );
});
