(function($){

    var megaMenuHideDelay;

    $('.primary-nav ul.menu >li').on('mouseenter',openMegaMenu);
    $('.primary-nav ul.menu >li, .mega-menu-item').on('mouseleave',initCloseMegaMenu);
    $('.mega-menu-item').on('mouseenter',enterMegaMenu);


    function enterMegaMenu(){
        clearTimeout(megaMenuHideDelay);
    }

    function openMegaMenu(target){
        var el = this;
        if(!el) {
            el = target
        }
        if(!el) {
            return false;
        }
        var page_id = $(el).data('page-id');

        if(!hasMegaMenu(page_id)){
            if(isMegaMenuOpen()){
                //No mega menu for this. Abort and init close
                initCloseMegaMenu();
            }
            return;
        }

        closeAllMegaMenusBut(page_id);
        $('#mega-menu-container .mega-menu-item[data-id="'+page_id+'"]').addClass('mega-menu-item-active');
        $('body').addClass('mega-menu-open');
        $('#menu-overlay').fadeIn(300);
        $(document).on('scroll', closeAllMegaMenus);
    } 

    function initCloseMegaMenu(){
        var el = this;
        clearTimeout(megaMenuHideDelay);
        megaMenuHideDelay = setTimeout(function(){
            closeAllMegaMenus();
        }, 300);
    }

    function closeMegaMenu(target){
        if(target) {
            el = target
        }else{
            var el = this;
        }

        if(!el) {
            return false;
        }
        var page_id = $(el).data('page-id');
        $('#mega-menu-container .mega-menu-item[data-id="'+page_id+'"]').removeClass('mega-menu-item-active');
        $('body').removeClass('mega-menu-open');
    }

    function closeAllMegaMenusBut(id){
        clearTimeout(megaMenuHideDelay);
        $('#mega-menu-container .mega-menu-item:not([data-id="'+id+'"])').removeClass('mega-menu-item-active');
    }

    function closeAllMegaMenus(){
        clearTimeout(megaMenuHideDelay);
        $('#mega-menu-container .mega-menu-item').removeClass('mega-menu-item-active');
        $('body').removeClass('mega-menu-open');
        $('#menu-overlay').fadeOut(300);
        $(document).off('scroll', closeAllMegaMenus);
    }

    function isMegaMenuOpen(){
        return $('body').hasClass('mega-menu-open');
    }

    function hasMegaMenu(page_id){
        return $('#mega-menu-container .mega-menu-item[data-id="'+page_id+'"]').length > 0
    }

})(jQuery);