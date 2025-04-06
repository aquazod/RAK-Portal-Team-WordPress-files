<?php
/**
 * The template for displaying all pages.
 *
 * @package OceanWP WordPress theme
 */

get_header(); ?>

<?php if ( is_front_page() ) : ?>
    <div id="homepage-content-wrap" class="container clr">
        <div id="homepage-content" class="site-content clr">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php get_template_part( 'partials/page/layout' ); ?>
            <?php endwhile; ?>
        </div>
    </div>
<?php else : ?>
    <div id="content-wrap" class="container clr page-content-wrap">
        <div class="page-heading">
            <h1><?php the_title(); ?></h1>
        </div>
        <div id="content" class="site-content clr page-content">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php get_template_part( 'partials/page/layout' ); ?>
            <?php endwhile; ?>
        </div>
    </div>
<?php endif; ?>

<?php get_footer(); ?>
