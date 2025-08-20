<?php

/**
 * @var array[] $menu_items
 */

?>
<nav class="my-9 w-full md:w-auto md:grow">
    <ul class="grid grid-cols-2 gap-9 md:grid-cols-4">
        <?php foreach ($menu_items as $parent_item) : ?>
            <li>
                <a class="inline-block font-mono text-xl text-brand-color hover:underline leading-6 mb-2" <?= $parent_item['attributes_string'] ?>>
                    <?= $parent_item['title'] ?>
                </a>

                <?php if ($parent_item['children']) : ?>
                    <ul>
                        <?php foreach ($parent_item['children'] as $child) : ?>
                            <li class="my-4">
                                <a <?= $child['attributes_string'] ?> class="hover:underline">
                                    <?= $child['title'] ?>
                                </a>
                            </li>
                        <?php endforeach ?>
                    </ul>
                <?php endif ?>
            </li>
        <?php endforeach ?>
    </ul>
</nav>
