<?php
function pb_custom_password_form() {
    global $post;
    $label = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );

    $output = '';
    $content_id = get_field('loginside_indhold', 'option');
    if($content_id){
        remove_filter( 'the_content', 'wpautop' );
        $output .= apply_filters('the_content',get_the_content(null, null, $content_id));
    }
    return $output;
    add_filter( 'the_content', 'wpautop' );
}
add_filter('the_password_form', 'pb_custom_password_form', 99);

function pb_password_protected_logged_in(){
    global $post;

    if(!$cookie_hash = get_transient('cookie_hash')){
        $cookie_hash = md5(get_bloginfo('url'));
        set_transient('cookie_hash', $cookie_hash);
    }

    if(isset($_COOKIE['wp-postpass_'.$cookie_hash])){
        return true;
    }else{
        return false;
    }
/*
    if(!empty($post->post_password) && !post_password_required()){
        return true;
    }else{
        return false;
    }
*/
}