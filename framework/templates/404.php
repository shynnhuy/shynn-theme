<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * The template for displaying the 404 page
 */

$page_404 = get_post( us_get_option( 'page_404' ) );

// Output specific page
if ( $page_404 ) {
	if ( class_exists( 'SitePress' ) ) {
		$page_404 = get_post( apply_filters( 'wpml_object_id', $page_404->ID, 'page', TRUE ) );
	}

	get_header();
	?>
	<main class="l-main"<?php echo ( us_get_option( 'schema_markup' ) ) ? ' itemprop="mainContentOfPage"' : ''; ?>>

		<?php
		do_action( 'us_before_page' );

		if ( us_get_option( 'enable_sidebar_titlebar', 0 ) ) {

			// Titlebar, if it is enabled in Theme Options
			us_load_template( 'templates/titlebar' );

			// START wrapper for Sidebar
			us_load_template( 'templates/sidebar', array( 'place' => 'before' ) );
		}

		us_open_wp_query_context();

		us_add_page_shortcodes_custom_css( $page_404->ID );

		echo apply_filters( 'the_content', $page_404->post_content );

		us_close_wp_query_context();

		if ( us_get_option( 'enable_sidebar_titlebar', 0 ) ) {
			// AFTER wrapper for Sidebar
			us_load_template( 'templates/sidebar', array( 'place' => 'after' ) );
		}

		do_action( 'us_after_page' );
		?>

	</main>
	<?php
	get_footer();

	// Output predefined layout
} else {
	$us_layout = US_Layout::instance();

	get_header();
	?>
	<main class="l-main">
		<section class="l-section">
			<div class="l-section-h i-cf">

				<?php do_action( 'us_before_404' ) ?>

				<div class="page-404">
					<?php
					$the_content = '<h1>' . us_translate( 'Page not found' ) . '</h1>';
					$the_content .= '<p>' . __( 'The link you followed may be broken, or the page may have been removed.', 'us' ) . '</p>';
					echo apply_filters( 'us_404_content', $the_content );
					?>
				</div>

				<?php do_action( 'us_after_404' ) ?>

			</div>
		</section>
	</main>
	<?php
	get_footer();
}
