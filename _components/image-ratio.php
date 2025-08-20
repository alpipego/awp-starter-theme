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

// @formatter:off
?>
<style>
    #<?= $id_attribute ?> {
        --ratio: <?= $ratio['small'] ?? 300/335 ?>;
    }
    @media (width >= 768px) {
        #<?= $id_attribute ?> {
            --ratio: <?= $ratio['medium'] ?? $ratio['small'] ?? $ratio ?>;
        }
    }
    <?php if (isset($ratio['large'])) : ?>
    @media (width >= 1024px) {
        #<?= $id_attribute ?> {
            --ratio: <?= $ratio['large'] ?>;
        }
    }
    <?php endif ?>
</style>
<?php // @formatter.on ?>
