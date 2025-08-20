<?php

/**
 * @var array{
 *     id: ?string,
 *     search_classes: ?(string[]),
 * } $data
 */

use Theme\Image;
use Theme\Loaders\TemplateLoader;
use Theme\Helpers\HtmlAttributes;
use Theme\Translations\TranslationManager;

return [
    'search_url'       => esc_url(home_url('/')),
    'input_attributes' => HtmlAttributes::parseAttributes([
        'id'          => 'header-search',
        'value'       => get_search_query(),
        'placeholder' => TranslationManager::get('search_placeholder'),
        'name'        => 's',
        'required'    => 'required',
    ]),
    'icon'             => TemplateLoader::pattern()->buildComponent('icon', [
        'svg' => Image::getSvgString('search'),
    ])->return(),
    'submit_text'      => TranslationManager::get('search_submit'),
    'label'            => TranslationManager::get('search_label'),
];
