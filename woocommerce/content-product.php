<?php
/**
 * WWB V2 — Product Card in Archive Loop
 *
 * BEM markup for V2 design system.
 *
 * @package WWB_V2
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( 'wwb-product-card', $product ); ?>>

	<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="wwb-product-card__link">

		<div class="wwb-product-card__image">
			<?php
			// Sale badge
			if ( $product->is_on_sale() ) : ?>
				<span class="wwb-product-card__badge wwb-product-card__badge--sale">Promo</span>
			<?php elseif ( $product->is_featured() ) : ?>
				<span class="wwb-product-card__badge wwb-product-card__badge--bestseller">Bestseller</span>
			<?php endif; ?>

			<?php echo $product->get_image( 'woocommerce_thumbnail' ); ?>
		</div>

		<div class="wwb-product-card__content">

			<?php
			// Variation count
			if ( $product->is_type( 'variable' ) ) :
				$variations = $product->get_available_variations();
				$count = count( $variations );
				if ( $count > 0 ) :
					$attributes = $product->get_attributes();
					$attr_label = '';
					foreach ( $attributes as $attr_name => $attr ) {
						if ( $attr['variation'] ) {
							$attr_label = wc_attribute_label( $attr_name );
							break;
						}
					}
					if ( $attr_label ) : ?>
						<span class="wwb-product-card__variants"><?php echo esc_html( $count . ' ' . $attr_label ); ?></span>
					<?php endif;
				endif;
			endif;
			?>

			<h3 class="wwb-product-card__title"><?php echo get_the_title(); ?></h3>

			<div class="wwb-product-card__price">
				<?php echo $product->get_price_html(); ?>
			</div>

		</div>

	</a>

</li>
