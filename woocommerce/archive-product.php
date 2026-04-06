<?php
/**
 * WWB V2 — Product Archive
 *
 * Header/footer handled by FSE template (archive-product.html).
 *
 * @package WWB_V2
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Gather data for the enhanced header.
global $wp_query;
$product_count = $wp_query->found_posts;

// Count subcategories (collections) for the current term.
$collections_count = 0;
$current_term      = get_queried_object();
if ( $current_term && isset( $current_term->term_id ) ) {
	$child_terms       = get_terms( array(
		'taxonomy'   => 'product_cat',
		'parent'     => $current_term->term_id,
		'hide_empty' => true,
	) );
	$collections_count = is_array( $child_terms ) ? count( $child_terms ) : 0;
} elseif ( is_shop() ) {
	$top_cats          = get_terms( array(
		'taxonomy'   => 'product_cat',
		'parent'     => 0,
		'hide_empty' => true,
	) );
	$collections_count = is_array( $top_cats ) ? count( $top_cats ) : 0;
}
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

		<div class="wwb-shop__meta">
			<span class="wwb-shop__count"><strong><?php echo esc_html( $product_count ); ?> produit<?php echo $product_count > 1 ? 's' : ''; ?></strong></span>
			<?php if ( $collections_count > 0 ) : ?>
				<span class="wwb-shop__meta-dot">&bull;</span>
				<span><?php echo esc_html( $collections_count ); ?> collection<?php echo $collections_count > 1 ? 's' : ''; ?></span>
			<?php endif; ?>
		</div>
	</header>

	<!-- Filter pills (cosmetic) -->
	<div class="wwb-shop__filters">
		<div class="wwb-shop__filters-label">
			<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
				<path d="M2 4h14M5 9h8M7 14h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
			</svg>
			<span>Filtres du produit</span>
		</div>
		<div class="wwb-shop__filters-pills">
			<?php
			$filter_labels = array( 'Format', 'Style', 'Couleur', 'Prix', 'Usage', 'Pièce' );
			foreach ( $filter_labels as $label ) : ?>
				<button type="button" class="wwb-shop__pill">
					<?php echo esc_html( $label ); ?>
					<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</button>
			<?php endforeach; ?>
		</div>
	</div>

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

	<!-- Explorer par style -->
	<section class="wwb-shop__styles">
		<h2>Explorer par style</h2>
		<div class="wwb-shop__styles-grid">
			<?php
			$styles = array(
				array( 'label' => 'Terrazzo',        'slug' => 'terrazzo' ),
				array( 'label' => 'Béton Ciré',      'slug' => 'beton-cire' ),
				array( 'label' => 'Marbre',          'slug' => 'marbre' ),
				array( 'label' => 'Imitation Bois',  'slug' => 'imitation-bois' ),
				array( 'label' => 'Mosaïque',        'slug' => 'mosaique' ),
			);
			foreach ( $styles as $style ) :
				$tag_term  = get_term_by( 'slug', $style['slug'], 'product_tag' );
				$href      = $tag_term ? get_term_link( $tag_term ) : '#';
				$thumb_id  = $tag_term ? get_term_meta( $tag_term->term_id, 'thumbnail_id', true ) : 0;
				$thumb_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'medium' ) : '';
			?>
				<a href="<?php echo esc_url( $href ); ?>" class="wwb-shop__style-card">
					<div class="wwb-shop__style-card-img">
						<?php if ( $thumb_url ) : ?>
							<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $style['label'] ); ?>" loading="lazy" />
						<?php endif; ?>
					</div>
					<span><?php echo esc_html( $style['label'] ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</section>

	<!-- Comment choisir son carrelage ? -->
	<section class="wwb-shop__guide">
		<h2>Comment choisir son carrelage ?</h2>
		<ul class="wwb-shop__guide-list">
			<?php
			$tips = array(
				"Déterminez l'usage (intérieur, extérieur, mur, sol)",
				'Choisissez le format adapté (30×30, 60×60, mosaïque…)',
				'Vérifiez la résistance au glissement (norme R9 à R13)',
			);
			foreach ( $tips as $tip ) : ?>
				<li>
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<path d="M10 18a8 8 0 100-16 8 8 0 000 16z" stroke="var(--wp--preset--color--secondary)" stroke-width="1.5"/>
						<path d="M7 10l2 2 4-4" stroke="var(--wp--preset--color--secondary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					<span><?php echo esc_html( $tip ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
		<a href="#" class="wwb-shop__guide-btn">Lire le guide complet &rarr;</a>
	</section>

	<!-- Nos conseils carrelage -->
	<section class="wwb-shop__blog">
		<h2>Nos conseils carrelage</h2>
		<p class="wwb-shop__blog-intro">Inspirations, tutoriels et conseils pour réussir votre projet carrelage.</p>
		<div class="wwb-shop__blog-grid">
			<?php
			$blog_query = new WP_Query( array(
				'post_type'      => 'post',
				'posts_per_page' => 3,
				'post_status'    => 'publish',
				'tax_query'      => array(
					array(
						'taxonomy' => 'category',
						'field'    => 'slug',
						'terms'    => 'carrelage',
					),
				),
			) );

			if ( $blog_query->have_posts() ) :
				while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>
					<a href="<?php the_permalink(); ?>" class="wwb-shop__blog-card">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="wwb-shop__blog-card-img">
								<?php the_post_thumbnail( 'medium', array( 'loading' => 'lazy' ) ); ?>
							</div>
						<?php else : ?>
							<div class="wwb-shop__blog-card-img"></div>
						<?php endif; ?>
						<h3 class="wwb-shop__blog-card-title"><?php the_title(); ?></h3>
					</a>
				<?php endwhile;
				wp_reset_postdata();
			else : ?>
				<p class="wwb-shop__blog-empty">Contenus à venir — restez connecté !</p>
			<?php endif; ?>
		</div>
	</section>

</main>
