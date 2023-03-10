(function($){


    $('input, textarea, select', '.wpcf7, .post-password-form, .pb-form').on('focus', inputPlaceholderCheck);
    $('input, textarea, select', '.wpcf7, .post-password-form, .pb-form').on('change', inputPlaceholderCheck);
    $('input, textarea, select', '.wpcf7, .post-password-form, .pb-form').on('blur', inputPlaceholderCheckBlur);

    $('input, textarea', '.wpcf7, .post-password-form, .pb-form').on('blur', function(e){
        var parent = $(this).closest('.form-control-wrap');
        if(parent.length) {
            parent.removeClass('valid').removeClass('not-valid');
            var validated = validateField(this);
            if (validated) {
                parent.addClass('valid');
            }else{
                parent.addClass('not-valid');
            }
        }
    });

    $('input, textarea, select', '.wpcf7, .post-password-form, .pb-form').each(function(){

        if($(this).hasClass('wpcf7-validates-as-required') || $(this).attr('required')){

            var placeholder = $(this).closest('.form-control-wrap').find('.placeholder');
            var text = placeholder.text();
            placeholder.html(text+'<span class="required-asterix">*</span>');
        }

        inputPlaceholderCheckBlur(null, this);
    });


    function validateField(field){
        var field = $(field);
        var value = field.val();
        var type = field.attr('type');
        var required = (field.attr('required') || field.hasClass('wpcf7-validates-as-required'));
        var validated = true;

        if(required && isEmpty(value)){
            return false;
        }

        switch(type){
            case 'text':
                //validated = isLegalText(value);
                break;
            case 'email':
                validated = isEmail(value);
                break;
            case 'tel':
                validated = isPhoneNumber(value);
                break;
            case 'number':
                validated = isNumber(value);
                break;

        }

        return validated;

    }


    function inputPlaceholderCheck(){
        var value = $(this).val();
        var placeholder = $(this).closest('.form-control-wrap').find('.placeholder');
        if(placeholder.length == 1){
            $(placeholder).addClass('focus');
        }
    }

    function inputPlaceholderCheckBlur(e, target){
        if(!target){target = e.target}
        var value = $(target).val();
        var placeholder = $(target).closest('.form-control-wrap').find('.placeholder');
        $(placeholder).removeClass('focus');
        if(value.length == 0 && placeholder.length == 1){
            $(placeholder).removeClass('filled');
        }else{
            $(placeholder).addClass('filled');
        }
    }


    function isEmpty(str) {
        return (!str || 0 === str.length);
    }

    function isLegalText(txt) {
        if (txt == '' || txt == null) {
            return false;
        }
        var re = /^[a-zA-Z??????????????????????????????.,\s-]*$/;
        return re.test(txt);
    }

    function isPhoneNumber(txt) {
        if (txt == '' || txt == null) {
            return false;
        }
        var re = /^[+0-9\s]*$/;
        return re.test(txt);
    }

    function isEmail(email) {
        if (email == '' || email == null) {
            return false;
        }
        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        return re.test(email);
    }

    function isNumber(number) {
        if (number == '' || number == null) {
            return false;
        }
        var re = new RegExp('^[0-9]+$');
        return re.test(number);
    }

    if($('.post-password-form').length){
        $('input[type="password"]', '.post-password-form').focus();
    }

    var wpcf7Elm = document.querySelector( '.wpcf7' );
    if(wpcf7Elm){
        wpcf7Elm.addEventListener( 'wpcf7mailsent', function( event ) {
            $('input, textarea, select', '.wpcf7').each(function(){
                $(this).closest('.form-control-wrap').removeClass('valid not-valid').find('.placeholder').removeClass('filled focus');
            });
        }, false );
    }


})(jQuery);