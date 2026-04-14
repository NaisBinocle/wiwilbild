<?php
/**
 * WWB V2 — Product Card in Archive Loop
 *
 * Figma design: card with image + badge, title, material tag, stars + rating, price.
 * Adapts for menuiserie (material badge, "À partir de") and carrelage (/m² suffix).
 *
 * @package WWB_V2
 * @version 5.0.0
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
	$total_sales  = (int) $product->get_total_sales();
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

// Detect product line
$is_carrelage = has_term( 'carrelage', 'product_cat', $product->get_id() );

// Material tag (menuiserie only) — extract from product name or attribute
$material_tag = '';
if ( ! $is_carrelage ) {
	$name_lower = strtolower( $product->get_name() );
	if ( strpos( $name_lower, 'pvc' ) !== false ) {
		$material_tag = 'PVC';
	} elseif ( strpos( $name_lower, 'aluminium' ) !== false || strpos( $name_lower, 'alu' ) !== false ) {
		$material_tag = 'Aluminium';
	} elseif ( strpos( $name_lower, 'bois' ) !== false ) {
		$material_tag = 'Bois';
	}
}

// Négoce price (carrelage) or showroom price (menuiserie)
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

			<?php if ( $material_tag ) : ?>
				<span class="wwb-product-card__material"><?php echo esc_html( $material_tag ); ?></span>
			<?php endif; ?>

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
					<span class="wwb-product-card__negoce">
						<s><?php echo esc_html( $negoce_price ); ?> € <?php echo $is_carrelage ? 'en négoce' : 'constaté en showroom'; ?></s>
					</span>
				<?php endif; ?>
			</div>

			<?php if ( ! $is_carrelage ) :
				$uw = get_post_meta( $product->get_id(), '_wwb_uw_value', true );
				if ( ! $uw ) $uw = '1,3';
				$delivery = get_post_meta( $product->get_id(), '_wwb_delivery_weeks', true );
				if ( ! $delivery ) $delivery = '3-4';
			?>
				<div class="wwb-product-card__specs">
					<span>Uw <?php echo esc_html( $uw ); ?></span>
					<span class="wwb-product-card__specs-dot">·</span>
					<span>Fab. France</span>
					<span class="wwb-product-card__specs-dot">·</span>
					<span>Livré <?php echo esc_html( $delivery ); ?> sem.</span>
				</div>
			<?php endif; ?>

			<span class="wwb-product-card__cta">
				<span><?php echo $is_carrelage ? 'Voir le produit' : 'Configurer'; ?></span>
				<svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2 6h8M7 3l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</span>

		</div>

	</a>

</li>
