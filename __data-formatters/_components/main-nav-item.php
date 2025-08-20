<?php

/**
 * @var array{
 *     id: int,
 *     title: string,
 *     current: bool,
 *     current_item_ancestor: bool,
 *     current_item_parent: bool,
 *     current: bool,
 *     children: array[],
 *     atts: array{string: mixed},
 *     raw: WP_Post
 * } $data
 */

use Theme\Helpers\HtmlAttributes;

return [
    'title'             => $data['title'],
    'current'           => $data['current'],
    'attributes_string' => HtmlAttributes::parseAttributes(array_filter($data['atts'])),
];
