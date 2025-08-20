<?php

/**
 * @var array $data
 */

use Theme\Posts\MenuItems;
use Theme\Helpers\HtmlAttributes;

$menuItems = (new MenuItems($data['location']))->getItems();
array_walk($menuItems, static function (&$menuItem) {
    $menuItem['attributes_string'] = HtmlAttributes::parseAttributes(array_filter($menuItem['atts']));
});

return [
    'menu_items' => $menuItems,
];
