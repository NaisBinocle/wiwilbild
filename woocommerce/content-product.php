<?php
/**
 * WWB V2 — Product Card in Archive Loop
 *
 * Figma design: card with image + badge, title, stars + rating, price + négoce barré.
 *
 * @package WWB_V2
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

// Badge logic
$badge_text  = '';
$badge_class = '';
if ( $product->is_on_sale() ) {
	$badge_text  = 'Promo';
	$badge_class = 'wwb-product-card__badge--sale';
} elseif ( $product->is_featured() ) {
	$badge_text  = 'Coup de coeur';
	$badge_class = 'wwb-product-card__badge--featured';
} else {
	$total_sales = (int) $product->get_total_sales();
	$date_created = $product->get_date_created();
	if ( $total_sales >= 500 ) {
		$badge_text  = '500+ vendus';
		$badge_class = 'wwb-product-card__badge--bestseller';
	} elseif ( $date_created && ( time() - $date_created->getTimestamp() ) < 30 * DAY_IN_SECONDS ) {
		$badge_text  = 'Nouveau';
		$badge_class = 'wwb-product-card__badge--new';
	}
}

// Rating
$rating_count = $product->get_rating_count();
$average      = $product->get_average_rating();

// Négoce price (custom field or fallback to regular price × markup)
$negoce_price = $product->get_meta( '_wwb_negoce_price' );
?>
<li <?php wc_product_class( 'wwb-product-card', $product ); ?>>

	<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="wwb-product-card__link">

		<div class="wwb-product-card__image">
			<?php if ( $badge_text ) : ?>
				<span class="wwb-product-card__badge <?php echo esc_attr( $badge_class ); ?>"><?php echo esc_html( strtoupper( $badge_text ) ); ?></span>
			<?php endif; ?>

			<?php echo $product->get_image( 'woocommerce_thumbnail' ); ?>
		</div>

		<div class="wwb-product-card__content">

			<h3 class="wwb-product-card__title"><?php echo get_the_title(); ?></h3>

			<?php if ( $rating_count > 0 ) : ?>
				<div class="wwb-product-card__rating">
					<span class="wwb-product-card__stars"><?php
						$full  = floor( $average );
						$half  = ( $average - $full ) >= 0.5 ? 1 : 0;
						$empty = 5 - $full - $half;
						echo str_repeat( '★', $full );
						if ( $half ) echo '★';
						echo str_repeat( '☆', $empty );
					?></span>
					<span class="wwb-product-card__rating-text"><?php echo esc_html( number_format( $average, 1, ',', '' ) . ' (' . $rating_count . ')' ); ?></span>
				</div>
			<?php endif; ?>

			<div class="wwb-product-card__price">
				<?php echo $product->get_price_html(); ?>
				<?php if ( $negoce_price ) : ?>
					<span class="wwb-product-card__negoce"><s><?php echo esc_html( $negoce_price ); ?> € en négoce</s></span>
				<?php endif; ?>
			</div>

		</div>

	</a>

</li>
