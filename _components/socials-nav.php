<?php
/**
 * @var array{
 *     attributes_string: string,
 *     icon: string,
 *     title: string,
 * } $menu_items
 */

?>
<ul class="flex gap-6">
    <?php foreach ($menu_items as $item) : ?>
        <li>
            <a <?= $item['attributes_string'] ?>>
                <span class="inline-block align-middle size-icon"><?= $item['icon'] ?></span>
                <span class="screen-reader-text"><?= $item['title'] ?></span>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
