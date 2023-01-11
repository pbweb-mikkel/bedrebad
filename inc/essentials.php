<?php
require_once 'mobile_detect/Mobile_Detect.php';

add_action('init', 'pb_init');
function pb_init(){
    remove_action( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );
}

add_action('after_setup_theme', 'pb_after_setup_theme');

function pb_after_setup_theme(){
    $detect = new Mobile_Detect;
    global $isMobile;
    global $isTablet;
    $isMobile = $detect->isMobile();
    $isTablet = $detect->isTablet();
}

add_action('template_redirect', 'pb_disable_wp_pages');

function pb_disable_wp_pages() {
	global $wp_query;

    //|| is_archive()
	if ( is_author() || is_attachment() ) {
		// Redirect to homepage, set status to 301 permenant redirect.
		// Function defaults to 302 temporary redirect.
		wp_redirect(get_option('home'), 301);
		exit;
	}
}

function rename_flamingo() {

	global $menu;

	foreach($menu as $key => $item) {
		if ( $item[0] === 'Flamingo' ) {
			$menu[$key][0] = __('Forespørgsler','pb');     //change name
		}
	}
	return false;
}
add_action( 'admin_menu', 'rename_flamingo', 999 );



function my_myme_types($mime_types){
	$mime_types['svg'] = 'image/svg+xml'; //Adding svg extension
	return $mime_types;
}
add_filter('upload_mimes', 'my_myme_types', 1, 1);

add_action('init', 'pb_theme_setup');
function pb_theme_setup(){
	add_theme_support( 'post-thumbnails');
	//remove_theme_support( 'post-thumbnails');
	add_editor_style();
    //register_nav_menu( 'primary', __( 'Primær menu', 'pb' ) );
    register_nav_menu( 'primary-left', __( 'Primær menu - Venstre', 'pb' ) );
	register_nav_menu( 'primary-right', __( 'Primær menu - Højre', 'pb' ) );

    add_image_size('medium_large_cropped', 768, 576, true);
    add_image_size('medium_large_square', 768, 768, true);
    add_image_size('max_cropped', 1920, 650, true);
    add_image_size('max_1080', 1920, 1080, true);
    add_image_size('hero_max', 2560, 950, true);

	if(function_exists('acf_add_options_page')) {
		acf_add_options_page(array(
            'page_title' => 'Hjemmeside Indstillinger',
            'menu_title' => 'Hjemmeside Indstillinger',
            'menu_slug' => 'acf-options-hjemmeside-indstillinger',
            'capability' => 'manage_options'
        ));
	}

}

//add_action( 'widgets_init', 'pbweb_widgets_init' );

function pbweb_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Footer 1', 'pb' ),
		'id' => 'footer-1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widgettitle">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer 2', 'pb' ),
		'id' => 'footer-2',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widgettitle">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer 3', 'pb' ),
		'id' => 'footer-3',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widgettitle">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer 4', 'pb' ),
		'id' => 'footer-4',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widgettitle">',
		'after_title'   => '</h4>',
	) );

}

function pb_format_embed_link($link, $autoplay = false){

    if(strpos($link, 'vimeo') > -1){

        $link = str_replace('https://vimeo.com/', 'https://player.vimeo.com/video/', $link);

        if(strpos($link, '?') > -1){
            $link .= '&dnt=1';
        }else{
            $link .= '?dnt=1';
        }

        if($autoplay){
            $link .= '&autoplay=1&controls=1&loop=1&playsinline=1&autopause=0';
        }

    }else if(strpos($link, 'youtube') > -1){
        $link = str_replace('youtube.com/watch?v=', 'youtube-nocookie.com/embed/', $link);

        $link = strtok($link, '&');

        $parts = explode('/', $link);
        $id = array_pop($parts);

        if($autoplay){
            $link .= '?version=3&autoplay=1&controls=0&disablekb=1&iv_load_policy=3&modestbranding=0&rel=0&playsinline=1&loop=1&showinfo=0';
        }

    }

    return $link;
}

function pb_get_icon($icon, $size = 'icon-sm'){
    return '<img src="'.pb_get_icon_url($icon).'" '.pb_get_icon_size($size).' alt="'.$icon.'">';
}

function pb_get_icon_svg($icon, $size = 'icon-sm'){
    return file_get_contents(pb_get_icon_url($icon));
}

function pb_get_icon_url($icon){
    return get_template_directory_uri().'/img/icons/'.$icon.'.svg';
}


function pb_get_icon_size($size = 'icon-sm'){

    if(is_array($size)){
        $return = '';
        if(!empty($size[0])){
            $return .= 'width="'.$size[0].'px" ';
        }
        if(!empty($size[1])){
            $return .= 'height="'.$size[1].'px"';
        }

        return $return;
    }else{
        $sizes = [
            'icon-xxs' => '12px',
            'icon-xms' => '16px',
            'icon-xs' => '24px',
            'icon-sm' => '36px',
            'icon-md' => '48px',
            'icon-lg' => '72px',
            'icon-xl' => '120px',
        ];
        return 'width="'.$sizes[$size].'" height="'.$sizes[$size].'"';
    }
}

add_filter( 'excerpt_length', function($length) {
    return 11;
}, PHP_INT_MAX );

add_filter('post_thumbnail_html', function($html, $post_id, $thumb_id, $size, $attr){
   if(!$thumb_id){
       if($placeholder = get_field('default_featured_image', 'option')){
           $html = wp_get_attachment_image( $placeholder, $size, false, $attr );
       }
   }

   return $html;

}, 20, 5);


add_action( 'admin_menu', 'pb_remove_menu_pages' );
function pb_remove_menu_pages() {
    //remove_menu_page('edit.php');
    remove_menu_page('edit-comments.php');
}

function pb_get_link_from_array($array, $class = ""){
    return '<a href="'.$array['url'].'" '.($class ? 'class="'.$class.'"' : '').' target="'. ($array['target'] ?: '_self') .'" title="'. $array['title'] .'">'. $array['title'] .'</a>';
}

function pb_get_buttons_from_array($array, $default_class = ""){
    $c = '';
    foreach ($array as $a){
        $class = $a['farve'] != 'default' ? $a['farve'] : $default_class;
        $c .= pb_get_link_from_array($a['knap'], $class);
    }
    return $c;
}

function pb_format_date_to_datestring($date, $time_from = '', $time_to = '', $enddate = ''){

    $time = strtotime($date);
    $month = date('n', $time) - 1;
    $day = date('w', $time);
    $date = date('j', $time);
    $year = date('Y', $time);

    $days = [
        __('Søndag', 'pb'),
        __('Mandag', 'pb'),
        __('Tirsdag', 'pb'),
        __('Onsdag', 'pb'),
        __('Torsdag', 'pb'),
        __('Fredag', 'pb'),
        __('Lørdag', 'pb'),
    ];
    $months = [
        __('Januar', 'pb'),
        __('Februar', 'pb'),
        __('Marts', 'pb'),
        __('April', 'pb'),
        __('Maj', 'pb'),
        __('Juni', 'pb'),
        __('Juli', 'pb'),
        __('August', 'pb'),
        __('September', 'pb'),
        __('Oktober', 'pb'),
        __('November', 'pb'),
        __('December', 'pb'),
    ];

    if($enddate){

        $endtime = strtotime($enddate);
        $endmonth = date('n', $endtime) - 1;
        $endday = date('w', $endtime);
        $enddate = date('j', $endtime);


        $date_string = sprintf(
            __('%s D. %s %s - %s D. %s %s Kl. %s%s', 'pb'),
            $days[$day],
            $date,
            $months[$month],
            $days[$endday],
            $enddate,
            $months[$endmonth],
            $time_from,
            $time_to ? ' - '.$time_to : ''
        );
    }else{
        $date_string = sprintf(
            __('%s D. %s %s Kl. %s%s', 'pb'),
            $days[$day],
            $date,
            $months[$month],
            //$year,
            $time_from,
            $time_to ? ' - '.$time_to : ''
        );
    }

    return $date_string;
}

function pb_get_page_scope(){
    $scope = '';
        if(strpos(get_page_template_slug(), 'page-faq.php') > -1){
            $scope = 'itemscope itemtype="https://schema.org/FAQPage"';
        }

    return $scope;
}

function pb_wpcf7_set_form_tags( $form_tag )
{
    if ( $form_tag['name'] == 'referer-page' ) {
        $form_tag['values'][] = htmlspecialchars('https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    }
    return $form_tag;
}
if ( !is_admin() ) {
    add_filter( 'wpcf7_form_tag', 'pb_wpcf7_set_form_tags' );
}


// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

// Remove comments links from admin bar
add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});