<?php

use Theme\Image;
use Theme\Loaders\TemplateLoader;

return [
    'home_url'   => get_bloginfo('url'),
    'site_title' => get_bloginfo('name'),
    'logo'       => get_theme_mod('custom_logo', Image::getSvgString('logo')),
    'nav'        => TemplateLoader::pattern()->buildComponent('footer-nav', [
        'location' => 'footer',
    ])->return(),
    'socials'    => TemplateLoader::pattern()->buildComponent('socials-nav', [
        'location' => 'footer_socials',
    ])->return(),
    'legal'      => TemplateLoader::pattern()->buildComponent('legal-nav', [
        'location' => 'legal',
    ])->return(),
];
