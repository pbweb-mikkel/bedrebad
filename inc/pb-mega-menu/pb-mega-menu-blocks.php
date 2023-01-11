<?php

add_action('acf/init', 'pb_mega_acf_init_block_types');


function pb_mega_acf_init_block_types() {

    register_block_type(get_template_directory().'/inc/pb-mega-menu/blocks/megamenu-submenu');
    register_block_type(get_template_directory().'/inc/pb-mega-menu/blocks/megamenu-card');

}

function pb_mega_block_categories( $categories ) {
    global $post;

    if($post && $post->post_type == 'pb-mega-menu') {
        array_unshift(
            $categories,

            [
                'slug'  => 'mega-menu',
                'title' => __('Mega Menu', 'pb'),
            ]

        );
    }

    return $categories;
}
add_action( 'block_categories', 'pb_mega_block_categories', 10, 2 );

