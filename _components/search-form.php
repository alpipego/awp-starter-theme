<?php
/**
 * @var string $search_url
 * @var string $label
 * @var string $search_field
 * @var string $submit_text
 * @var string $input_attributes
 * @var string $icon
 */

?>
<form role="search" aria-label="<?= $label ?>" method="get" class="w-full" action="<?= $search_url ?>">
    <label class="relative block h-12 w-full group">
        <span class="screen-reader-text"><?= $label ?></span>

        <span class="absolute top-0 right-0 text-dark group-hover:text-brand-color-dark peer-focus:text-dark/30"><?= $icon ?></span>

        <input
            class="h-full w-full cursor-pointer rounded border border-gray-400 pr-12 pl-4 placeholder-gray-300 peer lg:group-focus-within:w-auto lg:group-focus-within:pr-12 lg:group-focus-within:pl-4 lg:group-focus-within:opacity-100 lg:w-12 lg:px-0 lg:opacity-0"
            <?= $input_attributes ?>
        />

        <input type="submit" class="absolute bg-transparent border-0 border-transparent min-w-0 top-0 right-0 z-10 h-full w-12 cursor-pointer lg:group-focus-within:block lg:hidden" value="" title="<?= $submit_text ?>" aria-label="<?= $submit_text ?>" />
    </label>
</form>
