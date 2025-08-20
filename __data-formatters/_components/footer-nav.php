<?php

use Theme\Posts\MenuItems;
use Theme\Helpers\HtmlAttributes;

$menuItems = (new MenuItems('footer'))->getItems();
array_walk($menuItems, static function (&$menuItem) {
    $menuItem['attributes_string'] = HtmlAttributes::parseAttributes(array_filter($menuItem['atts']));

    array_walk($menuItem['children'], static function (&$menuItem) {
        $menuItem['attributes_string'] = HtmlAttributes::parseAttributes(array_filter($menuItem['atts']));
    });
});

return [
    'menu_items' => $menuItems,
];
