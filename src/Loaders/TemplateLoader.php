<?php

declare(strict_types=1);

namespace Theme\Loaders;

use Theme\Logger;
use Alpipego\AWP\Template\Assets;
use Alpipego\AWP\Assets\AssetsCollection;
use Alpipego\AWP\Template\PatternFactory;
use Alpipego\AWP\Template\TemplateInterface;

class TemplateLoader
{
    public static function isCurrentPattern(string $name, string $pattern, string $file = null): bool
    {
        $file ??= debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['file'];

        return (bool)preg_match(sprintf('/_%ss\/%s\.php$/', $pattern, $name), $file);
    }

    /**
     * Iterate over array of data arrays, build patterns and implode them into string.
     */
    public static function patternArray(string $template, string $type, array $data): string
    {
        $builderFunction = 'build' . ucfirst($type);
        $result          = '';
        foreach ($data as $datum) {
            /** @var TemplateInterface $pattern */
            $pattern = self::pattern()->{$builderFunction}($template, $datum);
            $result  .= $pattern->return();
        }

        return $result;
    }

    public static function pattern(): PatternFactory
    {
        static $factory;
        if (is_null($factory)) {
            $factory = new PatternFactory([], new Assets(new AssetsCollection()));
        }

        return $factory;
    }

    public static function addHtmlClasses(string ...$addedClasses): void
    {
        add_filter('html_classes', static function (iterable $classes) use ($addedClasses) {
            return array_merge((array)$classes, $addedClasses);
        });
    }

    public static function addBodyClasses(string ...$addedClasses): void
    {
        add_filter('body_class', static function (iterable $classes) use ($addedClasses) {
            return array_merge((array)$classes, $addedClasses);
        });
    }

    public function filterHierarchy(): void
    {
        foreach (
            [
                '404',
                'archive',
                'author',
                'category',
                'tag',
                'taxonomy',
                'date',
                'embed',
                'home',
                'frontpage',
                'privacypolicy',
                'page',
                'paged',
                'search',
                'single',
                'singular',
                'attachment',
            ] as $type
        ) {
            add_filter($type . '_template_hierarchy', static function (array $templates) use ($type) {
                array_walk($templates, static function (&$value) {
                    if (str_starts_with($value, '_templates/')) {
                        return;
                    }
                    $value = '_templates/' . $value;
                });

                return array_unique($templates);
            });
        }
    }

    public function templateInclude($template): string
    {
        if (getenv('WP_ENV') === 'development') {
            $this->debugTemplate();
        }
        if (realpath($template) === realpath(get_index_template())) {
            $this->debugTemplate(true);

            return $template;
        }

        $template = self::pattern()->buildTemplate(
            basename($template, '.' . pathinfo($template, PATHINFO_EXTENSION))
        );

        self::pattern()->buildPage('default', $template->getData())->render([
            'main' => $template->return(),
        ]);

        return get_index_template();
    }

    private function debugTemplate(bool $print = false): void
    {
        $missingTemplates = [];
        $tag_templates    = [
            'is_embed'             => 'get_embed_template',
            'is_404'               => 'get_404_template',
            'is_search'            => 'get_search_template',
            'is_front_page'        => 'get_front_page_template',
            'is_home'              => 'get_home_template',
            'is_privacy_policy'    => 'get_privacy_policy_template',
            'is_post_type_archive' => 'get_post_type_archive_template',
            'is_tax'               => 'get_taxonomy_template',
            'is_attachment'        => 'get_attachment_template',
            'is_single'            => 'get_single_template',
            'is_page'              => 'get_page_template',
            'is_singular'          => 'get_singular_template',
            'is_category'          => 'get_category_template',
            'is_tag'               => 'get_tag_template',
            'is_author'            => 'get_author_template',
            'is_date'              => 'get_date_template',
            'is_archive'           => 'get_archive_template',
        ];
        foreach ($tag_templates as $tag => $template_function) {
            if ( ! $tag()) {
                continue;
            }
            $type = str_replace('is_', '', $tag);
            if ($type === 'front_page') {
                $type = 'frontpage';
            }
            add_filter("{$type}_template_hierarchy", static function ($templates) use (&$missingTemplates) {
                $missingTemplates = array_unique(array_merge($missingTemplates, $templates));

                return $templates;
            }, 999);
            $template_function();
        }
        if ($print) {
            ?>
            <!doctype html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Missing Template</title>
                <?php
                do_action('wp_head'); ?>
            </head>
            <body>
            <h1>Missing Template</h1>
            <code>
                <pre style="white-space: pre-wrap; word-wrap: break-word;"><?php
                    /** @noinspection ForgottenDebugOutputInspection */
                    var_dump($missingTemplates);
                    ?></pre>
            </code>

            <h2>Template Hierarchy</h2>
            <p>Note that the fallback to index.php is missing from this template loader.</p>
            <a target="_blank"
               href="https://i0.wp.com/developer.wordpress.org/files/2014/10/Screenshot-2019-01-23-00.20.04.png?ssl=1">
                <img width="1024" height="639"
                     src="https://i0.wp.com/developer.wordpress.org/files/2014/10/Screenshot-2019-01-23-00.20.04.png?resize=1024%2C639&amp;ssl=1"
                     alt="WordPress' Template Hierarchy" class="wp-image-"
                     srcset="https://i0.wp.com/developer.wordpress.org/files/2014/10/Screenshot-2019-01-23-00.20.04.png?resize=1024%2C639&amp;ssl=1 1024w, https://i0.wp.com/developer.wordpress.org/files/2014/10/Screenshot-2019-01-23-00.20.04.png?resize=300%2C187&amp;ssl=1 300w, https://i0.wp.com/developer.wordpress.org/files/2014/10/Screenshot-2019-01-23-00.20.04.png?resize=768%2C479&amp;ssl=1 768w, https://i0.wp.com/developer.wordpress.org/files/2014/10/Screenshot-2019-01-23-00.20.04.png?w=1685&amp;ssl=1 1685w"
                     sizes="(max-width: 1000px) 100vw, 1000px">
            </a>

            <?php Logger::console($missingTemplates); ?>
            </body>
            </html>
            <?php
        }
    }
}
