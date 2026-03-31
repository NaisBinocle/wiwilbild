<?php
/**
 * WWB V2 — Single Product Template
 *
 * Header/footer handled by FSE template (single-product.html).
 * This file is loaded inside wp:woocommerce/legacy-template block.
 *
 * @package WWB_V2
 * @version 1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<main class="wwb-single-product">

	<div class="wwb-single-product__breadcrumbs">
		<?php
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
		}
		?>
	</div>

	<?php
	do_action( 'woocommerce_before_main_content' );

	while ( have_posts() ) :
		the_post();
		wc_get_template_part( 'content', 'single-product' );
	endwhile;

	do_action( 'woocommerce_after_main_content' );
	?>

</main>
