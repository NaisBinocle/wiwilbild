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

// 4. Cleanup — remove breadcrumb & loop add-to-cart
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

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
