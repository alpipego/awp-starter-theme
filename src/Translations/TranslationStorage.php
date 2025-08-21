<?php

namespace Theme\Translations;

class TranslationStorage
{
    private static array  $translations = [];
    private static string $domain;

    public function __construct(array $translations, string $domain)
    {
        self::$translations = $translations;
        self::$domain       = $domain;
    }

    public static function set(Translatable $translation): void
    {
        self::$translations[$translation->key] = [$translation];
    }

    public static function save(string $outputDir)
    {
        // write the array to a PHP file that can be parsed
        $outputFile = $outputDir . '/theme-translatable-strings.php';
        $fileHandle = fopen($outputFile, 'wb');

        // Write the opening PHP tag
        fwrite($fileHandle, "<?php\n\n");

        // Write the array structure
        fwrite($fileHandle, "return [\n");

        // Iterate through the array and write its contents
        foreach (self::dump() as $key => $value) {
            fwrite($fileHandle, "    '$key' => $value,\n");
        }

        // Write the closing bracket for the array
        fwrite($fileHandle, "];\n");

        fclose($fileHandle);
    }

    public static function dump(): array
    {
        $translatable = [];
        array_map(static function (Translatable $translation) use (&$translatable) {
            $translatable[$translation->key] = $translation->resolve(self::$domain);
        }, self::$translations);

        return $translatable;
    }

    public function eval()
    {
        $translatable = self::dump();

        array_walk($translatable, static function (string &$value) {
            $value = eval('return '.$value. ';');
        });

        return $translatable;
    }
}
