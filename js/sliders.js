(function($){

    initSliders();
    function initSliders(){

        $('.showcase-slider').each(function(){
            var slider = this;
            var parent = $(slider).closest('.section-showcase-slider');
            var slidesToShow = Math.floor($(this).width() / $('.slide', slider).first().width());
            var slidesToScroll = Math.ceil(slidesToShow / 2);

            $(slider).slick({
                slidesToShow: slidesToShow,
                slidesToScroll:slidesToScroll,
                infinite: false,
                variableWidth: true,
                dots:true,
                arrows:false,
                appendDots : $('.dots', parent),
                responsive:[
                    {
                        breakpoint:1200,
                        settings:{
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        }
                    },
                    {
                        breakpoint:1000,
                        settings:{
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        }
                    },
                    {
                        breakpoint:767,
                        settings:{
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
            });

        $('.media-text-slider').each(function(){
            var slider = this;
            var parent = $(slider).closest('section');
            $(slider).slick({
                infinite: true,
                variableWidth: false,
                dots:false,
                fade:true,
                arrows:false,
                autoplay:true,
                dots:true
            });
        });

    }
})(jQuery);