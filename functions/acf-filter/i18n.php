<?php

add_action('after_setup_theme', static function () {
    $translationFile = __DIR__ . '/../build/acf-field-translatable.php';
    if (!file_exists($translationFile)) {
        return;
    }

    $translations = require $translationFile;

    add_filter('acf/load_field', static function (array $field) use ($translations) {
        if (
            !array_key_exists($field['key'], $translations)
            || !is_admin()
            || \Theme\Plugins\Acf::isFieldSettings()
        ) {
            return $field;
        }

        foreach ($translations[$field['key']] as $name => $translation) {
            $field[$name] = $translation;
        }

        return $field;
    });
});
