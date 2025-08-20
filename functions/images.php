<?php

add_action('after_setup_theme', static function () {
    $sizes = [
        // 'mobile'              => [
        //     'width'  => 372,
        //     'height' => 372 * 300 / 335,
        //     'crop'   => true,
        // ],
        // 'default'             => [
        //     'width'  => 728,
        //     'height' => 728 * 300 / 335,
        //     'crop'   => true,
        // ],
        // 'square'              => [
        //     'width'  => 427,
        //     'height' => 427,
        //     'crop'   => true,
        // ],
        // 'square_md'           => [
        //     'width'  => 767,
        //     'height' => 767,
        //     'crop'   => true,
        // ],
    ];

    foreach ($sizes as $size => $settings) {
        for ($i = 1; $i < 3; $i++) {
            add_image_size("{$size}_{$i}x", $settings['width'] * $i, $settings['height'] * $i, $settings['crop']);
        }
    }
});
