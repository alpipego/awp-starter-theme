<?php
/**
 * @var array $data
 */

namespace Theme;

use Theme\Image;
use Theme\Posts\MenuItems;
use Theme\Helpers\HtmlAttributes;

$menuItems = (new MenuItems($data['location']))->getItems();
static $colors = [
    'facebook'  => 'hover:text-brands-facebook',
    'instagram' => 'hover:text-brands-instagram',
    'youtube'   => 'hover:text-brands-youtube',
    'pinterest' => 'hover:text-brands-pinterest',
    'whatsapp'  => 'hover:text-brands-whatsapp',
];
$hoverColor = static function (string $href) use ($colors) {
    foreach ($colors as $brand => $color) {
        if (str_contains($href, $brand)) {
            return $color;
        }
    }

    return 'hover:text-brand-color';
};
array_walk($menuItems, static function (&$menuItem) use ($hoverColor) {
    $menuItem['atts']['class']     = $hoverColor($menuItem['atts']['href']);
    $menuItem['atts']['title']     = $menuItem['title'];
    $menuItem['attributes_string'] = HtmlAttributes::parseAttributes(array_filter($menuItem['atts']));
    $icon                          = \Theme\Plugins\Acf::get_field('icon', $menuItem['raw']);
    if ($icon) {
        $menuItem['icon'] = Image::getSvgString(get_attached_file($icon));
    }
});

return [
    'menu_items' => $menuItems,
];
