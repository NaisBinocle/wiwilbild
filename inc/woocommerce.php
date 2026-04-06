<?php
/**
 * WWB V2 — WooCommerce Customizations
 *
 * Hooks WC : prix, swatches, cart, checkout, shortcodes.
 * Migré depuis mu-plugins/wwb-woocommerce.php le 2026-04-01.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ─────────────────────────────────────────────
// Filters & Actions
// ─────────────────────────────────────────────

// 1. Prix "À partir de" pour les variables
add_filter( 'woocommerce_variable_sale_price_html', 'wwb_v2_variation_price_format', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'wwb_v2_variation_price_format', 10, 2 );

// 2. Suffixe /m² sur les carrelages
add_filter( 'woocommerce_get_price_html', 'wwb_v2_suffixe_m2', 10, 2 );

// 3. AJAX Cart fragments
add_filter( 'woocommerce_add_to_cart_fragments', 'wwb_v2_cart_fragment' );

// 4. Cleanup — remove default WC elements we handle ourselves
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );

// Fix WC CSS overrides
add_action( 'wp_head', function() {
    if ( is_shop() || is_product_taxonomy() ) {
        echo '<style>.woocommerce-products-header__title{display:none!important}ul.products::before,ul.products::after{content:none!important;display:none!important}</style>' . "\n";
    }
    if ( is_product() ) {
        echo '<style>.wwb-single.product{display:block!important;grid-template-columns:none!important}</style>' . "\n";
    }
} );

// 5. Breadcrumb separator (Yoast)
add_filter( 'wpseo_breadcrumb_separator', function() {
    return '<img src="' . get_template_directory_uri() . '/assets/img/arrow_right.svg" alt="" width="16" height="16">';
} );

// 6. Color swatches
add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'wwb_v2_color_swatches', 10, 2 );

// 7. Tab headings
add_filter( 'woocommerce_product_description_heading', function() { return 'Description du produit'; } );
add_filter( 'woocommerce_product_additional_information_heading', function() { return 'Caractéristiques techniques'; } );

// 8. Placeholder image
add_filter( 'woocommerce_placeholder_img_src', function() {
    return 'https://placehold.co/600x600/EAE9EC/362C49?text=Produit';
} );

// 9. Checkout billing fields — CP + Ville côte à côte
add_filter( 'woocommerce_billing_fields', function( $fields ) {
    if ( isset( $fields['billing_postcode'] ) ) {
        $fields['billing_postcode']['class'] = array( 'form-row-first', 'address-field', 'validate-required', 'validate-postcode' );
    }
    if ( isset( $fields['billing_city'] ) ) {
        $fields['billing_city']['class'] = array( 'form-row-last', 'address-field', 'validate-required' );
    }
    return $fields;
} );

// 9b. Override locale FR pour garder CP+Ville côte à côte
add_filter( 'woocommerce_get_country_locale', function( $locales ) {
    if ( isset( $locales['FR'] ) ) {
        $locales['FR']['postcode']['class'] = array( 'form-row-first', 'address-field', 'validate-required', 'validate-postcode' );
        $locales['FR']['city']['class']     = array( 'form-row-last', 'address-field', 'validate-required' );
    }
    return $locales;
} );

// 10. Shortcode inscription Pro
add_shortcode( 'wwb_inscription_pro', function() {
    ob_start();
    get_template_part( 'template-parts/inscription-pro' );
    return ob_get_clean();
} );

// 11. Custom archive layout via hooks
// Remove default WC wrappers
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

// Open custom wrapper + breadcrumbs + header + filters (archives only)
add_action( 'woocommerce_before_main_content', function() {
    if ( is_product() ) return; // Skip single product pages
    global $wp_query;
    $product_count = $wp_query->found_posts;
    $current_term  = get_queried_object();
    $collections   = 0;
    if ( $current_term && isset( $current_term->term_id ) ) {
        $children   = get_terms( [ 'taxonomy' => 'product_cat', 'parent' => $current_term->term_id, 'hide_empty' => true ] );
        $collections = is_array( $children ) ? count( $children ) : 0;
    } elseif ( is_shop() ) {
        $top = get_terms( [ 'taxonomy' => 'product_cat', 'parent' => 0, 'hide_empty' => true ] );
        $collections = is_array( $top ) ? count( $top ) : 0;
    }
    ?>
    <main class="wwb-shop">
        <div class="wwb-shop__breadcrumbs">
            <?php if ( function_exists( 'yoast_breadcrumb' ) ) yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' ); ?>
        </div>
        <header class="wwb-shop__header">
            <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                <h1 class="wwb-shop__title"><?php woocommerce_page_title(); ?></h1>
            <?php endif; ?>
            <?php do_action( 'woocommerce_archive_description' ); ?>
            <div class="wwb-shop__meta">
                <span class="wwb-shop__count"><strong><?php echo esc_html( $product_count ); ?> produit<?php echo $product_count > 1 ? 's' : ''; ?></strong></span>
                <?php if ( $collections > 0 ) : ?>
                    <span class="wwb-shop__meta-dot">&bull;</span>
                    <span><?php echo esc_html( $collections ); ?> collection<?php echo $collections > 1 ? 's' : ''; ?></span>
                <?php endif; ?>
            </div>
        </header>
        <div class="wwb-shop__filters">
            <div class="wwb-shop__filters-label">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M2 4h14M5 9h8M7 14h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                <span>Filtres</span>
            </div>
            <div class="wwb-shop__filters-pills">
                <?php foreach ( [ 'Format', 'Style', 'Couleur', 'Prix', 'Usage', 'Pièce' ] as $label ) : ?>
                    <button type="button" class="wwb-shop__pill"><?php echo esc_html( $label ); ?> <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
                <?php endforeach; ?>
            </div>
        </div>
    <?php
}, 5 );

// Close custom wrapper + add extra sections after loop (archives only)
add_action( 'woocommerce_after_main_content', function() {
    if ( is_product() ) return; // Skip single product pages
    ?>
    <!-- Explorer par style -->
    <section class="wwb-shop__styles">
        <h2>Explorer par style</h2>
        <div class="wwb-shop__styles-grid">
            <?php
            $styles = [
                [ 'label' => 'Terrazzo',       'slug' => 'terrazzo' ],
                [ 'label' => 'Béton Ciré',     'slug' => 'beton-cire' ],
                [ 'label' => 'Marbre',         'slug' => 'marbre' ],
                [ 'label' => 'Imitation Bois', 'slug' => 'imitation-bois' ],
                [ 'label' => 'Mosaïque',       'slug' => 'mosaique' ],
            ];
            foreach ( $styles as $style ) :
                $tag  = get_term_by( 'slug', $style['slug'], 'product_tag' );
                $href = $tag ? get_term_link( $tag ) : '#';
                $tid  = $tag ? get_term_meta( $tag->term_id, 'thumbnail_id', true ) : 0;
                $img  = $tid ? wp_get_attachment_image_url( $tid, 'medium' ) : '';
            ?>
                <a href="<?php echo esc_url( $href ); ?>" class="wwb-shop__style-card">
                    <div class="wwb-shop__style-card-img"><?php if ( $img ) : ?><img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $style['label'] ); ?>" loading="lazy" /><?php endif; ?></div>
                    <span><?php echo esc_html( $style['label'] ); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Comment choisir -->
    <section class="wwb-shop__guide">
        <h2>Comment choisir son carrelage ?</h2>
        <ul class="wwb-shop__guide-list">
            <?php foreach ( [
                "Déterminez l'usage (intérieur, extérieur, mur, sol)",
                'Choisissez le format adapté (30×30, 60×60, mosaïque…)',
                'Vérifiez la résistance au glissement (norme R9 à R13)',
            ] as $tip ) : ?>
                <li>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="12" fill="var(--wp--preset--color--secondary)"/><path d="M8 12l3 3 5-5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span><?php echo esc_html( $tip ); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="#" class="wwb-shop__guide-btn">Lire le guide complet &rarr;</a>
    </section>

    <!-- Nos conseils -->
    <section class="wwb-shop__blog">
        <h2>Nos conseils carrelage</h2>
        <p class="wwb-shop__blog-intro">Inspirations, tutoriels et conseils pour réussir votre projet.</p>
        <div class="wwb-shop__blog-grid">
            <?php
            $q = new WP_Query( [ 'post_type' => 'post', 'posts_per_page' => 3, 'post_status' => 'publish' ] );
            if ( $q->have_posts() ) :
                while ( $q->have_posts() ) : $q->the_post(); ?>
                    <a href="<?php the_permalink(); ?>" class="wwb-shop__blog-card">
                        <div class="wwb-shop__blog-card-img"><?php if ( has_post_thumbnail() ) the_post_thumbnail( 'medium', [ 'loading' => 'lazy' ] ); ?></div>
                        <h3 class="wwb-shop__blog-card-title"><?php the_title(); ?></h3>
                    </a>
                <?php endwhile; wp_reset_postdata();
            else : ?>
                <p class="wwb-shop__blog-empty">Contenus à venir — restez connecté !</p>
            <?php endif; ?>
        </div>
    </section>
    </main>
    <?php
}, 15 );

// ─────────────────────────────────────────────
// Functions
// ─────────────────────────────────────────────

function wwb_v2_variation_price_format( $price, $product ) {
    $min = $product->get_variation_price( 'min', true );
    $max = $product->get_variation_price( 'max', true );
    if ( $min != $max ) {
        return sprintf( __( 'À partir de %1$s', 'woocommerce' ), wc_price( $min ) );
    }
    return wc_price( $min );
}

function wwb_v2_suffixe_m2( $price, $product ) {
    if ( $product && has_term( 'carrelage', 'product_cat', $product->get_id() ) ) {
        if ( ! str_contains( $price, '/m²' ) ) {
            $price .= ' /m²';
        }
    }
    return $price;
}

function wwb_v2_cart_fragment( $fragments ) {
    global $woocommerce;
    ob_start();
    ?>
    <a class="cart-customlocation" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'woocommerce' ); ?>">
        <?php echo sprintf( _n( '%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woocommerce' ), $woocommerce->cart->cart_contents_count ); ?> – <?php echo $woocommerce->cart->get_cart_total(); ?>
    </a>
    <?php
    $fragments['a.cart-customlocation'] = ob_get_clean();
    return $fragments;
}

function wwb_v2_color_swatches( $html, $args ) {
    if ( 'pa_couleurs' !== $args['attribute'] ) return $html;
    $options    = $args['options'];
    $product_id = $args['product_id'];
    if ( empty( $options ) && ! empty( $args['selected'] ) ) $options = array( $args['selected'] );
    $terms = wc_get_product_terms( $product_id, $args['attribute'], array( 'fields' => 'all' ) );
    if ( empty( $terms ) ) return $html;
    $html = '<div class="wwb-color-swatches">';
    foreach ( $terms as $term ) {
        $color = function_exists( 'get_field' ) ? get_field( 'product_color_pick', 'pa_couleurs_' . $term->term_id ) : '#ffffff';
        if ( empty( $color ) ) $color = '#ffffff';
        $selected = in_array( $term->slug, $options, true ) ? 'selected' : '';
        $html .= '<button type="button" class="wwb-color-swatches__swatch ' . esc_attr( $selected ) . '" data-value="' . esc_attr( $term->slug ) . '" style="background-color:' . esc_attr( $color ) . ';" title="' . esc_attr( $term->name ) . '" aria-label="' . esc_attr( $term->name ) . '"></button>';
    }
    $html .= '</div>';
    return $html;
}
