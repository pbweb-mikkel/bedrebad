(function($){
    if($('body').hasClass('block-editor-page')){

        acf.addAction('new_field/type=accordion', function(field){
            set_title(field.$el);
        });

        function set_title(field){
            var title_el = $(field).find('.acf-accordion-title label');
            var title = $(field).find('.acf-field-text[data-name="title"]');

            if(!title.length){
                return false;
            }
            title = $('input[type="text"]', title).val();

            if(!title.length){
                return false;
            }

            title_el.text(title);
        }
    }
})(jQuery);