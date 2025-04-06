<?php
/**
 * The template for displaying all pages, single posts, and attachments
 *
 * This is a new template file that WordPress introduced in
 * version 4.3.
 *
 * @package OceanWP WordPress theme
 */

// Assem Ali 28th July 2024: added both Accordions, hid accordion in mobile view

get_header();
get_sidebar(); ?>

<?php do_action('ocean_before_content_wrap'); ?>

<div class="breadcrumb innerbg">
		<div class="container">
			<?php if ( function_exists( 'oceanwp_breadcrumb_trail' ) ) {
						oceanwp_breadcrumb_trail();
					}
			?>
		</div>
</div>
<div id="content-wrap" class="container clr single-post-content-wrap">  <!-- Assem Ali 23th July 2024 -->

    <?php do_action('ocean_before_primary'); ?>

    <?php do_action('ocean_before_content'); ?>

    <div class="content-saver">
        <div class="sidebar-left">
            <!-- To be added by the upper script -->
        </div> <!-- sidebar-left -->

        <div id="content" class="site-content clr single-post-content">
            <?php do_action('ocean_before_content_inner'); ?>

            <?php
            // Elementor `single` location.
            if (!function_exists('elementor_theme_do_location') || !elementor_theme_do_location('single')) {

                // Start loop.
                while (have_posts()) :
                    the_post();

                    if (is_singular('download')) {

                        // EDD Page.
                        get_template_part('partials/edd/single');

                    } elseif (is_singular('page')) {

                        // Single post.
                        get_template_part('partials/page/layout');

                    } elseif (is_singular('oceanwp_library') || is_singular('elementor_library')) {

                        // Library post types.
                        get_template_part('partials/library/layout');

                    } else {

                        // All other post types.
                        get_template_part('partials/single/layout', get_post_type());

                    }

                endwhile;

            }
            ?>

            <?php do_action('ocean_after_content_inner'); ?>
        </div><!-- #content -->
    </div>

    <?php do_action('ocean_after_content'); ?>
    
    <?php do_action('ocean_after_primary'); ?>

</div><!-- #content-wrap -->

<?php do_action('ocean_after_content_wrap'); ?>

<?php get_footer(); ?>
