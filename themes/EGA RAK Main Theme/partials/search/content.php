<?php
/**
 * Search result page entry content
 *
 * @package OceanWP WordPress theme
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

// Excerpt length.
$length = apply_filters( 'ocean_search_results_excerpt_length', '20' );

?>

<div class="search-entry-summary clr"<?php oceanwp_schema_markup( 'entry_content' ); ?>>
	<p>
		<?php
		// Display excerpt.
		if ( has_excerpt( $post->ID ) ) {
// 			the_excerpt();
			echo wp_kses_post(wp_trim_words(get_the_excerpt(), $length)); // Assem Ali 14th Jul 2024


		} else {
			// Display custom excerpt.
			echo wp_kses_post( wp_trim_words( strip_shortcodes( $post->post_content ), 10000 ) );
		}
		?>
	</p>
</div><!-- .search-entry-summary -->
