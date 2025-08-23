<?php

namespace Theme;

class Image
{
    public static array  $defaultSizes = [
        '(max-width: 412px)'                        => 'mobile',
        '(min-width: 413px) and (max-width: 767px)' => 'default',
    ];
    private static array $svgCache     = [];

    public static function parse(?int $id, string|array $smallestSize = 'mobile_1x'): array
    {
        $image = [
            'id'           => $id,
            'src'          => '',
            'width'        => 0,
            'height'       => 0,
            'ratio'        => null,
            'lazy'         => true,
            'sizes'        => [],
            'title'        => '',
            'alt'          => '',
            'id_attribute' => 'image_' . md5(uniqid(microtime(true) . mt_rand(), true)),
        ];

        if (!$id) {
            if ($id === 0) {
                // base64 encoded version of <svg width="1" height="1" xmlns="http://www.w3.org/2000/svg"><rect width="1" height="1" fill="rgba(142,127,107,.2)"/></svg>
                $image['src'] = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMSIgaGVpZ2h0PSIxIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxyZWN0IHdpZHRoPSIxIiBoZWlnaHQ9IjEiIGZpbGw9InJnYmEoMTQyLDEyNywxMDcsLjIpIi8+PC9zdmc+';
            }

            return $image;
        }

        $attachment     = wp_get_attachment_image_src($id, $smallestSize);
        $image['title'] = get_the_title($id);
        $image['alt']   = get_post_meta($id, '_wp_attachment_image_alt', true);

        if (empty($attachment) || empty($attachment[0]) || empty($attachment[1]) || empty($attachment[2])) {
            if (get_post_mime_type($id) === 'image/svg+xml') {
                $uploads         = wp_get_upload_dir();
                $image['src']    = wp_get_attachment_url($id);
                $img             = str_replace($uploads['baseurl'], $uploads['basedir'], wp_get_attachment_url($id));
                $svgfile         = simplexml_load_string(file_get_contents($img));
                $image['width']  = (int)$svgfile['width'];
                $image['height'] = (int)$svgfile['height'];
                if (empty($image['width']) || empty($image['height'])) {
                    $image['width']  = 3000;
                    $image['height'] = 1500;
                }
                $image['ratio'] = $image['width'] / $image['height'];
            }

            return $image;
        }

        $intrinsicSizes = wp_get_attachment_image_src($id, 'full');

        return array_merge($image, [
            'src'    => $attachment[0],
            'width'  => $attachment[1],
            'height' => $attachment[2],
            'ratio'  => $attachment['ratio'] ?? $intrinsicSizes[1] / $intrinsicSizes[2],
        ]);
    }

    public static function getSizesSrc(int $id, string $size): string
    {
        if (empty($id)) {
            return '';
        }
        $sizes = array_filter(self::getSizes($id, $size));
        if (empty($sizes)) {
            return '';
        }

        array_walk($sizes, static function (&$value, $key) {
            $value = sprintf('%s %s', $value[0], $key);
        });

        return implode(', ', $sizes);
    }

    public static function getSizes(int $id, string $size): array
    {
        $sizes = [];
        for ($i = 1; $i < 3; $i++) {
            $sizes["{$i}x"] = wp_get_attachment_image_src($id, "{$size}_{$i}x");
        }

        return $sizes;
    }

    public static function sizesArray(array $sizes)
    {
        return array_merge(self::$defaultSizes, $sizes);
    }

    public static function getSvgStringFromUpload(int $icon): ?string
    {
        $file = get_attached_file($icon);

        return self::getSvgString($file, false);
    }

    public static function getSvgString(string $file, bool $autoprefix = true): ?string
    {
        if ($autoprefix && !str_starts_with($file, get_stylesheet_directory() . '/dist/img/')) {
            $file = get_stylesheet_directory() . '/dist/img/' . $file;
        }

        if (!str_ends_with($file, '.svg')) {
            $file .= '.svg';
        }

        if (isset(self::$svgCache[$file])) {
            return self::$svgCache[$file];
        }

        if (!file_exists($file) || mime_content_type($file) !== 'image/svg+xml') {
            return null;
        }

        $svg                   = file_get_contents($file);
        self::$svgCache[$file] = $svg;

        return $svg;
    }
}
