<?php
get_header();
global $post;
$categories = get_the_terms(get_the_ID(), 'category');
$tags = get_the_terms(get_the_ID(), 'post_tag');

if(!empty($tags)){
    $terms = array_merge($categories, $tags);
}else{
    $terms = $categories;
}

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		?>
		<article id="main-content">
            <?php do_action( 'pb_main_content_open' ); ?>
            <header>
                <div class="background-container">
                    <?= the_post_thumbnail('hero_max'); ?>
                </div>
                <div class="content">
                    <div class="container">
                        <div class="row no-gutter">
                            <div class="col-lg-6 col-xxl-5">
                            <div class="terms">
                                <?php
                                    foreach ($terms as $t){
                                        echo '<a href="'.get_term_link($t->term_id).'" class="term type-'.$t->taxonomy.'">'.($t->taxonomy == 'post_tag' ? '#' : '').$t->name.'</a>';
                                    }
                                ?>
                            </div>
                            <h1><?= get_the_title() ?></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <div class="container">
                <div class="row">
                    <main class="col-lg-9 col-xl-8">
                        <div class="inner">
                            <?php if(get_field('ingredients')){ ?>
                            <div class="ingredients">
                                <h2 class="h3"><?= __('Ingredients', 'pb') ?></h2>
                                <?= get_field('ingredients') ?>
                            </div>
                            <?php } ?>
                            <?php the_content() ?>
                        </div>
                    </main>
                    <aside class="col-lg-2 ">
                        <div class="post-share">
                            <p><?= __('Share this post', 'pb') ?></p>
                            <ul>
                                <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(get_the_permalink()) ?>" target="_blank"><?= pb_get_icon_svg('facebook-primary', ['34', '34']); ?></a></li>
                                <li><a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode(get_the_permalink()) ?>" target="_blank"><?= pb_get_icon_svg('linkedin-primary', ['34', '34']); ?></a></li>
                                <li><a href="mailto:?subject=<?= get_the_title() ?>&body=<?= __("Hej. Jeg fandt denne artikel på cozze-pizza.com som jeg synes du skal se",'pb') ?>: <?= get_the_permalink(); ?>"><?= pb_get_icon_svg('email-primary', ['34', '34']); ?></a></li>
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
		</article>
		<?php
        $related = get_posts( array( 'category__in' => wp_get_post_categories($post->ID), 'numberposts' => 3, 'post__not_in' => array($post->ID) ) );

        if($related){
            ?>
            <div class="related-articles">
                <div class="container">
                    <div class="text">
                        <h2><?= __('Related articles', 'pb') ?></h2>
                    </div>
                    <div class="row gutter-10 posts">
                        <?php foreach($related as $p){
                            $p = $p->ID;
                            $terms = get_the_terms($p, 'category');
                            $tags = get_the_terms($p, 'post_tag');
                            $termClass = "";
                            if(is_array($terms) && count($terms) > 0){
                            foreach($terms as $term){
                            $termClass .= ' '.$term->slug;
                            }
                            }
                            $termClass = trim($termClass);

                            if($tags){
                            $terms = array_merge($terms, $tags);
                            }
                            ?>
                            <div class="post col-lg-6 col-xxl-4 <?= $termClass ?>" data-categories="<?= $termClass ?>">
                                <div class="inner">
                                    <a href="<?= get_the_permalink($p) ?>">
                                        <div class="image">
                                            <?= get_the_post_thumbnail($p, 'medium_large_cropped') ?>
                                        </div>
                                    </a>
                                    <div class="terms">
                                        <?php
                                        foreach ($terms as $t){
                                            echo '<a href="'.get_term_link($t->term_id).'" class="term type-'.$t->taxonomy.'">'.($t->taxonomy == 'post_tag' ? '#' : '').$t->name.'</a>';
                                        }
                                        ?>
                                    </div>
                                    <a href="<?= get_the_permalink($p) ?>">
                                        <h3><?= get_the_title($p); ?></h3>
                                        <div class="excerpt">
                                            <?= get_the_excerpt($p); ?>
                                        </div>
                                        <div class="circle-arrow primary" title="<?= __('Read article','pb') ?>"></div>
                                        <div class="btn-primary" title="<?= __('Read article','pb') ?>"><?= __('Read article','pb') ?></div>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php
        }

	} // end while
} // end if

get_footer();