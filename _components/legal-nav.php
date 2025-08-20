<?php

/**
 * @var array[] $menu_items
 */

?>
<nav class="flex flex-wrap gap-6 font-normal lg:gap-16">
    <?php foreach ($menu_items as $menu_item) : ?>
        <a <?= $menu_item['attributes_string'] ?> class="hover:underline">
            <?= $menu_item['title'] ?>
        </a>
    <?php endforeach; ?>
</nav>
