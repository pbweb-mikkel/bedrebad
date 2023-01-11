<?php
$s = $_POST['s'];

$args = array(
    'post_type' => 'any',
    's' => $s,
);
// The Query
$the_query = new WP_Query( $args );
?>
<?php if ( $the_query->have_posts() ) : ?>
    <div class="search-title"><?= count($the_query->posts) ?><?php printf( __( ' resultater på \'%s\'', 'pb' ), '<span>' . $s . '</span>' ); ?></div>
    <div class="search-results">
    <?php /* Start the Loop */ ?>
    <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
        <div class="search-result">
            <a href="<?= get_the_permalink(); ?>">
                <div class="image">
                    <?php the_post_thumbnail('thumbnail'); ?>
                </div>
                <div class="content">
                    <h4><?= get_the_title(); ?></h4>
                    <p><?= get_the_excerpt(); ?></p>
                </div>
            </a>
        </div>
    <?php endwhile; ?>
    </div>
<?php else: ?>
    <p><?php printf( __( 'Ingen resultater fundet på \'%s\'', 'pb' ), '<span>' . $s . '</span>' ); ?></p>
<?php endif; ?>