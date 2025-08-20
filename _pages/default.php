<?php
/**
 * @var string   $class        imploded array of classes for the HTML element
 * @var string   $favicon_path
 * @var string   $header       Header content
 * @var string   $main         Main content
 * @var string   $footer       Footer content
 * @var callable $body_classes Additional body classes `get_body_class( $css_class )`
 * @var array{
 *     href: string,
 *     as: string,
 * }[]           $preloads
 */

?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes() ?>>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php foreach ($preloads as $preloadAttributesString) : ?>
        <link rel="preload" <?= $preloadAttributesString ?> />
    <?php endforeach; ?>
    <script>document.documentElement.className = document.documentElement.className.replace("no-js", "js");</script>

    <title><?php wp_title() ?></title>

    <?php wp_head(); ?>
</head>
<body class="overflow-x-hidden bg-background text-dark font-medium <?= $body_classes() ?>">
<?php do_action('wp_body_open'); ?>

<div class="px-5 relative flex flex-col min-h-screen">
    <?= $header ?>
    <main class="max-w-wide mx-auto my-5 md:my-10 relative flex-grow">
        <?= $main ?>
    </main>
    <?= $footer ?>
</div>
<?php wp_footer(); ?>
</body>
</html>
