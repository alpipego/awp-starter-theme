<?php
/**
 * @var string $icon        SVG string
 * @var string $label
 * @var string $input       _components/input.php
 * @var string $submit_text _components/input.php
 */

?>
<label class="relative block h-12 w-full">
    <span class="screen-reader-text">
        <?= $label ?>
    </span>

    <?= $input ?>

    <span class="absolute top-0 right-0 text-dark hover:text-brand-color focus-within:text-dark/30">
        <?= $icon ?>
    </span>

    <input type="submit" class="absolute bg-transparent border-0 border-transparent min-w-0 top-0 right-0 z-10 h-full w-12 cursor-pointer" value="" title="<?= $submit_text ?>" aria-label="<?= $submit_text ?>" />
</label>
