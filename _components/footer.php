<?php
/**
 * @var string $site_title
 * @var string $home_url
 * @var string $logo    logo SVG
 * @var string $nav     _components/footer-nav.php
 * @var string $socials _components/socials.php
 * @var string $legal   _components/legal-nav.php
 */

?>

<footer class="-mx-5 mt-16 block p-8 pb-16 bg-zinc-200">
    <div class="mx-auto max-w-[1136px]">
        <a href="<?= $home_url ?>" class="block h-[38px] w-[231px]">
            <?= $logo ?>
        </a>

        <div class="flex flex-wrap md:flex-nowrap mb-9 pb-9 border-b border-white/10">
            <?= $nav ?>

            <nav class="w-full md:w-auto md:*:flex-col md:mt-9">
                <?= $socials ?>
            </nav>
        </div>

        <?= $legal ?>
    </div>
</footer>
