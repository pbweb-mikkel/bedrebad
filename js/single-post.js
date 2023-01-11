(function($){


    var sliderEnabled = false;
    initRelatedPostsSlider();

    function initRelatedPostsSlider(){
        var ww = $(window).innerWidth();
        if(ww <= 980 && sliderEnabled == false) {
            sliderEnabled = true;
            $('.related-articles .posts').each(function () {
                var slider = this;
                var parent = $(slider).closest('section');
                $(slider).slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    infinite: false,
                    variableWidth: true,
                    dots: true,
                    arrows: false,
                    responsive: [
                        {
                            breakpoint: 10000,
                            settings: "unslick"
                        },
                        {
                            breakpoint: 980,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                            }
                        }
                    ]
                });
                $(slider).off('destroy');
                $(slider).on('destroy', function () {
                    sliderEnabled = false;
                });

            });
        }
    }

    $(window).on('resize', function(){
        initRelatedPostsSlider();
    });

})(jQuery);