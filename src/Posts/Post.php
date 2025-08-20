<?php

declare(strict_types=1);

namespace Theme\Posts;

use WP_Post;

class Post
{
    private static $content;

    /**
     * Return the filtered content.
     * WordPress can either echo the filtered content `the_content` or return unfiltered content `get_the_content`.
     *
     * @param int|\WP_Post|null $post Optional. WP_Post instance or Post ID/object. Default null.
     */
    public static function get_the_content($post = null): string
    {
        return apply_filters('the_content', get_the_content(null, false, $post));
    }

    public static function get_thumbnail($post = null): array
    {
        $post = get_post($post);
        if (empty($post)) {
            return [];
        }
        $id = get_post_thumbnail_id($post);
        if (empty($id)) {
            return [];
        }

        return apply_filters('acf/format_value/type=image', $id, $post->ID, ['return_format' => '']);
    }

    /**
     * @param int|WP_Post|null $post
     * @param int|null         $sentences
     * @param int              $fallbackSentences
     *
     * @return string
     */
    public static function get_the_excerpt(int|WP_Post $post = null, int $sentences = null, int $fallbackSentences = 3): string
    {
        /** @var \WP_Post $post */
        $post = get_post($post);
        if (empty($post->post_excerpt)) {
            $excerpt = get_the_content('', false, $post);
        } else {
            $excerpt = get_the_excerpt($post);
        }

        $excerpt = strip_shortcodes($excerpt);
        $excerpt = strip_tags($excerpt);
        $excerpt = excerpt_remove_blocks($excerpt);
        $excerpt = str_replace(']]>', ']]&gt;', $excerpt);

        if (!empty($post->post_excerpt)) {
            return apply_filters('the_excerpt', $excerpt);
        }


        preg_match_all('/(.+?[.!?]\s)/', $excerpt, $excerptSentences);

        if (empty($excerptSentences[1])) {
            $excerptSentences[1][] = $excerpt;
        }
        // get first x elements
        $excerptSentences = array_slice($excerptSentences[1], 0, $sentences ?? $fallbackSentences);

        return apply_filters('the_excerpt', implode(' ', array_map('trim', $excerptSentences)));
    }
}
