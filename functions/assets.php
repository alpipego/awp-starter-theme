<?php

use Alpipego\AWP\Assets\Style;
use Alpipego\AWP\Assets\Script;
use Alpipego\AWP\Assets\AssetsCollection;

$min = !defined('SCRIPT_DEBUG') || !SCRIPT_DEBUG;

add_filter('awp/template/pattern/styles/prio', static function (string $priority, string $name, string $pattern) {
    if ($pattern === 'template' && $name === 'singular') {
        return 'defer';
    }

    return $priority;
}, 10, 3);

add_filter('awp/template/pattern/scripts/prio', static function (string $priority, string $name, string $pattern) {
    return match ($pattern === 'component' && $name) {
        // 'homepage-slider' => 'async',
        default => 'async'
    };
}, 10, 3);

add_filter('awp/template/pattern/scripts/action', static function (string $action, string $name, string $pattern) {
    if (is_admin()) {
        return 'add';
    }

    if (in_array(wp_get_environment_type(), ['local', 'development'])) {
        return $action;
    }

    return match (true) {
        $pattern === 'page' && $name === 'default',
            $pattern === 'component' && $name === 'header' => 'inline',
        default                                            => $action,
    };
}, 10, 3);

add_filter(
    'awp/template/pattern/styles/action',
    static function (string $action, string $name, string $pattern) use ($min) {
        if (is_admin()) {
            return 'add';
        }

        if (!$min) {
            return $action;
        }

        if (!in_array($pattern, ['page', 'template'], true)) {
            return 'inline';
        }

        return $action;
    },
    10,
    3,
);


add_filter('awp/template/pattern/styles/dep', static function (array $deps, string $name, string $pattern): array {
    if (!is_admin()) {
        $deps[] = 'app';
    }

    return $deps;
}, 10, 3);

add_filter('awp/assets/inline/filesize', static fn() => 200000);
add_filter('awp/assets/dir', static fn() => get_stylesheet_directory_uri() . '/dist/');
add_filter('awp/assets/path', static fn() => get_stylesheet_directory() . '/dist/');

add_filter('awp/template/pattern/path/styles', static fn() => get_stylesheet_directory() . '/dist/css');
add_filter('awp/template/pattern/path/styles_uri', static fn() => get_stylesheet_directory_uri() . '/dist/css');
add_filter('awp/template/pattern/path/scripts', static fn() => get_stylesheet_directory() . '/dist/js');
add_filter('awp/template/pattern/path/scripts_uri', static fn() => get_stylesheet_directory_uri() . '/dist/js');

// only add assets for blocks in the current view instead of stylesheet including all of them.
add_filter('should_load_separate_core_block_assets', static fn() => true);
remove_action('wp_footer', 'wp_enqueue_global_styles', 1);
/**
 * This code is from core. But it gets executed in the wp_footer of "classic" themes, therefore
 * the reset (!) styles are printed in the wp_footer instead of wp_head 🤯.
 */
add_action('wp_enqueue_scripts', static function () {
    /*
	 * If loading the CSS for each block separately, then load the theme.json CSS conditionally.
	 * This removes the CSS from the global-styles stylesheet and adds it to the inline CSS for each block.
	 * This filter must be registered before calling wp_get_global_stylesheet();
	 */
    add_filter('wp_theme_json_get_style_nodes', 'wp_filter_out_block_nodes');

    $stylesheet = wp_get_global_stylesheet();

    if (empty($stylesheet)) {
        return;
    }

    wp_register_style('global-styles', false);
    wp_add_inline_style('global-styles', $stylesheet);
    wp_enqueue_style('global-styles');

    // Add each block as an inline css.
    wp_add_global_styles_for_blocks();
});

add_action('wp_enqueue_scripts', static function () {
    $styles = new AssetsCollection();

    $styles->add((new Style('app')));

    foreach (['md' => '48rem', 'lg' => '64rem', 'xl' => '80rem'] as $name => $size) {
        $styles->add(
            (new Style('app-' . $name))
                ->deps(['app'])
                ->media('(width >= ' . $size . ')')
                ->condition(static function () use ($name) {
                    return file_exists(get_stylesheet_directory() . '/dist/css/app-' . $name . '.css');
                })
                ->prio('defer'),
        );
    }

//
//    $styles->inline((new Style('fonts-critical')));
//
//    $styles->add(
//        (new Style('fonts'))
//            ->prio('defer')
//    );

    $styles->run();
});

add_action('wp_enqueue_scripts', static function () use ($min) {
    $scripts = new AssetsCollection();

    $scripts->run();
});

add_action('enqueue_block_assets', static function () {
    if (!is_admin()) {
        return;
    }
    $theme = wp_get_theme();

    // specific editor styles
    $editorExtra = '/dist/css/editor-extra.css';
    wp_enqueue_style(
        $theme->get_template() . '-editor',
        get_template_directory_uri() . $editorExtra,
        [],
        filemtime(get_template_directory() . $editorExtra),
    );

    // editor script
    $editorScript = '/dist/js/editor.js';
    wp_enqueue_script(
        $theme->get_template() .'-editor',
        get_template_directory_uri() . $editorScript,
        ['wp-i18n', 'wp-hooks', 'wp-compose', 'wp-element', 'wp-data', 'wp-dom-ready'],
        filemtime(get_template_directory() . $editorScript),
    );
});

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
