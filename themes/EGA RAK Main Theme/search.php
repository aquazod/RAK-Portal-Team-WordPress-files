<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package OceanWP WordPress theme
 */

// Assem Ali 29th July 2024: added both accordions, hid accordions from mobile view

get_header(); 
?>
<?php get_sidebar(); ?> <!-- Assem Ali 17th July 2024 -->
<?php //get_sidebar('left'); ?>

<?php do_action('ocean_before_content_wrap'); ?>

<div class="breadcrumb innerbg">
		<div class="container">
			<?php if ( function_exists( 'oceanwp_breadcrumb_trail' ) ) {
						oceanwp_breadcrumb_trail();
					}
			?>
		</div>
</div>

<div id="content-wrap" class="container clr search-content-wrap">  <!-- Assem Ali 23rd July 2024 -->
    <?php do_action('ocean_before_primary'); ?>

    <?php do_action('ocean_before_content'); ?>


    <div class="content-saver">
        <div class="sidebar-left">
            <!-- To be added by the upper script -->
        </div> <!-- sidebar-left -->

        <div id="content" class="site-content clr search-content">
            <?php
            $logo_search = get_theme_mod('ocean_search_logo');
            if (!empty($logo_search)) {
            ?>
                <img class="logo-search" src="<?php echo esc_url($logo_search); ?>" alt="<?php esc_attr_e('Search Logo', 'oceanwp'); ?>" title="<?php esc_attr_e('Search Logo', 'oceanwp'); ?>" />
            <?php } ?>

            <?php do_action('ocean_before_content_inner'); ?>

            <?php if (have_posts()) : ?>

                <?php
                while (have_posts()) :
                    the_post();
                ?>

                    <?php get_template_part('partials/search/layout'); ?>

                <?php endwhile; ?>

                <?php oceanwp_pagination(); ?>

            <?php else : ?>

                <?php
                // Display no post found notice.
                get_template_part('partials/none');
                ?>

            <?php endif; ?>

            <?php do_action('ocean_after_content_inner'); ?>
        </div> <!-- search-content -->
    </div>

    <?php do_action('ocean_after_content'); ?>

    <?php do_action('ocean_after_primary'); ?>

    <?php do_action('ocean_after_content_wrap'); ?>
</div><!-- #content-wrap -->

<?php get_footer(); ?>
