<?php
global $post;
get_header();

$page_for_posts_id = get_option('page_for_posts');
if ( $page_for_posts_id ) :
    $post = get_page($page_for_posts_id);
    setup_postdata($post);
    ?>
    <main id="main-content">
        <?php do_action( 'pb_main_content_open' ); ?>
        <?php the_content(); ?>
    </main>
    <?php
endif;

get_footer();