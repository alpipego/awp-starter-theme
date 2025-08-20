<?php

use Theme\Helpers\HtmlAttributes;

/**
 * @var array{
 *     type: ?string,
 *     placeholder: ?string,
 *     id: ?string,
 *     name: ?string,
 *     value: ?string|int,
 *     attributes: array<string, mixed>,
 *     classes: string[],
 * } $data
 */

$attributes = array_merge([
    'type'        => $data['type'] ?? 'text',
    'placeholder' => $data['placeholder'] ?? '',
    'id'          => $data['id'] ?? '',
    'name'        => $data['name'] ?? '',
    'value'       => $data['value'] ?? '',
], $data['attributes'] ?? []);

return [
    'attributes' => HtmlAttributes::parseAttributes($attributes),
    'classes'    => implode(' ', $data['classes'] ?? []),
    ...$attributes,
];
