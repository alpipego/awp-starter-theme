<?php

/**
 * @var array {
 *     post: ?(int|WP_Post),
 * } $data
 */

use Theme\Posts\Post;

return [
    'content' => Post::get_the_content($data['post'] ?? null),
];
