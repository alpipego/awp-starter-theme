<?php

namespace Theme\Posts;

class PostsCollector
{
    private static $collector = [];

    /**
     * @param int[] $ids
     *
     * @return void
     */
    public static function add(array $ids): void
    {
        self::$collector = array_unique(array_values(array_merge(self::$collector, array_filter(array_map('intval', $ids)))));
    }

    /**
     * @return int[]
     */
    public static function get(): array
    {
        return self::$collector;
    }
}
