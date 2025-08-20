<?php

use Theme\Image;
use Theme\Loaders\TemplateLoader;
use Theme\Translations\TranslationManager;

return [
    'home_url'          => get_bloginfo('url'),
    'home_label'        => TranslationManager::get(key: 'home_link_a11y_label'),
    'site_title'        => get_bloginfo('name'),
    'logo'              => get_theme_mod('custom_logo', Image::getSvgString('logo')),
    'open_icon'         => TemplateLoader::pattern()->buildComponent('icon', [
        'svg' => Image::getSvgString('menu'),
    ])->return(),
    'close_icon'        => TemplateLoader::pattern()->buildComponent('icon', [
        'svg' => Image::getSvgString('x-close'),
    ])->return(),
    'search_form'       => TemplateLoader::pattern()->buildComponent('search-form', [])->return(),
    'menu_button_label' => TranslationManager::get(key: 'toggle_menu_a11y_label'),
    'main_nav'          => TemplateLoader::pattern()->buildComponent('main-nav', [
        'location' => 'main',
    ])->return(),
    'socials'           => TemplateLoader::pattern()->buildComponent('socials-nav', [
        'location' => 'socials',
    ])->return(),
];
