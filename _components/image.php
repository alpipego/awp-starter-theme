<?php

/**
 * @var array{
 *      src: string,
 *      alt: string,
 *      parsed_classes: string,
 *      title: string,
 *      lazy: bool,
 *      width: int,
 *      height: int,
 *      ratio: numeric,
 *      id_attribute: string,
 *      sizes: array{string: string},
 *      caption: string,
 * }           $image
 * @var string $picture _components/image-picture-tag
 * @var string $ratio   _components/image-ratio
 * @var string $caption
 */

// return if we don't have an image
if (!$image['src']) {
    return;
}
echo $ratio;
?>

<figure id="<?= $image['id_attribute'] ?>" class="relative w-full aspect-[var(--ratio)] mt-0 mb-5 has-[figcaption]:mb-11">
    <?= $picture ?>

    <?php if ($image['caption']) : ?>
        <figcaption class="relative top-full font-mono text-right text-[15px] pt-1 leading-5">
            <?= $image['caption'] ?>
        </figcaption>
    <?php endif ?>
</figure>
