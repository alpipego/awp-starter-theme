<?php

declare(strict_types=1);

namespace Theme\Posts;

class PostTypeArchive
{
    public static function getSlug(string $postType): string
    {
        $page = self::getArchivePage($postType);
        if ($page === null) {
            return '';
        }

        return $page->post_name;
    }

    private static function getArchivePage(string $postType): ?\WP_Post
    {
        static $cache = [];
        if (array_key_exists($postType, $cache)) {
            return $cache[$postType];
        }
        $pages = new \WP_Query([
            'post_type'      => 'page',
            'posts_per_page' => 1,
            'meta_query'     => [
                [
                    'key'   => '_wp_page_template',
                    'value' => sprintf('%s/archive-%s.php', apply_filters('awp/template/pattern/path/templates', '_templates'), $postType),
                ],
            ],
        ]);

        return $cache[$postType] = $pages->post;
    }

    public static function getPost(string $postType): ?\WP_Post
    {
        return self::getArchivePage($postType);
    }
}
