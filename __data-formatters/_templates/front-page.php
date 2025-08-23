<?php

use Theme\Loaders\TemplateLoader;

return [
    'content' => TemplateLoader::pattern()->buildComponent('content', [])->return(),
];
