<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package OceanWP WordPress theme
 */

// Assem Ali 28th July 2024: added sidebar, Arabic & English accordion, hid accordions from mobile view

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
<div id="content-wrap" class="container clr index-content-wrap">  <!-- Assem Ali 23rd July 2024 -->

    <?php do_action('ocean_before_primary'); ?>

    <?php // do_action('ocean_before_content'); ?>

    <div class="content-saver">
        <div class="sidebar-left">
            <!-- To be added by the script -->
        </div>    

        <div id="content" class="site-content clr index-content">
            <?php do_action('ocean_before_content_inner'); ?>

            <?php
            // Check if posts exist.
            if (have_posts()) :

                // Elementor `archive` location.
                if (!function_exists('elementor_theme_do_location') || !elementor_theme_do_location('archive')) {

                    // Add Support For EDD Archive Pages.
                    if (is_post_type_archive('download') || is_tax(array('download_category', 'download_tag'))) {

                        do_action('ocean_before_archive_download');
                        ?>

                        <div class="oceanwp-row <?php echo esc_attr(oceanwp_edd_loop_classes()); ?>">
                            <?php
                            // Archive Post Count for clearing float.
                            $oceanwp_count = 0;
                            while (have_posts()) :
                                the_post();
                                $oceanwp_count++;
                                get_template_part('partials/edd/archive');
                                if (oceanwp_edd_entry_columns() === $oceanwp_count) {
                                    $oceanwp_count = 0;
                                }
                            endwhile;
                            ?>
                        </div>

                        <?php
                        do_action('ocean_after_archive_download');
                    } else {
                        ?>
                        <div id="blog-entries" class="<?php oceanwp_blog_wrap_classes(); ?>">

                            <?php
                            // Define counter for clearing floats.
                            $oceanwp_count = 0;
                            ?>

                            <?php
                            // Loop through posts.
                            while (have_posts()) :
                                the_post();
                                ?>

                                <?php
                                // Add to counter.
                                $oceanwp_count++;
                                ?>

                                <?php
                                // Get post entry content.
                                get_template_part('partials/entry/layout', get_post_type());
                                ?>

                                <?php
                                // Reset counter to clear floats.
                                if (oceanwp_blog_entry_columns() === $oceanwp_count) {
                                    $oceanwp_count = 0;
                                }
                                ?>

                            <?php endwhile; ?>

                        </div><!-- #blog-entries -->

                        <?php
                        // Display post pagination.
                        oceanwp_blog_pagination();
                    }
                }
                ?>

                <?php
                // No posts found.
                else :
                    ?>

                    <?php
                    // Display no post found notice.
                    get_template_part('partials/none');
                    ?>

                <?php endif; ?>

            <?php do_action('ocean_after_content_inner'); ?>
        </div> <!-- #content -->
    </div> <!-- #content-saver -->

    <?php do_action('ocean_after_content'); ?>

    <?php do_action('ocean_after_primary'); ?>

</div> <!-- #content-wrap -->

<?php do_action('ocean_after_content_wrap'); ?>

<?php get_footer(); ?>
