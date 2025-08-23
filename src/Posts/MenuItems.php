<?php

namespace Theme\Posts;

use WP_Post;

class MenuItems
{
    /**
     * @var array {
     *     id: int,
     *     title: string,
     *     current: bool,
     *     current_item_ancestor: bool,
     *     current_item_parent: bool,
     *     children: array,
     *     atts: array{string: mixed},
     *     raw: WP_Post,
     *     }[] $items
     */
    private array $items;

    public function __construct(string $location)
    {
        $locations = get_nav_menu_locations();
        if (!array_key_exists($location, $locations)) {
            $this->items = [];

            return;
        }
        $menu = wp_get_nav_menu_object($locations[$location]);
        if (empty($menu)) {
            $this->items = [];

            return;
        }
        $items = wp_get_nav_menu_items($menu->term_id, ['update_post_term_cache' => false]);
        // instead of throwing an exception, short-circuit getting the items and default to the empty array
        if ($items === false) {
            $this->items = [];

            return;
        }
        _wp_menu_item_classes_by_context($items);
        $sorted_menu_items = [];
        foreach ($items as $menu_item) {
            if ((string)$menu_item->ID === (string)$menu_item->menu_item_parent) {
                $menu_item->menu_item_parent = 0;
            }

            $sorted_menu_items[$menu_item->menu_order] = $menu_item;
        }

        $this->items = $this->buildTree($sorted_menu_items, 0, 0);
    }

    private function buildTree(array $items, int $parentId, int $level): array
    {
        $branch = [];
        $level++;
        foreach ($items as $item) {
            if ((int)$item->menu_item_parent === $parentId) {
                $children = $this->buildTree($items, $item->ID, $level);
                if ($children) {
                    $item->children = $children;
                }
                $branch[] = $this->parseItem($item, $level);
            }
        }

        return $branch;
    }

    private function parseItem(WP_Post $item, int $level): array
    {
        $atts           = [];
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        if ('_blank' === $item->target && empty($item->xfn)) {
            $atts['rel'] = 'noopener';
        } else {
            $atts['rel'] = $item->xfn;
        }

        if (!empty($item->url)) {
            if (get_privacy_policy_url() === $item->url) {
                $atts['rel'] = empty($atts['rel']) ? 'privacy-policy' : $atts['rel'] . ' privacy-policy';
            }

            $atts['href'] = $item->url;
        } else {
            $atts['href'] = '';
        }

        $title = apply_filters('the_title', $item->title, $item->ID);
        $title = apply_filters('nav_menu_item_title', $title, $item, [], $item->menu_item_depth);

        $atts['aria-current'] = $item->current ? 'page' : '';

        return [
            'id'                    => $item->ID,
            'title'                 => $title,
            'current'               => $item->current,
            'current_item_ancestor' => $item->current_item_ancestor,
            'current_item_parent'   => $item->current_item_parent,
            'level'                 => $level,
            'children'              => $item->children ?? [],
            'atts'                  => $atts,
            'raw'                   => $item,
        ];
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
