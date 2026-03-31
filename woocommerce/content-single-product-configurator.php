<?php
/**
 * WWB V2 — Single Product Content for Configurator Products
 *
 * Replaces the default 2-column WC layout with a full-width configurator.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

global $product;
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'wwb-product-configurator', $product ); ?>>

	<!-- Breadcrumbs -->
	<div class="wwb-product-configurator__breadcrumbs">
		<?php
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
		}
		?>
	</div>

	<!-- Title + short desc -->
	<div class="wwb-product-configurator__header">
		<?php the_title( '<h1 class="product_title entry-title">', '</h1>' ); ?>
		<?php woocommerce_template_single_excerpt(); ?>
	</div>

	<!-- Full-width configurator -->
	<?php
	wc_get_template( 'single-product/add-to-cart/configurator.php', [
		'product' => $product,
		'config'  => WWB_Configurator::get_config_options(),
	] );
	?>

	<!-- Tabs (description, additional info, reviews) -->
	<div class="wwb-product-configurator__tabs">
		<?php woocommerce_output_product_data_tabs(); ?>
	</div>

	<!-- Related products -->
	<?php woocommerce_output_related_products(); ?>

</div>
