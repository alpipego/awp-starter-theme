<?php

use Theme\Loaders\TemplateLoader;

if (!class_exists(TemplateLoader::class)) {
    require_once __DIR__ . '/vendor/autoload.php';
}


add_action('after_setup_theme', static function () {
    add_theme_support('post-thumbnails');
    add_theme_support('disable-custom-gradients');
    add_theme_support('disable-custom-colors');
    add_theme_support('disable-layout-styles');
    // 'editor-color-palette'

    add_theme_support('editor-styles');
    add_editor_style([
        'assets/build/admin/css/editor.css',
    ]);

    add_theme_support('menus');

    load_theme_textdomain(wp_get_theme()->get('Text Domain'), get_stylesheet_directory() . '/languages');
});

add_action('wp_footer', static function () {
    if (wp_get_environment_type() !== 'development') {
        return;
    }

    ?>
    <script id="__bs_script__">//<![CDATA[
        (function () {
            try {
                var script = document.createElement('script');
                if ('async') {
                    script.async = true;
                }
                script.src = 'http://HOST:3000/browser-sync/browser-sync-client.js?v=2.29.3'.replace("HOST", location.hostname);
                if (document.body) {
                    document.body.appendChild(script);
                } else if (document.head) {
                    document.head.appendChild(script);
                }
            } catch (e) {
                console.error("Browsersync: could not append script tag", e);
            }
        })()
        //]]></script>
    <?php
});

// setup automatic field syncing and UI for acf
require_once __DIR__ . '/functions/acf.php';

add_action('init', static function () {
    // setup the translations/translatable strings
    require_once __DIR__ . '/functions/translations.php';
});

// handle scripts and styles, globally and for patterns (pages, templates, components etc.)
require_once __DIR__ . '/functions/assets.php';

// register menus and create fallback locations
require_once __DIR__ . '/functions/menus.php';

// load custom blocks
require_once __DIR__ . '/functions/blocks.php';

// add custom image sizes
require_once __DIR__ . '/functions/images.php';

// handle too-small old images
require_once __DIR__ . '/functions/maintain-image-ratio.php';

add_filter('awp/template/pattern/data', static fn() => '__data-formatters');

if (is_admin()) {
    return;
}

$template = new TemplateLoader();

add_action('after_setup_theme', [$template, 'filterHierarchy']);

add_filter('template_include', [$template, 'templateInclude']);
