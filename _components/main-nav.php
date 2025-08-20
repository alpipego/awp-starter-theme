<?php
/**
 * @var array{
 *     template: string[]
 * } $menu_items
 */

?>
<ul class="lg:flex lg:justify-center lg:gap-10 lg:mt-8 lg:border-t lg:border-black/5">
    <?php foreach ($menu_items as $menu_item) : ?>
        <?= $menu_item ?>
    <?php endforeach; ?>
</ul>
