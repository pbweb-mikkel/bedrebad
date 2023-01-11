<?php
define('PB_ASSET_VERSION', time());
function pb_register_styles(){
    wp_deregister_script( 'jquery' );
    wp_enqueue_script( 'jquery', includes_url( '/js/jquery/jquery.js' ), false, NULL, true );
    wp_enqueue_script('pb', get_template_directory_uri() . '/js/main.js', array('jquery', 'gsap'), PB_ASSET_VERSION, true);
    wp_enqueue_script('pb-form', get_template_directory_uri() . '/js/form.min.js', array('jquery'), PB_ASSET_VERSION, true);
    wp_enqueue_script('slick', get_template_directory_uri() . '/inc/slick/slick.min.js', array('jquery'), '1.8.1', true);

    wp_enqueue_script('gsap', get_template_directory_uri() . '/inc/gsap/gsap.min.js', array('jquery'), '', true);
    wp_enqueue_script('lightbox', get_template_directory_uri() . '/inc/lightbox/js/lightbox.min.js', array('jquery'), '', true);
    wp_enqueue_style('lightbox', get_template_directory_uri() . '/inc/lightbox/css/lightbox.min.css');
    wp_enqueue_style('pb-critical', get_template_directory_uri() . '/style.css', array(), PB_ASSET_VERSION, 'screen');
    wp_enqueue_script( 'aos-js', get_template_directory_uri() . '/inc/aos/aos.js', array('pb'), '1.9.2', true );
    wp_enqueue_style( 'aos', get_template_directory_uri() . '/inc/aos/aos.css', null, '4.1.3');

    wp_localize_script( 'pb', 'ajax_object',
        array(
            'ajaxurl' => admin_url( 'admin-ajax.php' )
        )
    );

    if(get_post_type() == 'post' && !is_home()){
        wp_enqueue_style('pb-posts', get_template_directory_uri() . '/scss/single-post.css', array(), PB_ASSET_VERSION, 'screen');
        wp_enqueue_script('pb-posts', get_template_directory_uri() . '/js/single-post.min.js', array('jquery', 'slick'), PB_ASSET_VERSION, true);
    }

}
add_action( 'wp_enqueue_scripts', 'pb_register_styles', 11);

function pb_assets_footer(){
    wp_enqueue_style('pb', get_template_directory_uri() . '/pb-styles.css', array(), PB_ASSET_VERSION, 'screen');
    //wp_enqueue_style('pb-popup',get_template_directory_uri().'/inc/pb-popup/pb-popup.css');
    //wp_enqueue_script('pb-popup',get_template_directory_uri().'/inc/pb-popup/pb-popup.min.js', array('jquery'));
}
add_action('wp_footer', 'pb_assets_footer');

// Update CSS within in Admin
function admin_style() {
    wp_enqueue_style('admin-styles', get_template_directory_uri().'/admin-style.css');
    wp_enqueue_script('pb-admin-script', get_template_directory_uri().'/admin/admin.min.js');

    echo "<style>

            @font-face {
                font-family: 'Jost';
                src: url('".get_template_directory_uri()."/fonts/Jost-Regular.ttf') format('truetype');
                font-display: swap;
                font-weight: 400;
            }

            @font-face {
                font-family: 'Jost';
                src: url('".get_template_directory_uri()."/fonts/Jost-Italic.ttf') format('truetype');
                font-display: swap;
                font-weight: 400;
                font-style: italic;
            }

            @font-face {
                font-family: 'Jost';
                src: url('".get_template_directory_uri()."/fonts/Jost-Bold.ttf') format('truetype');
                font-display: swap;
                font-weight: 600;
            }

            @font-face {
                font-family: 'Barlow';
                src: url('".get_template_directory_uri()."/fonts/Barlow-Regular.ttf') format('truetype');
                font-display: swap;
                font-weight: 400;
            }

            @font-face {
                font-family: 'Amatic SC';
                src: url('".get_template_directory_uri()."/fonts/AmaticSC-Regular.ttf') format('truetype');
                font-display: swap;
                font-weight: 400;
            }

            @font-face {
                font-family: 'Amatic SC';
                src: url('".get_template_directory_uri()."/fonts/AmaticSC-Bold.ttf') format('truetype');
                font-display: swap;
                font-weight: 700;
            }

            /* adjust fallback font overrides */
            @font-face {
                font-family: fallback-font;
                ascent-override: 100%;
                descent-override: 15%;
                line-gap-override: normal;
                advance-override: 10;
                src: local(Arial);
            }

            html, body{
                font-family: 'Jost', fallback-font, sans-serif;
            }
            
            .acf-block-preview h1, .acf-block-preview h2, .acf-block-preview h3{
                    font-family: 'gill-sans-nova-extra-condens';
            }
            
        </style>
        <link rel='stylesheet' href='https://use.typekit.net/oug1ebv.css'>";

}
add_action('admin_enqueue_scripts', 'admin_style');