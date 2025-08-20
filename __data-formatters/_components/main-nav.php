<?php

use Theme\Posts\MenuItems;
use Theme\Loaders\TemplateLoader;

$menuItems = (new MenuItems('main'))->getItems();

$callback = static function (&$menuItem) {
    $menuItem['template'] = TemplateLoader::pattern()->buildComponent(
        'main-nav-item',
        $menuItem,
    )->return();
};

// Recursive function to process menu items from the deepest children to the parent
$processMenuItems = static function (array &$menuItems, callable $callback) use (&$processMenuItems) {
    foreach ($menuItems as &$menuItem) {
        if (!empty($menuItem['children'])) {
            $processMenuItems($menuItem['children'], $callback);
        }
        $callback($menuItem);
    }
};

$processMenuItems($menuItems, $callback);

return [
    'menu_items' => array_column($menuItems, 'template'),
];
