<?php
/**
 * @var string                $src
 * @var string                $alt
 * @var string                $parsed_classes
 * @var string                $title
 * @var bool                  $lazy
 * @var int                   $width
 * @var int                   $height
 * @var numeric               $ratio
 * @var string                $id_attribute
 * @var array{string: string} $sizes
 */

?>
<picture class="my-0">
    <?php foreach ($sizes as $media => $size) : ?>
        <source media="<?= $media ?>" srcset="<?= $size ?>">
    <?php endforeach; ?>
    <img
        src="<?= $src ?>"
        alt="<?= $alt ?>"
        class="rounded object-cover absolute w-full h-full aspect-[var(--ratio)] <?= $parsed_classes ?>"
        <?php if ($title) : ?>
            title="<?= $title ?>"
        <?php endif ?>
        loading="<?= $lazy ? 'lazy' : 'eager' ?>"
        width="<?= $width ?>"
        height="<?= $height ?>"
    >
</picture>
