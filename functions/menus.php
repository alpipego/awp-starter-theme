<?php

add_action('after_setup_theme', static function () {
    register_nav_menus([
        'main'           => 'Hauptmenü',
        'footer'         => 'Hauptmenü Footer',
        'socials'        => 'Socials',
        'footer_socials' => 'Socials Footer',
        'legal'          => 'Datenschutz, Impressum, etc.',
    ]);
});
