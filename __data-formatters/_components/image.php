<?php

use Theme\Image;
use Theme\Loaders\TemplateLoader;

/**
 * @var array{
 *     id: int,
 *     sizes: array{string: string},
 *     ratio: ?float,
 *     no_fallback: ?bool,
 *     smallest_size: ?(string|array{int, int})
 * } $data
 */

$image            = Image::parse($data['id'], $data['smallest_size'] ?? 'mobile_1x');
$image['ratio']   = $data['ratio'] ?? $image['ratio'];
$image['lazy']    = $data['lazy'] ?? $image['lazy'];
$image['caption'] = ($data['caption'] ?? false) ? get_post_field('post_excerpt', $data['id']) : '';

$sizes          = $data['sizes'] ?? [];
$image['sizes'] = array_filter(array_combine(
    array_keys($sizes),
    array_map(static fn(string $size) => Image::getSizesSrc($image['id'], $size), $sizes),
));

return [
    'image'   => $image,
    'ratio'   => TemplateLoader::pattern()->buildComponent('image-ratio', $image)->return(),
    'picture' => TemplateLoader::pattern()->buildComponent('image-picture-tag', array_merge($image, [
        'parsed_classes' => $data['parsed_classes'] ?? implode(' ', $data['classes'] ?? []),
    ]))->return(),
];
