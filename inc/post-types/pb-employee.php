<?php
add_action( 'init', 'pb_register_employee' );

function pb_register_employee(){
    $labels = array(
        'name' =>  'Medarbejdere',
        'singular_name' =>  'Medarbejder',
        'add_new' =>  'Tilføj ny',
        'add_new_item' =>  'Tilføj ny Medarbejdere',
        'edit' =>  'Rediger',
        'edit_item' =>  'Rediger Medarbejdere',
        'new_item' =>  'Ny Medarbejdere',
        'view' =>  'Vis',
        'view_item' =>  'Vis Medarbejdere',
        'search_items' =>  'Søg efter Medarbejdere',
        'not_found' =>  'Ingen medarbejdere fundet',
        'not_found_in_trash' =>  'Ingen medarbejdere fundet i papirkurven.',
        'parent' =>  'Medarbejdere forælder',
    );

    $args = array(
        //'menu_icon' => 'dashicons-welcome-widgets-menus',
        'labels' => $labels,
        'description' => 'Medarbejdere som vises forskellige steder på websitet',
        'public' => true,
        'exclude_from_search' => true,
        'show_in_nav_menus' => false,
        'has_archive' => false,
        'publicly_queryable'  => true,
        'hierachical' => false,
        'show_in_rest' => true,
        'supports' => array('title','editor','thumbnail', 'excerpt', 'page-attributes'),
        'capability_type' => 'post',
    );
    register_post_type( 'medarbejder', $args);

    $labels = array(
        'name'              => _x( 'Kategorier', 'pb' ),
        'singular_name'     => _x( 'Kategori', 'pb' ),
        'add_new_item'     => _x( 'Tilføj kategori', 'pb' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'show_in_rest' => true,
    );
    register_taxonomy( 'employee_cat', 'medarbejder', $args );

}
