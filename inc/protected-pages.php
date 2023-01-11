<?php

if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_637247b12f743',
        'title' => 'Loginbeskyttet',
        'fields' => array(
            array(
                'key' => 'field_637247ae16802',
                'label' => 'Loginbeskyttet',
                'name' => 'login_protected',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'wpml_cf_preferences' => 3,
                'message' => '',
                'default_value' => 0,
                'ui_on_text' => '',
                'ui_off_text' => '',
                'ui' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => 0,
    ));

endif;


function pb_show_login_content($content){

    remove_filter( 'the_content', 'pb_show_login_content' );
    $id = get_field('loginside_indhold', 'option');
    $content = apply_filters('the_content',get_the_content(null, null, $id));
    return $content;
}

function pb_check_login_status(){

    if(!empty(get_field('login_protected')) && get_field('login_protected') == true && !is_user_logged_in()){
        add_filter( 'the_content', 'pb_show_login_content' );
        return;
    }

}
add_action('pb_main_content_open', 'pb_check_login_status');