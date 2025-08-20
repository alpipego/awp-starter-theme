<?php

namespace Theme\Plugins;

use GFAPI;
use RuntimeException;

class GravityForm
{
    static private array $formCache = [];

    public static function isValid(int $id)
    {
        if (!class_exists('GFAPI')) {
            throw new RuntimeException('GFAPI is not installed.');
        }

        $formObject = self::getForm($id);
        if (!$formObject) {
            throw new RuntimeException('Form not found.');
        }

        if (!$formObject['is_active'] || $formObject['is_trash']) {
            throw new RuntimeException('Form is not currently active.');
        }
    }

    public static function getForm(int $id): array
    {
        $key = 'form_' . $id;
        if (!array_key_exists($key, self::$formCache)) {
            self::$formCache[$key] = GFAPI::get_form($id) ?: [];
        }

        return self::$formCache[$key];
    }
}
