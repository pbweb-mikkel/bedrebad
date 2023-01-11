<?php
global $isMobile;
global $isTablet;
global $post;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> <?= pb_get_page_scope(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?php wp_title(); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

        <link rel="preload" href="<?= get_template_directory_uri() ?>/fonts/Liquid_Embrace/Liquid_Embrace.ttf" crossorigin="anonymous" as="font" type="font/ttf">
        <link rel="preload" href="<?= get_template_directory_uri() ?>/fonts/Bebas_Neue/BebasNeue-Regular.ttf" crossorigin="anonymous" as="font" type="font/ttf">
        <link rel="preload" href="<?= get_template_directory_uri() ?>/fonts/Roboto/Roboto-Light.ttf" crossorigin="anonymous" as="font" type="font/ttf">
        <style>

            @font-face {
                font-family: 'Liquid Embrace';
                src: url('<?= get_template_directory_uri() ?>/fonts/Liquid_Embrace/Liquid_Embrace.ttf') format('truetype');
                font-display: swap;
                font-weight: 400;
            }

            @font-face {
                font-family: 'Bebas Neue';
                src: url('<?= get_template_directory_uri() ?>/fonts/Bebas_Neue/BebasNeue-Regular.ttf') format('truetype');
                font-display: swap;
                font-weight: 400;
            }

            @font-face {
                font-family: 'Roboto';
                src: url('<?= get_template_directory_uri() ?>/fonts/Roboto/Roboto-Light.ttf') format('truetype');
                font-display: swap;
                font-weight: 300;
            }

            @font-face {
                font-family: 'Roboto';
                src: url('<?= get_template_directory_uri() ?>/fonts/Roboto/Roboto-Bold.ttf') format('truetype');
                font-display: swap;
                font-weight: 600;
            }

            @font-face {
                font-family: 'Roboto';
                src: url('<?= get_template_directory_uri() ?>/fonts/Roboto/Roboto-LightItalic.ttf') format('truetype');
                font-display: swap;
                font-weight: 300;
                font-style: italic;
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
                font-family: 'Roboto', fallback-font, sans-serif;
            }
        </style>
        <?php
	    if(get_field('header_top_scripts', 'option')) {
		    echo get_field('header_top_scripts', 'option');
	    }
	    ?>
        <?php wp_head(); ?>
        <?php
	    if(get_field('header_scripts', 'option')) {
		    echo get_field('header_scripts', 'option');
	    }
	    ?>
    </head>
<body <?php body_class() ?>>
<?php do_action( 'wp_body_open' ); ?>
<?php
if(get_field('body_scripts', 'option')) {
	echo get_field('body_scripts', 'option');
}
?>
<a class="skip-link screen-reader-text" href="#main-content">Skip to content</a>
<header id="header">
    <div class="container-fluid">
        <div class="header-navigation-left">
            <nav class="primary-nav">
                <?php
                wp_nav_menu(
                    array('theme_location' => 'primary-left', 'walker' => new PB_Walker())
                );
                ?>
            </nav>
        </div>
        <div class="header-logo">
            <?php if($logo = get_field('logo', 'option')){ ?>
                <div id="logo">
                    <a href="<?= get_home_url() ?>">
                        <?= file_get_contents(wp_get_attachment_image_url($logo['ID'], 'full')) ?>
                    </a>
                </div>
            <?php } ?>
        </div>
        <div class="header-navigation-right">
            <nav class="primary-nav">
                <?php
                wp_nav_menu(
                    array('theme_location' => 'primary-right', 'walker' => new PB_Walker())
                );
                ?>
            </nav>
        </div>
        <div class="mobile-menu-toggle">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="16" viewBox="0 0 28.48 16.11"><defs><style>.a{fill:currentColor;fill-rule:evenodd;}</style></defs><path class="a" d="M1.54,1.47a.75.75,0,0,0-.09.3.77.77,0,0,0,0,.3.74.74,0,0,0,.14.28.72.72,0,0,0,.24.19c.29.16,6,.53,6.51.55a133.28,133.28,0,0,0,18-.91A.92.92,0,0,0,27,1.92a.89.89,0,0,0,.26-.63A.89.89,0,0,0,26.35.4,88.52,88.52,0,0,0,8.3.53C5.73.82,1.88.71,1.54,1.47Z" transform="translate(0 0)"/><path class="a" d="M12.87,6.86A106.82,106.82,0,0,0,.92,8.46a.64.64,0,0,0-.18.25A.77.77,0,0,0,.68,9a.8.8,0,0,0,.06.3.72.72,0,0,0,.18.25c.34.49,3.06.21,5,.26A94.83,94.83,0,0,0,25.3,8.67c.37,0,2.31,0,2.83-.45a.91.91,0,0,0,.3-.42.89.89,0,0,0,0-.51.85.85,0,0,0-.26-.45.93.93,0,0,0-.46-.24C27.4,6.55,16.64,6.6,12.87,6.86Z" transform="translate(0 0)"/><path class="a" d="M19.65,15.73c6.65-.44,8.06-.15,8.17-1.49a.91.91,0,0,0-.19-.59.84.84,0,0,0-.55-.3s1-.13-7.53-.26a101.11,101.11,0,0,0-14.45.55,33.62,33.62,0,0,0-4.79.65.87.87,0,0,0-.2.23.77.77,0,0,0-.1.29.81.81,0,0,0,0,.31.87.87,0,0,0,.14.27c.31.53,3.19.32,5,.42A79.13,79.13,0,0,0,19.65,15.73Z" transform="translate(0 0)"/></svg>
        </div>
    </div>
    <?php do_action('pb_before_header_end'); ?>
</header>