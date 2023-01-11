<?php

function pb_login_logo() { ?>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap" rel="stylesheet">
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/img/logo-primary.svg);
            height:100px;
            width:100px;
            background-size: contain;
            background-repeat: no-repeat;
        }
        body{
            background: rgba(240, 237, 231, 0.5) !important;
            font-family:"Exo 2", sans-serif !important;
        }

        .dashicons{
            color:#5F2B1E !important;
        }

        .wp-core-ui .button-primary{
            background:#5F2B1E !important;
            border-color:#5F2B1E !important;
            border-radius: none !important;
        }

    </style>
<?php }
add_action( 'login_enqueue_scripts', 'pb_login_logo' );

function pb_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'pb_login_logo_url' );

function pb_login_logo_url_title() {
    return get_bloginfo('name');
}
add_filter( 'login_headertext', 'pb_login_logo_url_title' );
