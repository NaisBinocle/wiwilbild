<?php
/**
 * WWB V2 — Thème FSE Wiwilbild
 *
 * functions.php léger : setup FSE, enqueue assets, pattern categories.
 * Les hooks WooCommerce sont dans mu-plugins/wwb-woocommerce.php.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ─────────────────────────────────────────────
// Theme Setup
// ─────────────────────────────────────────────
function wwb_v2_setup() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'html5', array(
        'search-form', 'gallery', 'caption', 'style', 'script',
    ));

    // Editor styles
    add_editor_style( 'assets/css/custom.css' );
    add_editor_style( 'assets/css/woocommerce.css' );
}
add_action( 'after_setup_theme', 'wwb_v2_setup' );

// ─────────────────────────────────────────────
// Pattern Categories
// ─────────────────────────────────────────────
function wwb_v2_register_pattern_categories() {
    register_block_pattern_category( 'wwb-homepage', array(
        'label' => __( 'Wiwilbild — Accueil', 'wwb-v2' ),
    ));
    register_block_pattern_category( 'wwb-product', array(
        'label' => __( 'Wiwilbild — Produits', 'wwb-v2' ),
    ));
    register_block_pattern_category( 'wwb-general', array(
        'label' => __( 'Wiwilbild — Général', 'wwb-v2' ),
    ));
}
add_action( 'init', 'wwb_v2_register_pattern_categories' );

// ─────────────────────────────────────────────
// Enqueue Frontend Assets
// ─────────────────────────────────────────────
function wwb_v2_enqueue_assets() {
    $theme_uri = get_template_directory_uri();
    $theme_dir = get_template_directory();
    $version   = wp_get_theme()->get( 'Version' );

    // CSS global
    wp_enqueue_style( 'wwb-v2-custom', $theme_uri . '/assets/css/custom.css', array(), $version );

    // WooCommerce CSS (uniquement sur les pages WC)
    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_style( 'wwb-v2-woocommerce', $theme_uri . '/assets/css/woocommerce.css', array(), $version );
    }

    // JS conditionnel — pages produit uniquement
    if ( function_exists( 'is_product' ) && is_product() ) {
        wp_enqueue_script( 'wwb-v2-swatches', $theme_uri . '/assets/js/swatches.js', array(), $version, true );
        wp_enqueue_script( 'wwb-v2-calculator', $theme_uri . '/assets/js/calculator.js', array(), $version, true );
        wp_enqueue_script( 'wwb-v2-hotspots', $theme_uri . '/assets/js/hotspots.js', array(), $version, true );
    }
}
add_action( 'wp_enqueue_scripts', 'wwb_v2_enqueue_assets' );

// ─────────────────────────────────────────────
// Remove WP Generator meta tag
// ─────────────────────────────────────────────
remove_action( 'wp_head', 'wp_generator' );

// ─────────────────────────────────────────────
// Favicon SVG
// ─────────────────────────────────────────────
add_action( 'wp_head', function() {
    $favicon = get_template_directory_uri() . '/assets/img/favicon.svg';
    echo '<link rel="icon" href="' . esc_url( $favicon ) . '" type="image/svg+xml">' . "\n";
} );

// ─────────────────────────────────────────────
// Configurateur Fenêtre Sur Mesure
// ─────────────────────────────────────────────
require_once get_template_directory() . '/inc/class-wwb-configurator.php';

// ─────────────────────────────────────────────
// Hotspots interactifs sur images produits
// ─────────────────────────────────────────────
function wwb_get_product_image_hotspots( $product_id ) {
    $tid = get_post_thumbnail_id( $product_id );
    if ( ! $tid ) return array();
    $data = get_post_meta( $tid, '_wwb_hotspots', true );
    if ( empty( $data ) ) return array();
    $h = json_decode( $data, true );
    return is_array( $h ) ? $h : array();
}

function wwb_display_product_hotspots( $hotspots ) {
    if ( empty( $hotspots ) ) return;
    echo '<div class="wwb-hotspots" id="product-hotspots">';
    foreach ( $hotspots as $i => $h ) {
        $x = isset( $h['x'] ) ? floatval( $h['x'] ) : 50;
        $y = isset( $h['y'] ) ? floatval( $h['y'] ) : 50;
        echo '<div class="wwb-hotspots__point" style="left:' . $x . '%;top:' . $y . '%;" data-index="' . $i . '">';
        echo '<span class="wwb-hotspots__pulse"></span><span class="wwb-hotspots__dot">+</span>';
        echo '<div class="wwb-hotspots__tooltip"><strong>' . esc_html( $h['title'] ?? '' ) . '</strong>';
        if ( ! empty( $h['description'] ) ) echo '<p>' . esc_html( $h['description'] ) . '</p>';
        echo '</div></div>';
    }
    echo '</div>';
}

add_action( 'wp_footer', 'wwb_v2_hotspots_init_script' );
function wwb_v2_hotspots_init_script() {
    if ( ! function_exists( 'is_product' ) || ! is_product() ) return;
    global $product;
    if ( ! $product ) return;
    $hotspots = wwb_get_product_image_hotspots( $product->get_id() );
    if ( empty( $hotspots ) ) return;
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var html = <?php ob_start(); wwb_display_product_hotspots( $hotspots ); echo json_encode( ob_get_clean() ); ?>;
        var img = document.querySelector('.woocommerce-product-gallery__image');
        if (img) { img.style.position = 'relative'; img.insertAdjacentHTML('beforeend', html); }
    });
    </script>
    <?php
}

// ACF Hotspots admin field
$wwb_hotspots_field = get_template_directory() . '/acf-hotspots-field.php';
if ( ! file_exists( $wwb_hotspots_field ) ) {
    $wwb_hotspots_field = ABSPATH . 'wp-content/themes/WWB/acf-hotspots-field.php';
}
if ( file_exists( $wwb_hotspots_field ) ) {
    require_once $wwb_hotspots_field;
}
