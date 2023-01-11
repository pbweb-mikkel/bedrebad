<?php
/*
 * This file adds functionality to check for posts to be automatically deleted. Uses the date field set_draft_date from acf
 */
function pb_get_expired_posts(){
    date_default_timezone_set('Europe/Copenhagen');
    $now = date('Y-m-d');
    $args = [
        'post_type' => 'any',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_query' => [
            array(
                'key' => 'set_draft_date',
                'value' => $now,
                'type' => 'DATE',
                'compare' => '<'
            )
        ]
    ];
    $posts = new WP_Query($args);
    return $posts->posts;
}


function pb_expire_posts(){
    new PB_Log('check_expire_post');
    $posts = pb_get_expired_posts();
    date_default_timezone_set('Europe/Copenhagen');
    $now = strtotime(date('Y-m-d'));
    if($posts && is_array($posts)){
        foreach ($posts as $post_id){
            $draft_date = get_field('set_draft_date', $post_id);
            if(!$draft_date){
                continue;
            }
            if(strtotime($draft_date) < $now){
                wp_update_post(array(
                    'ID'    =>  $post_id,
                    'post_status'   =>  'draft'
                ));
                new PB_Log('expire_post', $post_id, get_the_title($post_id));
            }
        }
    }
}

add_action('wp_scheduled_auto_draft_delete', 'pb_expire_posts');

function pb_post_expire_fields()
{
    if (function_exists('acf_add_local_field_group')):

        acf_add_local_field_group(array(
            'key'                   => 'group_627d6a73dbaf8',
            'title'                 => 'Udløbsdato',
            'fields'                => array(
                array(
                    'key'               => 'field_627d6a7a054ec',
                    'label'             => 'Dato for udløb',
                    'name'              => 'set_draft_date',
                    'type'              => 'date_picker',
                    'instructions'      => '',
                    'required'          => 0,
                    'conditional_logic' => 0,
                    'wrapper'           => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                    'display_format'    => 'F j, Y',
                    'return_format'     => 'Ymd',
                    'first_day'         => 1,
                ),
            ),
            'location'              => array(
                array(
                    array(
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'post',
                    ),
                ),
                array(
                    array(
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'page',
                    ),
                ),
                array(
                    array(
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'job',
                    ),
                ),
                array(
                    array(
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'case',
                    ),
                ),
                array(
                    array(
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'pb-popups',
                    ),
                ),
            ),
            'menu_order'            => 0,
            'position'              => 'side',
            'style'                 => 'default',
            'label_placement'       => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen'        => '',
            'active'                => true,
            'description'           => '',
            'show_in_rest'          => 0,
        ));

    endif;
}
add_action('init', 'pb_post_expire_fields');