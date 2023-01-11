<footer id="footer">
<?php

    $fat_footer_content_id = get_field('fat_footer_indhold', 'option');
    $exclude_pages = get_field('exclude_pages', $fat_footer_content_id);
    if($fat_footer_content_id){
        if(!(!empty($exclude_pages) && in_array(get_the_ID(), $exclude_pages))){
            echo apply_filters('the_content',get_the_content(null, null, $fat_footer_content_id));
        }
    }

    $footer_content_id = get_field('footer_indhold', 'option');
    if($footer_content_id){
        echo apply_filters('the_content',get_the_content(null, null, $footer_content_id));
    }
?>
</footer>
<?php get_template_part('templates/partials/search-form'); ?>
<?php get_template_part('templates/partials/mobile-menu'); ?>
<a id="scroll-to-top" title="<?= __('Rul til toppen', 'pb') ?>" style="opacity: 0;"></a>
<div id="pb-overlay"></div>
<?php wp_footer(); ?>
<script>
    jQuery.event.special.touchstart = {
        setup: function( _, ns, handle ) {
            this.addEventListener("touchstart", handle, { passive: !ns.includes("noPreventDefault") });
        }
    };
    jQuery.event.special.touchmove = {
        setup: function( _, ns, handle ) {
            this.addEventListener("touchmove", handle, { passive: !ns.includes("noPreventDefault") });
        }
    };
    jQuery.event.special.wheel = {
        setup: function( _, ns, handle ){
            this.addEventListener("wheel", handle, { passive: true });
        }
    };
    jQuery.event.special.mousewheel = {
        setup: function( _, ns, handle ){
            this.addEventListener("mousewheel", handle, { passive: true });
        }
    };
</script>
<script>
    lightbox.option({
        'albumLabel': "%1 / %2",
    })
</script>
<script>
    AOS.init({
        once:true
    });
</script>
</body>
</html>