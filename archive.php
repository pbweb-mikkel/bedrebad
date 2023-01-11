<?php
global $post;
get_header();

$page_for_archive_id = get_field('archive_indhold', 'option');

if ( $page_for_archive_id ) :
    $post = get_post($page_for_archive_id);
    setup_postdata($post);
    ?>
    <main id="main-content">
        <?php do_action( 'pb_main_content_open' ); ?>
        <?php the_content(); ?>
    </main>
    <?php
endif;

get_footer();