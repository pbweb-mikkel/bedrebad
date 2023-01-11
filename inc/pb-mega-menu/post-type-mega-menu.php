<?php
add_action( 'init', 'pb_register_mega_menu' );

function pb_register_mega_menu(){
    $labels = array(
        'name' => __( 'Megamenu', 'pb' ),
        'singular_name' => __( 'Megamenu', 'pb' ),
        'add_new' => __( 'Tilføj nyt', 'pb' ),
        'add_new_item' => __( 'Tilføj ny Megamenu', 'pb' ),
        'edit' => __( 'Rediger', 'pb' ),
        'edit_item' => __( 'Rediger Megamenu', 'pb' ),
        'new_item' => __( 'Nyt Megamenu', 'pb' ),
        'view' => __( 'Se Megamenu', 'pb' ),
        'view_item' => __( 'Se Megamenu', 'pb' ),
        'search_items' => __( 'Søg efter Megamenu', 'pb' ),
        'not_found' => __( 'Ingen Megamenu blev fundet', 'pb' ),
        'not_found_in_trash' => __( 'Ingen Megamenuer fundet i papirkurven.', 'pb' ),
        'parent' => __( 'Megamenu forælder' , 'pb'),
    );

    $args = array(
        'menu_icon' => 'dashicons-editor-kitchensink',
        'labels' => $labels,
        'public' => true,
        'exclude_from_search' => true,
        'show_in_nav_menus' => false,
        'has_archive' => false,
        'publicly_queryable'  => false,
        'hierachical' => false,
        'show_in_rest' => true,
        'supports' => array('title','editor','thumbnail', 'excerpt', 'page-attributes'),
        'capability_type' => 'post',
    );
    register_post_type( 'pb-mega-menu', $args);
}
