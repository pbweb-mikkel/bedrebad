<div id="search-form-menu">
    <div class="close-menu">
        <div class="icon">
            <?= pb_get_icon('close-w', 'icon-xms'); ?>
        </div>
        <div class="action-name">Luk</div>
    </div>
    <div class="inner">
        <form id="search-form" action="/" method="get">
            <input type="search" name="s" placeholder="<?= __('Søg...', 'pb'); ?>">
            <button type="submit" aria-label="Search"><span class="pb-icon-search icon-xs"></button>
        </form>
        <div id="search-results">

        </div>
    </div>
</div>