<?php get_header(); ?>
    <main id="main-content">
        <?= apply_filters('the_content', get_the_content(null,null, get_field('404_indhold', 'option'))); ?>
    </main>
<?php get_footer(); ?>