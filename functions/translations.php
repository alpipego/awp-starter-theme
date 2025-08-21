<?php

namespace Theme;

use Theme\Translations\Translatable;
use Theme\Translations\TranslationStorage;
use Theme\Translations\TranslationManager;

(static function () {
    $wp        = function_exists('wp');
    $cacheFile = __DIR__ . '/../dist/translate/theme-translatable.php';
    if ($wp && wp_get_environment_type() === 'production' && file_exists($cacheFile)) {
        // env is production and the cache file exists
        $storage = require $cacheFile;
    } else {
        $storage = new TranslationStorage (
            translations: [
                new Translatable(string: 'Back to Home', key: 'home_link_a11y_label'),
                new Translatable(string: 'Toggle Main Menu', key: 'toggle_menu_a11y_label'),
                new Translatable(string: 'Search…', key: 'search_placeholder', context: 'Placeholder for main nav search input.'),
                new Translatable(string: 'Search', key: 'search_submit', context: 'Submit button text for main nav search.'),
                new Translatable(string: 'Search', key: 'search_label', context: 'Form label for main nav search.'),
            ],
            domain      : basename(dirname(__DIR__)),
        );
    }

    // only load the translation manager when in WP context
    if ($wp) {
        new TranslationManager($storage);
    }
})();
