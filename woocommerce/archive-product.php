<?php
/**
 * WWB V2 — Product Archive
 *
 * Header/footer handled by FSE template (archive-product.html).
 *
 * @package WWB_V2
 * @version 3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<main class="wwb-shop">

	<div class="wwb-shop__breadcrumbs">
		<?php
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
		}
		?>
	</div>

	<?php do_action( 'woocommerce_before_main_content' ); ?>

	<header class="wwb-shop__header">
		<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
			<h1 class="wwb-shop__title"><?php woocommerce_page_title(); ?></h1>
		<?php endif; ?>

		<?php do_action( 'woocommerce_archive_description' ); ?>
	</header>

	<?php
	// Subcategories
	$subcategories = woocommerce_maybe_show_product_subcategories();
	if ( $subcategories ) {
		echo $subcategories;
	}
	?>

	<?php if ( woocommerce_product_loop() ) : ?>

		<?php do_action( 'woocommerce_before_shop_loop' ); ?>

		<?php woocommerce_product_loop_start(); ?>

		<?php
		if ( wc_get_loop_prop( 'total' ) ) {
			while ( have_posts() ) {
				the_post();
				do_action( 'woocommerce_shop_loop' );
				wc_get_template_part( 'content', 'product' );
			}
		}
		?>

		<?php woocommerce_product_loop_end(); ?>

		<?php do_action( 'woocommerce_after_shop_loop' ); ?>

	<?php else : ?>

		<?php do_action( 'woocommerce_no_products_found' ); ?>

	<?php endif; ?>

	<?php do_action( 'woocommerce_after_main_content' ); ?>

</main>
