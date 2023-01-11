<?php
add_action( 'init', 'pb_register_page_element' );

function pb_register_page_element(){
    $labels = array(
        'name' => __( 'Sideelementer', 'pb' ),
        'singular_name' => __( 'Sideelement', 'pb' ),
        'add_new' => __( 'Tilføj nyt', 'pb' ),
        'add_new_item' => __( 'Tilføj nyt Sideelement', 'pb' ),
        'edit' => __( 'Rediger', 'pb' ),
        'edit_item' => __( 'Rediger Sideelement', 'pb' ),
        'new_item' => __( 'Nyt Sideelement', 'pb' ),
        'view' => __( 'Se Sideelement', 'pb' ),
        'view_item' => __( 'Se Sideelement', 'pb' ),
        'search_items' => __( 'Søg efter Sideelement', 'pb' ),
        'not_found' => __( 'Ingen Sideelement blev fundet', 'pb' ),
        'not_found_in_trash' => __( 'Ingen Sideelement fundet i papirkurven.', 'pb' ),
        'parent' => __( 'Sideelement forælder' , 'pb'),
    );

    $args = array(
        'menu_icon' => 'dashicons-welcome-widgets-menus',
        'labels' => $labels,
        'description' => __( 'Sideelementer som vises forskellige steder på websitet', 'pb' ),
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
    register_post_type( 'pb-page-element', $args);
}
