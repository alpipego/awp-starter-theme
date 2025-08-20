<?php

use Theme\Helpers\AdManager;
use Theme\Loaders\TemplateLoader;
use Theme\Helpers\HtmlAttributes;

$themeUrl = get_stylesheet_directory_uri();

return [
    'body_classes' => (static fn() => esc_attr(implode(' ', get_body_class()))),
    'preloads'     => array_map(
        static fn(array $preload) => HtmlAttributes::parseAttributes($preload),
        apply_filters('theme/preload', [
            // [
                // 'href'        => $themeUrl . '/assets/build/fonts/...',
                // 'as'          => 'font',
                // 'crossOrigin' => 'anonymous',
            // ],
        ]),
    ),
    'header'       => TemplateLoader::pattern()->buildComponent('header')->return(),
    'main'         => '',
    'footer'       => TemplateLoader::pattern()->buildComponent('footer')->return(),
];
