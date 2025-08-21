<?php

use Theme\Translations\TranslationStorage;

$themeDir  = dirname(__DIR__);
$textdomain = basename($themeDir);
$outputDir = $themeDir . '/dist/translate';
if (!is_dir($outputDir) && !mkdir($outputDir, 0755, true) && !is_dir($outputDir)) {
    throw new \RuntimeException(sprintf('Directory "%s" was not created', $outputDir));
}
require_once $themeDir . '/vendor/autoload.php';
require_once $themeDir . '/functions/translations.php';

TranslationStorage::save($outputDir);

require_once __DIR__ . '/acf-field-translations.php';

shell_exec("wp i18n make-pot . languages/{$textdomain}.pot");
