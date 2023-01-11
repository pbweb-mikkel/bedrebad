(function($){

    var resized;
    $(window).resize(function(){
        clearTimeout(resized);
        resized = setTimeout(resizeEnd, 100);
    });

    function resizeEnd(){
        calcPopupPosition();
    }

    $('.popup-close').on('click', hidePopup);
    $('.pb-popup').on('click', function(e){
        if($(e.target).closest('.inner').length > 0){
        }else{
            hidePopup();
        }
    });

    $('.pb-popup-overlay').on('click', hidePopup);

    $('a[href^="#popup-"]').on('click', showPopupManual);

    setTimeout(showPopup, 3000);


    function showPopup(){

        var popup = $('.pb-popup.open-automatically');

        if(!popup){
            return false;
        }

        var id = popup.data('id');

        if(getCookie('hidePopup-'+id) == 'true'){
            return false;
        }

        if(popup.hasClass('hide-mobile') && $(window).innerWidth() < 768){
            return false;
        }

        popup.fadeIn(400, function(){
            calcPopupPosition();
        });

    }

    function showPopupManual(e){
        e.preventDefault();
        var target = $(e.target);
        if(!target.is('a')){
            target = target.closest('a');
        }
        var href = target.attr('href');
        if(!href){
            return false;
        }
        var id = href.replace('#popup-', '');
        var popup = $('.pb-popup[data-id="'+id+'"]');
        popup.fadeIn(400, function(){
            calcPopupPosition();
        });

    }

    function calcPopupPosition(){

        var popups = $('.pb-popup');

        if(!popups.length){
            return false;
        }

        popups.each(function(){
            var inner = $('.inner', this);
            var wh = $(window).height();
            var h = inner.outerHeight();
            var top = (wh - h) / 2;

            if(top < 0){
                top = 0;
            }
            inner.css('top', top);
        });

    }

    function hidePopup(){
        var popup = $('.pb-popup');
        popup.fadeOut();
        var id = popup.data('id');
        setCookie('hidePopup-'+id, 'true', 7);
    }


    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

})(jQuery);