<?php

function pb_render_popup($id = false){
    if(is_404()){
        return false;
    }

    if(!$id){

        $args = array(
            'post_type' => 'pb-popups',
            'posts_per_page' => 1,
            'fields' => 'ids',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'hvis_pa_sider',
                    'value' => '"'.get_the_ID().'"',
                    'compare' => 'LIKE'
                ),
            ),
            'orderby' => 'menu_order',
            'order' => 'ASC'
        );
        $popups = new WP_Query($args);

        if(!$popups->posts){
            $args = array(
                'post_type' => 'pb-popups',
                'posts_per_page' =>1,
                'fields' => 'ids',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'hvis_pa_alle_sider',
                        'value' => true,
                    ),
                ),
                'orderby' => 'menu_order',
                'order' => 'ASC'
            );
            $popups = new WP_Query($args);
        }

        $args = array(
            'post_type' => 'pb-popups',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'open_manually',
                    'value' => true,
                ),
            ),
            'orderby' => 'menu_order',
            'order' => 'ASC'
        );

        $popups_manual = new WP_Query($args);

        if($popups_manual->posts){
            $popups->posts = array_merge($popups->posts, $popups_manual->posts);
        }
    }


    if(!$popups->posts){
        return false;
    }

    global $post;
    foreach ($popups->posts as $id){
        $post = $id;
        setup_postdata($post);
        include(locate_template('inc/pb-popup/templates/popup.php'));
    }
    wp_reset_postdata();
}
add_action('wp_footer', 'pb_render_popup');


/**
 * Testimonials
 */

add_action('init', 'pb_popups_register');

function pb_popups_register()
{

    $labels = array(
        'name'               => __('Popups', 'pb'),
        'singular_name'      => __('Popup', 'pb'),
        'add_new'            => __('Tilføj ny', 'pb'),
        'add_new_item'       => __('Tilføj ny Popup', 'pb'),
        'edit'               => __('Rediger', 'pb'),
        'edit_item'          => __('Rediger Popup', 'pb'),
        'new_item'           => __('Ny Popup', 'pb'),
        'view'               => __('Se Popup', 'pb'),
        'view_item'          => __('Se Popup', 'pb'),
        'search_items'       => __('Søg efter Popup', 'pb'),
        'not_found'          => __('Ingen Popup blev fundet', 'pb'),
        'not_found_in_trash' => __('Ingen Popup fundet i papirkurven.', 'pb'),
        'parent'             => __('Popup forælder', 'pb'),
    );

    $args = array(
        'menu_icon'           => 'dashicons-format-gallery',
        'labels'              => $labels,
        'description'         => __('Popups som vises forskellige steder på websitet', 'pb'),
        'public'              => true,
        'exclude_from_search' => true,
        'show_in_nav_menus'   => false,
        'has_archive'         => false,
        'publicly_queryable'  => false,
        'hierachical'         => false,
        'supports'            => array('title', 'editor', 'thumbnail', 'page-attributes'),
        'capability_type'     => 'post',
    );
    register_post_type('pb-popups', $args);


    if( function_exists('acf_add_local_field_group') ):

        acf_add_local_field_group(array(
            'key' => 'group_5ef9b2fa7c4fb',
            'title' => 'Popup',
            'fields' => array(
                array(
                    'key' => 'field_5ef9b31f91c10',
                    'label' => 'Billede',
                    'name' => 'image',
                    'type' => 'image',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                ),
                array(
                    'key' => 'field_5ef9b34091c12',
                    'label' => 'Knap 1',
                    'name' => 'knap_1',
                    'type' => 'link',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                ),
                array(
                    'key' => 'field_5ef9b37891c14',
                    'label' => 'Knap 2',
                    'name' => 'knap_2',
                    'type' => 'link',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                ),
                array(
                    'key' => 'field_5ef9b3a191c16',
                    'label' => 'Vis på alle sider',
                    'name' => 'hvis_pa_alle_sider',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 0,
                    'ui' => 1,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                ),
                array(
                    'key' => 'field_5ef9b3c791c17',
                    'label' => 'Vis på sider',
                    'name' => 'hvis_pa_sider',
                    'type' => 'relationship',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_5ef9b3a191c16',
                                'operator' => '!=',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'post_type' => array(
                        0 => 'page',
                        1 => 'event',
                        2 => 'aktivitet',
                        3 => 'ophold',
                        4 => 'post',
                        4 => 'news',
                        4 => 'kalender',
                    ),
                    'taxonomy' => '',
                    'filters' => array(
                        0 => 'search',
                        1 => 'post_type',
                    ),
                    'elements' => '',
                    'min' => '',
                    'max' => '',
                    'return_format' => 'id',
                ),
                array(
                    'key' => 'field_5efaef4bbd0ff',
                    'label' => 'Skjul på mobil',
                    'name' => 'skjul_pa_mobil',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 1,
                    'ui' => 1,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                ),
                array(
                    'key' => 'field_5efaef4bbd5ff',
                    'label' => 'Åbnes manuelt',
                    'name' => 'open_manually',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 0,
                    'ui' => 1,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                ),
                array(
                    'key' => 'field_62bea87d4ol3',
                    'label' => 'Link',
                    'name' => 'manual_link',
                    'type' => 'text',
                    'readonly' => 1,
                    'instructions' => 'Kopier dette og indsæt det som link på en knap for at åbne denne popup',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            'field' => 'field_5efaef4bbd5ff',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'new_lines' => '',
                    'esc_html' => 0,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'pb-popups',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => false,
        ));



    endif;


}

function pb_popup_add_manual_link($field){
    $field['value'] = '#popup-'.get_the_ID();
    return $field;
}

add_filter('acf/load_field/name=manual_link', 'pb_popup_add_manual_link');