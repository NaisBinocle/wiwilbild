<?php
/**
 * WWB V2 — Product Archive
 *
 * Layout wrapper (main, header, filters, bottom sections) is handled
 * by hooks in inc/woocommerce.php. This template only renders the
 * product loop and subcategories.
 *
 * @package WWB_V2
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

do_action( 'woocommerce_before_main_content' );

// Subcategories
$subcategories = woocommerce_maybe_show_product_subcategories();
if ( $subcategories ) {
	echo $subcategories;
}

if ( woocommerce_product_loop() ) :

	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();
			do_action( 'woocommerce_shop_loop' );
			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	do_action( 'woocommerce_after_shop_loop' );

else :

	do_action( 'woocommerce_no_products_found' );

endif;

do_action( 'woocommerce_after_main_content' );
