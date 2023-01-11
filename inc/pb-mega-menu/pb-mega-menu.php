<?php
define('PB_MEGA_MENU', true);
include 'post-type-mega-menu.php';
include 'pb-mega-menu-blocks.php';
include 'pb-mega-menu-acf-fields.php';

add_action('pb_before_header_end', 'pb_render_mega_menu');
function pb_render_mega_menu(){
    global $isMobile;
    global $isTablet;
    //ob_start();

    if(!$isMobile && !$isTablet){
        $mega_menus = get_posts(['numberposts' => -1, 'post_type' => 'pb-mega-menu']);
        if($mega_menus){
            echo '<div id="mega-menu-container">';
            foreach ($mega_menus as $m){
                $post = get_post($m->ID);
                setup_postdata( $post );

                $connected_page_id = get_field('forbundet_til_side', $m->ID);
                $content = $m->post_content;
                if(!$connected_page_id){
                    continue;
                }

                ?>
                <div class="mega-menu-item" style="opacity: 0;" data-id="<?= $connected_page_id ?>">
                    <?php echo apply_filters('the_content',get_the_content(null, null, $m->ID)); ?>
                </div>
                <?php
            }
            wp_reset_postdata();
            echo '</div>';
        }

        //echo ob_end_clean();
    }
}

add_action('wp_footer', 'pb_add_menu_overlay');
function pb_add_menu_overlay(){
    echo '<div id="menu-overlay"></div>';
}

function pb_mega_register_styles(){
    wp_enqueue_script('pb-mega-menu', get_template_directory_uri() . '/inc/pb-mega-menu/mega-menu.min.js', array('jquery'), PB_ASSET_VERSION, true);
    wp_enqueue_style('pb-mega-menu', get_template_directory_uri() . '/inc/pb-mega-menu/mega-menu.css', PB_ASSET_VERSION, true);

}
add_action( 'wp_enqueue_scripts', 'pb_mega_register_styles', 12);