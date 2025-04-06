<?php
/**
 * Search result page entry read more
 *
 * @package OceanWP WordPress theme
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$lang = pll_current_language();
$ReadMoreText = ($lang === 'ar') ? "اقرأ المزيد" : (($lang === 'ur') ? "مزید پڑھیں" : "Read More");
?>

<div class="search-entry-readmore clr">
    <a href="<?php the_permalink(); ?>"
       title="<?php echo esc_attr( oceanwp_theme_strings( 'owp-string-search-continue-reading', false ) ); ?>">
        <?php echo $ReadMoreText; ?>
    </a>
    <span class="screen-reader-text"><?php the_title(); ?></span>
</div><!-- .search-entry-readmore -->


