<?php
get_header();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		?>
		<main id="main-content">
            <?php do_action( 'pb_main_content_open' ); ?>
            <?php the_content(); ?>
		</main>
		<?php
	} // end while
} // end if

get_footer();