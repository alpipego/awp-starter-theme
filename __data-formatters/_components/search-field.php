<?php

use Theme\Loaders\TemplateLoader;

/**
 * @var array{
 *     value: string,
 *     label: string,
 *     name: string,
 *     id: string,
 *     placeholder: string,
 *     submit_text: ?string,
 *     search_classes: ?(string[])
 * } $data
 */

return [
    'icon'        => TemplateLoader::pattern()->buildComponent('icon', [
        'svg' => Image::getSvgString('search'),
    ])->return(),
    'input'       => TemplateLoader::pattern()->buildComponent('input', [
        'type'        => 'search',
        'name'        => $data['name'],
        'id'          => $data['id'] ?? null,
        'value'       => $data['value'],
        'placeholder' => $data['placeholder'],
        'attributes'  => [
            'required' => 'required',
        ],
        'classes'     => array_merge([
            'pr-12',
        ], $data['classes'] ?? []),
    ]),
    'submit_text' => $data['submit_text'],
    'label'       => $data['label'],
];
