<?php
/**
 * The template for displaying 404 pages.
 *
 * @package OceanWP WordPress theme
 */ 
// Assem Ali 28th July 2024: added accordion, sidebar, and created an Arabic 404 page and customised both pages, English and Arabic. Hid accordions from mobile view

// Get ID.

$get_id = get_theme_mod( 'ocean_error_page_template' );

// Check if page is Elementor page.
$elementor = get_post_meta( $get_id, '_elementor_edit_mode', true );

// Get content.
$get_content = oceanwp_error_page_template_content();

// If blank page.
if ( 'on' === get_theme_mod( 'ocean_error_page_blank', 'off' ) ) { ?>

	<!DOCTYPE html>
	<html class="<?php echo esc_attr( oceanwp_html_classes() ); ?>" <?php language_attributes(); ?>>
		<head>
			<meta charset="<?php bloginfo( 'charset' ); ?>">
			<link rel="profile" href="https://gmpg.org/xfn/11">

			<?php wp_head(); ?>
		</head>

		<!-- Begin Body -->
		<body <?php body_class(); ?><?php oceanwp_schema_markup( 'html' ); ?>>

			<?php wp_body_open(); ?>

			<?php do_action( 'ocean_before_outer_wrap' ); ?>

			<div id="outer-wrap" class="site clr">

				<a class="skip-link screen-reader-text" href="#main"><?php echo esc_html( oceanwp_theme_strings( 'owp-string-header-skip-link', false ) ); ?></a>

				<?php do_action( 'ocean_before_wrap' ); ?>

				<div id="wrap" class="clr">

					<?php do_action( 'ocean_before_main' ); ?>

					<main id="main" class="site-main clr"<?php oceanwp_schema_markup( 'main' ); ?> role="main">

	<?php
} else {

	get_header();

}
?>

						<?php do_action( 'ocean_before_content_wrap' ); ?>

						<div id="content-wrap" class="container clr 404-content-wrap">  <!-- Assem Ali 28rd July 2024 -->

							<?php do_action( 'ocean_before_primary' ); ?>

<!-- 							<div id="primary" class="content-area clr"> -->

								<?php do_action( 'ocean_before_content' ); ?>
									
									<!--<div class="sidebar-left" style="width: 300px; padding-right: 15px;">

										<!-- To be added by the upper script -->

									<!-- </div> <!-- sidebar-left -->

								<div id="content" class="clr site-content 404-content">

									<?php do_action( 'ocean_before_content_inner' ); ?>

									<article class="entry clr">

										<?php
										// Elementor `404` location.
										if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {

											// Check if there is a template.
											if ( ! empty( $get_id ) ) {

												// If Elementor.
												if ( OCEANWP_ELEMENTOR_ACTIVE && $elementor ) {

													OceanWP_Elementor::get_error_page_content();

												} elseif ( OCEANWP_BEAVER_BUILDER_ACTIVE && ! empty( $get_id ) ) {

													echo do_shortcode( '[fl_builder_insert_layout id="' . $get_id . '"]' );

												} else if ( class_exists( 'SiteOrigin_Panels' ) && get_post_meta( $get_id, 'panels_data', true ) ) {

													echo SiteOrigin_Panels::renderer()->render( $get_id );

												} else {

													// If Gutenberg.
													if ( ocean_is_block_template( $get_id ) ) {
														$get_content = apply_filters( 'ocean_error_page_template_content', do_blocks( $get_content ) );
													}

													// Display template content.
													echo do_shortcode( $get_content );

												}
											} else {
												?>

												<div class="error404-content clr">
													<?php
													$logo_404 = get_theme_mod( 'ocean_404_logo' );
													if ( ! empty( $logo_404 ) ) {
														?>

														<img src="<?php echo esc_url( $logo_404 ); ?>" alt="<?php esc_attr_e( '404 Logo', 'oceanwp' ); ?>" title="<?php esc_attr_e( '404 Logo', 'oceanwp' ); ?>" />
													<?php } ?>
													
													<div class="container">
														  <style>
															.errorEN, .errorAR {
															  padding: 20px;
															  border: 1px solid #ddd;
															  border-radius: 5px;
															}
															.errorEN {
															  background-color: #f8f9fa;
															}
															.errorAR {
															  background-color: #f8f9fa;
															}
															.foreColorMain2 {
															  color: #d9534f;
															}
															.leftPanel {
															  text-align: left;
															}
															.ms-rteFontSize-5 {
															  font-size: 24px;
															}
															.ms-rteFontFace-12 {
															  font-family: Arial, sans-serif;
															}
														  </style>

														  <div class="errorEN col-md-6">
															<h2 class="foreColorMain2 leftPanel">
															  <span class="ms-rteFontSize-5">
																<span>Page Not Available 404</span>
															  </span>
															</h2>
															<div>
															  <p>
																The page you are trying to reach is not available. We apologize for the inconvenience. There are several possible reasons you are unable to reach the page you want:
															  </p>
															  <ul>
																<li>The link you used is outdated</li>
																<li>The Web address you entered is incorrect</li>
																<li>The page you want no longer exists</li>
																<li>Technical difficulties are preventing us from displaying the page</li>
															  </ul>
															  <p>
																<strong>
																  Please check the Web address to make sure it is correct or
																  <a href="/">visit our home page</a>.
																</strong>
															  </p>
															  <p>
																<a href="mailto:info@courts.rak.ae">Click here</a> to report the issue
															  </p>
															  <p>We apologize for the inconvenience this may cause you.</p>
															  <p>Thank you.</p>
															</div>
														  </div>

														  <div class="errorAR col-md-6">
															<h2 class="foreColorMain2 leftPanel">
															  <span class="ms-rteFontFace-12">
																<span class="ms-rteFontSize-5 ms-rteFontFace-12">صفحة غير متاحة 404</span>
															  </span>
															</h2>
															<div dir="rtl" style="text-align:right;">
															  <p>
																ا<span class="ms-rteFontFace-12">لصفحة التي تحاول الوصول إليها غير متوفرة الآن. نعتذر لهذا الوضع الحالي العائد للأسباب المحتملة التالية:</span>
															  </p>
															  <ul>
																<li><span class="ms-rteFontFace-12">الرابط الذي استخدمته قديم</span></li>
																<li><span class="ms-rteFontFace-12">عنوان الويب الذي أدخلته غير صحيح</span></li>
																<li><span class="ms-rteFontFace-12">الصفحة التي تريدها لم تعد متاحة</span></li>
																<li><span class="ms-rteFontFace-12">صعوبات تقنية تحول دون عرض الصفحة</span></li>
															  </ul>
															  <p>
																<span class="ms-rteFontFace-12">
																  <strong>
																	الرجاء، التحقق من صحة العنوان الإلكتروني <br>
																	أو تفضل <a href="/ar/%d8%a7%d9%84%d8%b1%d8%a6%d9%8a%d8%b3%d9%8a%d8%a9/">بزيارة صفحتنا الرئيسية.</a>
																  </strong>
																</span>
															  </p>
															  <p>
																<a href="mailto:info@courts.rak.ae">
																  <span class="ms-rteFontFace-12">الضغط هنا</span>
																</a>
																<span class="ms-rteFontFace-12"> للإبلاغ عن هذه المشكلة الرجاء </span>
															  </p>
															  <p><span class="ms-rteFontFace-12">نحن نعتذر عن الإزعاج الذي قد يسببه هذا الأمر.</span></p>
															  <p><span class="ms-rteFontFace-12">شكرا لك.</span></p>
															</div>
														  </div>
														</div>

												

												</div><!-- .error404-content -->

												<?php
											}
										}
										?>

									</article><!-- .entry -->

									<?php do_action( 'ocean_after_content_inner' ); ?>

								</div><!-- #content -->

								<?php do_action( 'ocean_after_content' ); ?>

<!-- 							</div><!-- #primary -->

							<?php //do_action( 'ocean_after_primary' ); ?>

						</div><!-- #content-wrap -->

						<?php do_action( 'ocean_after_content_wrap' ); ?>

<?php
// If blank page.
if ( 'on' === get_theme_mod( 'ocean_error_page_blank', 'off' ) ) {
	?>

					</main><!-- #main-content -->

					<?php do_action( 'ocean_after_main' ); ?>

				</div><!-- #wrap -->

				<?php do_action( 'ocean_after_wrap' ); ?>

			</div><!-- .outer-wrap -->

			<?php do_action( 'ocean_after_outer_wrap' ); ?>

			<?php wp_footer(); ?>

		</body>
	</html>

	<?php
} else {

	get_footer();

}
?>
