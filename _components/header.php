<?php
/**
 * @var string $site_title
 * @var string $home_url
 * @var string $home_label
 * @var string $logo
 *
 * @var string $open_icon
 * @var string $close_icon
 * @var string $menu_button_label
 *
 * @var string $search_form
 *
 * @var string $main_nav _components/main-nav.php
 * @var string $socials  _components/socials-nav.php
 */

?>
<header class="w-full mb-5 flex flex-wrap pb-5 lg:my-8 lg:pb-0 bg-zink-200">
    <a href="<?= $home_url ?>" class="h-[38px] w-[231px]" aria-label="<?= $home_label ?>">
        <?= $logo ?>
    </a>

    <button id="open-close-menu" data-open="" class="ml-auto lg:hidden" aria-label="<?= $menu_button_label ?>">
        <span id="menu-open" class="block"><?= $open_icon ?></span>
        <span id="menu-close" class="hidden"><?= $close_icon ?></span>
    </button>

    <div
            id="shelf"
            class="hidden lg:flex flex-col lg:flex-row flex-wrap w-[calc(100%+2.5rem)] lg:w-full -left-5 lg:left-0 px-5 lg:px-0 content-start lg:justify-end absolute lg:static bg-white min-h-[calc(100vh-156px)] lg:min-h-[unset] top-[88px] z-10"
    >
        <div class="mb-4 lg:order-2 lg:mb-0 lg:h-12">
            <?= $search_form ?>
        </div>

        <nav class="w-full lg:order-3" id="main-menu">
            <?= $main_nav ?>
        </nav>

        <nav class="mt-auto mb-9 flex w-full lg:mr-30 lg:order-1 lg:my-0 lg:mr-4 lg:w-auto lg:items-center">
            <?= $socials ?>
        </nav>
    </div>
</header>
