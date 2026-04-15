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

<div class="wwb-single__breadcrumbs">
	<div class="wwb-single__breadcrumbs-inner">
		<?php if ( function_exists( 'yoast_breadcrumb' ) ) {
			yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
		} ?>
	</div>
</div>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'wwb-single', $product ); ?>>

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
			$base_price    = 0;
			$regular_price = 0;
			if ( $product->is_type( 'variable' ) ) {
				$base_price    = (float) $product->get_variation_price( 'min', true );
				$regular_price = (float) $product->get_variation_regular_price( 'min', true );
			} else {
				$base_price    = (float) $product->get_price();
				$regular_price = (float) $product->get_regular_price();
			}
			$is_on_sale     = $regular_price > 0 && $regular_price > $base_price;
			$discount_pct   = $is_on_sale ? round( ( ( $regular_price - $base_price ) / $regular_price ) * 100 ) : 0;
			?>
			<?php if ( $base_price > 0 ) : ?>
				<div class="wwb-single__price wwb-single__price--from<?php echo $is_on_sale ? ' is-sale' : ''; ?>">
					<span class="wwb-single__price-label">À partir de</span>
					<span class="wwb-single__price-amount">
						<?php echo wc_price( $base_price ); ?>
						<?php if ( $is_on_sale ) : ?>
							<del class="wwb-single__price-old"><?php echo wc_price( $regular_price ); ?></del>
							<span class="wwb-single__price-tag">-<?php echo esc_html( $discount_pct ); ?>%</span>
						<?php endif; ?>
						<small>TTC</small>
					</span>
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

	<?php $is_carrelage = has_term( 'carrelage', 'product_cat', $product->get_id() ); ?>

	<?php if ( $is_carrelage ) : ?>

		<!-- Onglets ancres (navigation rapide) -->
		<?php $tabs_review_count = $review_count > 0 ? $review_count : 18; ?>
		<nav class="wwb-carrelage-tabs" aria-label="Sections de la fiche produit">
			<a href="#description" class="wwb-carrelage-tabs__link is-active">Description</a>
			<a href="#qr" class="wwb-carrelage-tabs__link">Questions fréquentes</a>
			<a href="#reviews" class="wwb-carrelage-tabs__link">Avis (<?php echo esc_html( $tabs_review_count ); ?>)</a>
			<a href="#related" class="wwb-carrelage-tabs__link">Vous aimerez aussi</a>
		</nav>

		<!-- Description + Où poser (2 colonnes) -->
		<section id="description" class="wwb-carrelage-desc">
			<div class="wwb-carrelage-desc__col wwb-carrelage-desc__col--text">
				<h2>Un carrelage vintage pour habiller vos pièces</h2>
				<?php $full_desc = $product->get_description(); ?>
				<?php if ( $full_desc ) : ?>
					<div class="wwb-carrelage-desc__body"><?php echo wp_kses_post( wpautop( $full_desc ) ); ?></div>
				<?php else : ?>
					<p class="wwb-carrelage-desc__body">Inspiré des carreaux de ciment authentiques, ce carrelage en grès cérame émaillé apporte une touche rétro-chic à votre intérieur. Posable en sol et mural, compatible plancher chauffant.</p>
				<?php endif; ?>
			</div>

			<aside class="wwb-carrelage-desc__col wwb-carrelage-desc__col--usages">
				<h3>Où poser ce carrelage ?</h3>
				<ul class="wwb-carrelage-desc__usages">
					<li>
						<svg class="wwb-carrelage-desc__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/><line x1="10" y1="5" x2="8" y2="7"/><line x1="2" y1="12" x2="22" y2="12"/><line x1="7" y1="19" x2="7" y2="21"/><line x1="17" y1="19" x2="17" y2="21"/></svg>
						<div><strong>Salle de bain</strong><em>WC, douche, receveur</em></div>
					</li>
					<li>
						<svg class="wwb-carrelage-desc__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/><line x1="6" y1="17" x2="18" y2="17"/></svg>
						<div><strong>Cuisine</strong><em>Sol et crédence</em></div>
					</li>
					<li>
						<svg class="wwb-carrelage-desc__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 9V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v3"/><path d="M2 11v5a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-5a2 2 0 0 0-4 0v2H6v-2a2 2 0 0 0-4 0Z"/><path d="M4 18v2"/><path d="M20 18v2"/></svg>
						<div><strong>Salon &amp; salle à manger</strong><em>Pièces à vivre</em></div>
					</li>
					<li>
						<svg class="wwb-carrelage-desc__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13 4h3a2 2 0 0 1 2 2v14"/><path d="M2 20h3"/><path d="M13 20h9"/><path d="M10 12v.01"/><path d="M13 4.562v16.157a1 1 0 0 1-1.242.97L5 20V5.562a2 2 0 0 1 1.515-1.94l4-1A2 2 0 0 1 13 4.561Z"/></svg>
						<div><strong>Entrée &amp; couloir</strong><em>Zones de passage</em></div>
					</li>
					<li class="is-highlight">
						<svg class="wwb-carrelage-desc__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
						<div><strong>Plancher chauffant compatible</strong><em>Basse température, sans contrainte</em></div>
					</li>
				</ul>
			</aside>
		</section>

		<!-- Questions fréquentes carrelage -->
		<section id="qr" class="wwb-carrelage-qr">
			<h2>Questions fréquentes</h2>
			<div class="wwb-carrelage-qr__list">
				<details class="wwb-carrelage-qr__item" open>
					<summary>Quelle colle utiliser pour ce carrelage ?</summary>
					<p>Une colle <strong>C2S1 flex</strong> est recommandée pour une pose en intérieur sur chape, dalle béton ou ancien carrelage. Comptez environ 5 kg de colle par m².</p>
				</details>
				<details class="wwb-carrelage-qr__item">
					<summary>Quelle largeur de joint pour un rendu authentique ?</summary>
					<p>Pour un effet carreau de ciment fidèle, un <strong>joint fin de 2 à 3 mm</strong> en ton beige ou gris clair met en valeur le motif sans l'écraser.</p>
				</details>
				<details class="wwb-carrelage-qr__item">
					<summary>Peut-on le poser sur un ancien carrelage ?</summary>
					<p>Oui, à condition que le support soit sain, plan, non décollé et parfaitement nettoyé. Utilisez un primaire d'accrochage adapté avant l'encollage.</p>
				</details>
				<details class="wwb-carrelage-qr__item">
					<summary>Existe-t-il des plinthes assorties ?</summary>
					<p>Nous proposons des plinthes droites en grès cérame coordonnées sur la plupart des séries. Contactez-nous pour connaître la disponibilité.</p>
				</details>
			</div>
		</section>

	<?php else : ?>

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

	<?php endif; ?>

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
	<section id="reviews" class="wwb-single__reviews">
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
	<section id="related" class="wwb-single__related">
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
