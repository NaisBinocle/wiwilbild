<?php
/**
 * WWB V2 — Single Product Content
 *
 * Fiche produit Figma design: image left, configurator right.
 * Replaces default WooCommerce content-single-product.php.
 *
 * @package WWB_V2
 * @version 1.0.0
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

			<?php if ( $review_count > 0 ) : ?>
				<div class="wwb-single__rating">
					<span class="wwb-single__rating-stars"><?php echo esc_html( $stars_html ); ?></span>
					<span class="wwb-single__rating-count">(<?php echo esc_html( $review_count ); ?> avis)</span>
				</div>
			<?php endif; ?>

			<?php if ( $product->get_short_description() ) : ?>
				<p class="wwb-single__excerpt"><?php echo wp_kses_post( $product->get_short_description() ); ?></p>
			<?php endif; ?>

			<!-- Configurator Header -->
			<div class="wwb-single__config-header">
				<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
					<path d="M9 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" stroke="currentColor" stroke-width="1.5"/>
					<path d="M14.55 11.25a1.26 1.26 0 00.25 1.39l.05.05a1.53 1.53 0 11-2.16 2.16l-.05-.05a1.26 1.26 0 00-1.39-.25 1.26 1.26 0 00-.75 1.15v.15a1.53 1.53 0 11-3.06 0v-.08a1.26 1.26 0 00-.82-1.15 1.26 1.26 0 00-1.39.25l-.05.05a1.53 1.53 0 11-2.16-2.16l.05-.05a1.26 1.26 0 00.25-1.39 1.26 1.26 0 00-1.15-.75H2.03a1.53 1.53 0 110-3.06h.08a1.26 1.26 0 001.15-.82 1.26 1.26 0 00-.25-1.39l-.05-.05a1.53 1.53 0 112.16-2.16l.05.05a1.26 1.26 0 001.39.25h.06a1.26 1.26 0 00.75-1.15V2.03a1.53 1.53 0 113.06 0v.08a1.26 1.26 0 00.75 1.15 1.26 1.26 0 001.39-.25l.05-.05a1.53 1.53 0 112.16 2.16l-.05.05a1.26 1.26 0 00-.25 1.39v.06a1.26 1.26 0 001.15.75h.15a1.53 1.53 0 110 3.06h-.08a1.26 1.26 0 00-1.15.75z" stroke="currentColor" stroke-width="1.5"/>
				</svg>
				CONFIGUREZ VOTRE FENÊTRE
			</div>

			<!-- Section 1: Dimensions -->
			<div class="wwb-single__config-section">
				<div class="wwb-single__config-title">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<path d="M2 14h12M2 14V2M2 5h2M2 8h3M2 11h2M5 14v-2M8 14v-3M11 14v-2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
					</svg>
					Dimensions
				</div>
				<div class="wwb-single__config-dims">
					<div class="wwb-single__config-field">
						<label for="wwb-dim-width">Largeur (cm)</label>
						<input type="number" id="wwb-dim-width" value="120" min="40" max="300" step="1" data-config="width">
					</div>
					<div class="wwb-single__config-field">
						<label for="wwb-dim-height">Hauteur (cm)</label>
						<input type="number" id="wwb-dim-height" value="135" min="40" max="250" step="1" data-config="height">
					</div>
				</div>
			</div>

			<!-- Section 2: Matériau -->
			<div class="wwb-single__config-section">
				<div class="wwb-single__config-title">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<path d="M8 1L1 5v6l7 4 7-4V5L8 1z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
						<path d="M1 5l7 4 7-4M8 9v6" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
					</svg>
					Matériau
				</div>
				<div class="wwb-single__config-options wwb-single__config-options--3col" data-config-group="materiau">
					<?php
					$materiaux = array(
						'pvc'       => 'PVC',
						'aluminium' => 'Aluminium',
						'bois'      => 'Bois',
					);
					$first = true;
					foreach ( $materiaux as $value => $label ) : ?>
						<button type="button" class="wwb-single__config-btn<?php echo $first ? ' wwb-single__config-btn--active' : ''; ?>" data-value="<?php echo esc_attr( $value ); ?>">
							<?php echo esc_html( $label ); ?>
						</button>
					<?php $first = false; endforeach; ?>
				</div>
			</div>

			<!-- Section 3: Couleur -->
			<div class="wwb-single__config-section">
				<div class="wwb-single__config-title">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.5"/>
						<path d="M8 1.5A6.5 6.5 0 008 14.5V1.5z" fill="currentColor"/>
					</svg>
					Couleur
				</div>
				<div class="wwb-single__config-colors" data-config-group="couleur">
					<?php
					$colors = array(
						'blanc'      => array( 'hex' => '#ffffff', 'border' => '1px solid #d1d5db' ),
						'anthracite' => array( 'hex' => '#374151', 'border' => 'none' ),
						'chene'      => array( 'hex' => '#8b6914', 'border' => 'none' ),
						'bleu-nuit'  => array( 'hex' => '#1e3a5f', 'border' => 'none' ),
						'vert'       => array( 'hex' => '#2d5a27', 'border' => 'none' ),
					);
					$first = true;
					foreach ( $colors as $slug => $data ) : ?>
						<button type="button"
							class="wwb-single__config-color<?php echo $first ? ' wwb-single__config-color--active' : ''; ?>"
							data-value="<?php echo esc_attr( $slug ); ?>"
							style="background:<?php echo esc_attr( $data['hex'] ); ?>;<?php echo $data['border'] !== 'none' ? 'border:' . $data['border'] : ''; ?>"
							title="<?php echo esc_attr( ucfirst( str_replace( '-', ' ', $slug ) ) ); ?>">
						</button>
					<?php $first = false; endforeach; ?>
				</div>
				<span class="wwb-single__config-color-label" id="wwb-color-label">Blanc (sélectionné)</span>
			</div>

			<!-- Section 4: Vitrage -->
			<div class="wwb-single__config-section">
				<div class="wwb-single__config-title">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<rect x="1.5" y="1.5" width="13" height="13" rx="1.5" stroke="currentColor" stroke-width="1.5"/>
						<line x1="8" y1="1.5" x2="8" y2="14.5" stroke="currentColor" stroke-width="1.5"/>
					</svg>
					Vitrage
				</div>
				<div class="wwb-single__config-options wwb-single__config-options--2col" data-config-group="vitrage">
					<button type="button" class="wwb-single__config-btn wwb-single__config-btn--active" data-value="double">
						Double vitrage
						<small>4/16/4 argon</small>
					</button>
					<button type="button" class="wwb-single__config-btn" data-value="triple">
						Triple vitrage
						<small>4/12/4/12/4</small>
					</button>
				</div>
			</div>

			<!-- Price -->
			<div class="wwb-single__price">
				<span class="wwb-single__price-amount"><?php echo $product->get_price_html(); ?></span>
				<span class="wwb-single__price-info" id="wwb-price-info">Prix pour 120×135 cm • PVC Blanc • Double vitrage</span>
			</div>

			<!-- CTA Buttons -->
			<form class="cart" method="post" enctype="multipart/form-data" data-product_id="<?php echo absint( $product->get_id() ); ?>">
				<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
				<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>">
				<input type="hidden" name="quantity" value="1">
				<button type="submit" class="wwb-single__cta wwb-single__cta--primary single_add_to_cart_button">
					<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<path d="M1 1h2.5l1.3 6.5a2 2 0 002 1.5h7.4a2 2 0 002-1.5L17.5 4H4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						<circle cx="7" cy="15" r="1" fill="currentColor"/>
						<circle cx="14" cy="15" r="1" fill="currentColor"/>
					</svg>
					Ajouter au panier
				</button>
				<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
			</form>

			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>?devis=<?php echo $product->get_id(); ?>" class="wwb-single__cta wwb-single__cta--outline">
				<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
					<path d="M10.5 1.5H4.5a1.5 1.5 0 00-1.5 1.5v12a1.5 1.5 0 001.5 1.5h9a1.5 1.5 0 001.5-1.5v-9l-4.5-4.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					<path d="M10.5 1.5v4.5H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
				Demander un devis sur-mesure
			</a>

			<!-- Reassurance -->
			<div class="wwb-single__reassurance">
				<div class="wwb-single__reassurance-item">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<path d="M1 9.5h2l2 3.5 4-10 2.5 6.5H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					Livraison offerte dès 500€
				</div>
				<div class="wwb-single__reassurance-item">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<path d="M8 1L2 4v4.5c0 3.5 2.5 6 6 7.5 3.5-1.5 6-4 6-7.5V4L8 1z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
						<path d="M5.5 8l2 2 3-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					Garantie 10 ans constructeur
				</div>
				<div class="wwb-single__reassurance-item">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<path d="M10 1.5L6 5.5H2v4h4l4 4V1.5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
					</svg>
					Pose par artisan certifié disponible
				</div>
			</div>

		</div><!-- .wwb-single__details -->
	</div><!-- .wwb-single__hero -->

	<!-- Caractéristiques techniques -->
	<section class="wwb-single__specs">
		<h2>Caractéristiques techniques</h2>
		<div class="wwb-single__specs-grid">
			<?php
			// Try product attributes first
			$specs_left = array();
			$specs_right = array();

			if ( ! empty( $attributes ) ) {
				$i = 0;
				foreach ( $attributes as $attr ) {
					$name  = wc_attribute_label( $attr->get_name() );
					$value = $product->get_attribute( $attr->get_name() );
					if ( $value ) {
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
	<?php if ( ! empty( $reviews ) || $review_count > 0 ) : ?>
		<section class="wwb-single__reviews">
			<div class="wwb-single__reviews-header">
				<h2>Avis clients (<?php echo esc_html( $review_count ); ?>)</h2>
				<?php if ( $rating > 0 ) : ?>
					<div class="wwb-single__reviews-score">
						<span class="wwb-single__reviews-score-stars"><?php echo esc_html( $stars_html ); ?></span>
						<span class="wwb-single__reviews-score-number"><?php echo esc_html( number_format( $rating, 1 ) ); ?>/5</span>
					</div>
				<?php endif; ?>
			</div>
			<div class="wwb-single__reviews-grid">
				<?php if ( ! empty( $reviews ) ) : ?>
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
					<!-- Placeholder reviews -->
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
	<?php endif; ?>

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

<script>
(function() {
	// Config button toggle
	document.querySelectorAll('[data-config-group]').forEach(function(group) {
		group.addEventListener('click', function(e) {
			var btn = e.target.closest('.wwb-single__config-btn');
			if (!btn) return;
			group.querySelectorAll('.wwb-single__config-btn').forEach(function(b) {
				b.classList.remove('wwb-single__config-btn--active');
			});
			btn.classList.add('wwb-single__config-btn--active');
		});
	});

	// Color swatch toggle
	var colorGroup = document.querySelector('[data-config-group="couleur"]');
	if (colorGroup) {
		colorGroup.addEventListener('click', function(e) {
			var btn = e.target.closest('.wwb-single__config-color');
			if (!btn) return;
			colorGroup.querySelectorAll('.wwb-single__config-color').forEach(function(b) {
				b.classList.remove('wwb-single__config-color--active');
			});
			btn.classList.add('wwb-single__config-color--active');
			var label = document.getElementById('wwb-color-label');
			if (label) {
				label.textContent = btn.title + ' (sélectionné)';
			}
		});
	}
})();
</script>
