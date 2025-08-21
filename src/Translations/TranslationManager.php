<?php

namespace Theme\Translations;

class TranslationManager
{
    private static array $translations = [];

    public function __construct(TranslationStorage $storage)
    {
        self::$translations = $storage->eval();
    }

    public static function get(?string $key = null, ?string $string = null, bool $caseSensitive = true)
    {
        if (empty($key) && empty($string)) {
            throw new \RuntimeException('You must provide either a key or a string to find a translation.');
        }

        if ($key) {
            if (empty(self::$translations[$key])) {
                throw new \RuntimeException('No translation found for key: ' . $key);
            }

            return self::$translations[$key];
        }

        if ($caseSensitive) {
            $return = array_search($string, self::$translations, true);
            if (empty($return)) {
                throw new \RuntimeException('No translation found for string: ' . $string);
            }

            return $return;
        }

        foreach (self::$translations as $value) {
            if (strcasecmp($value, $string) === 0) {
                return $value;
            }
        }

        throw new \RuntimeException('No translation found for string: ' . $string);
    }
}
