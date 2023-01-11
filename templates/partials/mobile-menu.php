<div id="mobile-menu" style="display: none;">
    <div class="close-menu">
        <div class="icon">
            <?= pb_get_icon_svg('close-primary', 'icon-xms'); ?>
        </div>
    </div>
    <div class="inner">
        <div class="menu-content">
            <div class="h3 menu-title"><?= __('Menu', 'pb') ?></div>
            <?php
             wp_nav_menu( array('theme_location' => 'primary', 'walker' => new PB_Walker()) );
            ?>
        </div>
        <?php if(defined('ICL_LANGUAGE_CODE')){ ?>
            <div class="lang-switcher-action">
                <div class="action-name">
                    <img class="flag" src="<?= get_template_directory_uri() ?>/img/flags/<?= ICL_LANGUAGE_CODE ?>.png" alt="Flag <?= ICL_LANGUAGE_CODE ?>" loading="lazy">
                    <span><?= __('Choose language', 'pb'); ?></span>
                    <?= pb_get_icon('chevron-right-text', [6,9]); ?>
                </div>
                <div class="lang-switcher-container" style="display: none;">
                    <?php pb_lang_switcher() ?>
                </div>
            </div>
        <?php } ?>
    </div>

</div>