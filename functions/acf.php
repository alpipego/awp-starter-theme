<?php

// When including the PRO plugin, hide the ACF Updates menu
add_filter('acf/settings/show_updates', '__return_false', 100);

// load all fields from resources.
add_filter('acf/settings/load_json', function (array $paths): array {
    unset($paths[0]);
    $paths[] = __DIR__ . '/../dist/fields/';

    return $paths;
});

switch (wp_get_environment_type()) {
    // in local environment, save fields to resources.
    case 'local':
    case 'development':
        add_filter('acf/settings/save_json', static function () {
            return __DIR__ . '/../assets/fields/';
        });
        break;

    // hide ACF settings in staging if there are no registered field groups
    case 'staging':
        add_filter('acf/settings/show_admin', static function () {
            return ! empty((new \WP_Query(['post_type' => 'acf-field-group', 'fields' => 'ids']))->post_count);
        });
        break;

    // hide ACF settings in production
    case 'production':
    default:
        add_filter('acf/settings/show_admin', '__return_false');
}

// move custom fields above Yoast
add_filter('wpseo_metabox_prio', static function () {
    return 'low';
});

/**
 * Make sure empty values return null instead of false.
 *
 * @param mixed      $value  the value
 * @param int|string $postId the post id (or `option`, `user` etc).
 * @param array      $field  field definition
 *
 * @return mixed return anything, null for empty values
 */
add_filter('acf/format_value', static function ($value, $postId, $field) {
    if ($value !== false) {
        return $value;
    }

    // funnily enough true_false has a value of 0/1 at this point, while others are `false` instead of `null`
    if ($field['type'] === 'true_false') {
        return false;
    }

    return null;
}, 99, 3);

// include acf filters
add_action('acf/init', static function () {
    foreach (glob(__DIR__ . '/acf-filter/*.php') as $filter) {
        include_once $filter;
    }
});
