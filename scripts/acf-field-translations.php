<?php

/**
 * Run as `wp eval-file --skip-plugins scripts/acf-field-translations.php`
 * or `ddev wp eval-file --skip-plugins scripts/acf-field-translations.php`
 * from the root of your project.
 *
 */
const TRANSLATABLE_FIELDS = ['title' => '', 'label' => '', 'description' => '', 'instructions' => '', 'placeholder' => '', 'choices' => [], 'message' => ''];
$translatable = [];
$themeDir     = dirname(__DIR__);
$template     = basename($themeDir);

$collectTranslatable = static function (array $item, array $context) use (&$translatable, $template) {
    $kvPairs = array_intersect_key($item, TRANSLATABLE_FIELDS);
    if (!empty($item['label'])) {
        $context[] = $item['label'];
    }
    $contextString              = implode(' → ', $context);
    $translatable[$item['key']] = array_filter(
        array_combine(
            array_keys($kvPairs),
            array_map(static function ($value) use ($contextString, $template) {
                if (empty($value)) {
                    return null;
                }

                if (is_array($value)) {
                    return array_map(static fn($choice) => "_x('$choice', 'ACF: $contextString → Choice', '$template')", $value);
                }

                return "_x('$value', 'ACF: $contextString', '$template')";
            }, $kvPairs),
        ),
    );
};

$recursiveSubFields = static function (array $field, array $context) use (&$recursiveSubFields, $collectTranslatable) {
    $collectTranslatable($field, $context);
    if (!empty($field['sub_fields'])) {
        if (!empty($field['label'])) {
            $context[] = $field['label'];
        }
        foreach ($field['sub_fields'] as $subField) {
            $recursiveSubFields($subField, $context);
        }
    }
};

foreach (glob($themeDir . '/assets/fields/*.json') as $groupFile) {
    $group = json_decode(file_get_contents($groupFile), true, 512, JSON_THROW_ON_ERROR);
    if (!isset($group['fields'])) {
        // options group
        continue;
    }
    $context   = [];
    $context[] = $group['title'];
    $collectTranslatable($group, $context);

    foreach ($group['fields'] as $field) {
        $context = [$group['title']];
        $recursiveSubFields($field, $context);
    }
}

// write the array to a PHP file that can be parsed
$outputFile = $themeDir . '/dist/translate/acf-field-translatables.php';
$fileHandle = fopen($outputFile, 'wb');

// Write the opening PHP tag
fwrite($fileHandle, "<?php\n\n");

// Write the array structure
fwrite($fileHandle, "return [\n");

// Iterate through the array and write its contents
foreach ($translatable as $key => $value) {
    fwrite($fileHandle, "    '$key' => [\n");
    foreach ($value as $subKey => $subValue) {
        if (is_array($subValue)) {
            fwrite($fileHandle, "        '$subKey' => [\n");
            foreach ($subValue as $subSubKey => $subSubValue) {
                fwrite($fileHandle, "            '$subSubKey' => $subSubValue,\n");
            }
            fwrite($fileHandle, "        ],\n");
        } else {
            fwrite($fileHandle, "        '$subKey' => $subValue,\n");
        }
    }
    fwrite($fileHandle, "    ],\n");
}

// Write the closing bracket for the array
fwrite($fileHandle, "];\n");

fclose($fileHandle);
