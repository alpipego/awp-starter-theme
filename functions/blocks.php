<?php

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
