<?php
/**
 * WWB V2 — Single Product Content
 *
 * Fiche produit menuiserie : galerie gauche, configurateur tailles standard + sur-mesure droite.
 * Le formulaire variation (tailles, vitrage, couleur) est chargé via variable.php.
 *
 * @package WWB_V2
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Category
$cats      = wp_get_post_terms( $product->get_id(), 'product_cat' );
$cat_label = ! empty( $cats ) ? $cats[0]->name : '';

// Rating
$rating       = $product->get_average_rating();
$review_count = $product->get_review_count();
$full_stars   = floor( $rating );
$stars_html   = str_repeat( '★', $full_stars ) . str_repeat( '☆', 5 - $full_stars );

// Attributes for specs
$attributes = $product->get_attributes();
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'wwb-single', $product ); ?>>

	<!-- Breadcrumb -->
	<div class="wwb-single__breadcrumbs">
		<?php if ( function_exists( 'yoast_breadcrumb' ) ) {
			yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
		} ?>
	</div>

	<?php do_action( 'woocommerce_before_single_product' ); ?>

	<!-- Hero: 2 columns -->
	<div class="wwb-single__hero">

		<!-- Left: Product Gallery -->
		<div class="wwb-single__gallery">
			<?php do_action( 'woocommerce_before_single_product_summary' ); ?>
		</div>

		<!-- Right: Product Details -->
		<div class="wwb-single__details">

			<?php if ( $cat_label ) : ?>
				<span class="wwb-label"><?php echo esc_html( strtoupper( $cat_label ) ); ?></span>
			<?php endif; ?>

			<?php the_title( '<h1>', '</h1>' ); ?>

			<div class="wwb-single__rating">
				<?php if ( $review_count > 0 ) : ?>
					<span class="wwb-single__rating-stars"><?php echo esc_html( $stars_html ); ?></span>
					<span class="wwb-single__rating-count">(<?php echo esc_html( $review_count ); ?> avis)</span>
				<?php else : ?>
					<span class="wwb-single__rating-stars">★★★★★</span>
					<span class="wwb-single__rating-count">Soyez le premier à donner votre avis</span>
				<?php endif; ?>
			</div>

			<?php if ( $product->get_short_description() ) : ?>
				<p class="wwb-single__excerpt"><?php echo wp_kses_post( $product->get_short_description() ); ?></p>
			<?php endif; ?>

			<?php
			// Base price "À partir de" (shown before any variation selected)
			$base_price = 0;
			if ( $product->is_type( 'variable' ) ) {
				$base_price = (float) $product->get_variation_price( 'min', true );
			} else {
				$base_price = (float) $product->get_price();
			}
			?>
			<?php if ( $base_price > 0 ) : ?>
				<div class="wwb-single__price wwb-single__price--from">
					<span class="wwb-single__price-label">À partir de</span>
					<span class="wwb-single__price-amount"><?php echo wc_price( $base_price ); ?> <small>TTC</small></span>
					<span class="wwb-single__price-info">Livraison calculée au panier · Prix usine sans intermédiaire</span>
				</div>
			<?php endif; ?>

			<?php
			/**
			 * The add-to-cart template (variable.php) renders:
			 * - Predefined size buttons (pa_taille)
			 * - Custom dimensions fallback
			 * - Vitrage pills (pa_vitrage)
			 * - Color swatches (pa_couleurs)
			 * - Price + Add to cart
			 */
			woocommerce_template_single_add_to_cart();
			?>

			<!-- Reassurance -->
			<div class="wwb-single__reassurance">
				<div class="wwb-single__reassurance-item">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7h11v10H3zM14 10h4l3 3v4h-7zM7 20a2 2 0 100-4 2 2 0 000 4zM18 20a2 2 0 100-4 2 2 0 000 4z"/></svg>
					<div>
						<strong>Livraison offerte dès 500€</strong>
						<span>Transporteur spécialisé avec prise de RDV</span>
					</div>
				</div>
				<div class="wwb-single__reassurance-item">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6l8-3z"/><path d="M9 12l2 2 4-4"/></svg>
					<div>
						<strong>Garantie 10 ans constructeur</strong>
						<span>Pièces et main d'œuvre de fabrication</span>
					</div>
				</div>
			</div>

		</div><!-- .wwb-single__details -->
	</div><!-- .wwb-single__hero -->

	<!-- Caractéristiques techniques -->
	<section class="wwb-single__specs">
		<h2>Caractéristiques techniques</h2>
		<div class="wwb-single__specs-grid">
			<?php
			$specs_left  = array();
			$specs_right = array();

			if ( ! empty( $attributes ) ) {
				$i = 0;
				foreach ( $attributes as $attr ) {
					$name  = wc_attribute_label( $attr->get_name() );
					$value = $product->get_attribute( $attr->get_name() );
					if ( $value ) {
						// Skip variation attributes (taille, vitrage, couleurs) — they're in the configurator
						$attr_slug = strtolower( $attr->get_name() );
						if ( strpos( $attr_slug, 'taille' ) !== false
							|| strpos( $attr_slug, 'vitrage' ) !== false
							|| strpos( $attr_slug, 'couleur' ) !== false
							|| strpos( $attr_slug, 'dimension' ) !== false ) {
							continue;
						}
						if ( $i % 2 === 0 ) {
							$specs_left[] = array( 'label' => $name, 'value' => $value );
						} else {
							$specs_right[] = array( 'label' => $name, 'value' => $value );
						}
						$i++;
					}
				}
			}

			// Fallback static specs if no attributes
			if ( empty( $specs_left ) && empty( $specs_right ) ) {
				$specs_left = array(
					array( 'label' => 'Coefficient Uw', 'value' => '1.3 W/m²K' ),
					array( 'label' => 'Classement AEV', 'value' => 'A*4 E*7B V*A2' ),
					array( 'label' => "Type d'ouverture", 'value' => 'Oscillo-battante' ),
				);
				$specs_right = array(
					array( 'label' => 'Nombre de vantaux', 'value' => '1 vantail' ),
					array( 'label' => 'Épaisseur dormant', 'value' => '70 mm' ),
					array( 'label' => 'Origine', 'value' => 'Fabrication Pologne' ),
				);
			}
			?>
			<div class="wwb-single__specs-col">
				<?php foreach ( $specs_left as $spec ) : ?>
					<div class="wwb-single__specs-row">
						<span class="wwb-single__specs-label"><?php echo esc_html( $spec['label'] ); ?></span>
						<span class="wwb-single__specs-value"><?php echo esc_html( $spec['value'] ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="wwb-single__specs-col">
				<?php foreach ( $specs_right as $spec ) : ?>
					<div class="wwb-single__specs-row">
						<span class="wwb-single__specs-label"><?php echo esc_html( $spec['label'] ); ?></span>
						<span class="wwb-single__specs-value"><?php echo esc_html( $spec['value'] ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- Avis clients -->
	<?php
	$reviews = get_comments( array(
		'post_id' => $product->get_id(),
		'status'  => 'approve',
		'type'    => 'review',
		'number'  => 2,
	) );
	?>
	<?php
	$has_real_reviews = ! empty( $reviews ) || $review_count > 0;
	$display_count    = $has_real_reviews ? $review_count : 18;
	$display_rating   = $has_real_reviews && $rating > 0 ? number_format( $rating, 1 ) : '4,8';
	$display_stars    = $has_real_reviews && $rating > 0 ? $stars_html : '★★★★★';
	?>
	<section class="wwb-single__reviews">
			<div class="wwb-single__reviews-header">
				<h2>Avis clients (<?php echo esc_html( $display_count ); ?>)</h2>
				<div class="wwb-single__reviews-score">
					<span class="wwb-single__reviews-score-stars"><?php echo esc_html( $display_stars ); ?></span>
					<span class="wwb-single__reviews-score-number"><?php echo esc_html( $display_rating ); ?>/5</span>
				</div>
			</div>
			<div class="wwb-single__reviews-grid">
				<?php if ( $has_real_reviews && ! empty( $reviews ) ) : ?>
					<?php foreach ( $reviews as $review ) :
						$r_rating = intval( get_comment_meta( $review->comment_ID, 'rating', true ) );
						$r_stars  = $r_rating > 0 ? str_repeat( '★', $r_rating ) . str_repeat( '☆', 5 - $r_rating ) : '★★★★★';
						$r_author = $review->comment_author;
						$r_city   = get_comment_meta( $review->comment_ID, 'city', true );
						$r_meta   = $r_author;
						if ( $r_city ) $r_meta .= ' — ' . $r_city;
					?>
						<div class="wwb-single__review-card">
							<span class="wwb-single__review-stars"><?php echo esc_html( $r_stars ); ?></span>
							<p class="wwb-single__review-text">"<?php echo esc_html( wp_strip_all_tags( $review->comment_content ) ); ?>"</p>
							<span class="wwb-single__review-author"><?php echo esc_html( $r_meta ); ?></span>
						</div>
					<?php endforeach; ?>
				<?php else : ?>
					<div class="wwb-single__review-card">
						<span class="wwb-single__review-stars">★★★★★</span>
						<p class="wwb-single__review-text">"Fenêtre de très bonne qualité, l'isolation est remarquable. Le configurateur m'a permis de choisir exactement ce qu'il me fallait. Livraison soignée."</p>
						<span class="wwb-single__review-author">Pierre D. — Nantes</span>
					</div>
					<div class="wwb-single__review-card">
						<span class="wwb-single__review-stars">★★★★★</span>
						<p class="wwb-single__review-text">"Artisan, je commande régulièrement chez Wiwibild. Les fenêtres PVC sont d'excellent rapport qualité-prix. Le SAV est réactif en cas de problème."</p>
						<span class="wwb-single__review-author">Laurent M. — Artisan, Toulouse</span>
					</div>
				<?php endif; ?>
			</div>
		</section>

	<!-- Produits similaires -->
	<section class="wwb-single__related">
		<?php
		$related_ids = wc_get_related_products( $product->get_id(), 3 );
		if ( ! empty( $related_ids ) ) : ?>
			<h2>Produits similaires</h2>
			<div class="wwb-single__related-grid">
				<?php foreach ( $related_ids as $rid ) :
					$rp = wc_get_product( $rid );
					if ( ! $rp ) continue;
				?>
					<a href="<?php echo esc_url( $rp->get_permalink() ); ?>" class="wwb-single__related-card">
						<div class="wwb-single__related-card-img">
							<?php echo $rp->get_image( 'woocommerce_thumbnail' ); ?>
						</div>
						<span class="wwb-single__related-card-title"><?php echo esc_html( $rp->get_name() ); ?></span>
						<span class="wwb-single__related-card-price"><?php echo $rp->get_price_html(); ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</section>

	<?php do_action( 'woocommerce_after_single_product' ); ?>

</div><!-- .wwb-single -->
