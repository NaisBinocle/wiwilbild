<?php
/**
 * WWB V2 — Thème FSE Wiwilbild
 *
 * functions.php léger : setup FSE, enqueue assets, pattern categories.
 * Les hooks WooCommerce sont dans inc/woocommerce.php.
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
    add_editor_style( 'assets/css/v3-homepage.css' );
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

    // Header custom (site-wide)
    wp_enqueue_style( 'wwb-v2-header', $theme_uri . '/assets/css/header.css', array( 'wwb-v2-custom' ), $version );

    // WooCommerce CSS (uniquement sur les pages WC)
    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_style( 'wwb-v2-woocommerce', $theme_uri . '/assets/css/woocommerce.css', array(), $version . '-' . filemtime( $theme_dir . '/assets/css/woocommerce.css' ) );
    }

    // V3 Homepage CSS (page-home-v2 template only)
    if ( is_page( 'home-v2' ) ) {
        $v3_css = $theme_dir . '/assets/css/v3-homepage.css';
        wp_enqueue_style( 'wwb-v3-homepage', $theme_uri . '/assets/css/v3-homepage.css', array(), file_exists( $v3_css ) ? filemtime( $v3_css ) : $version );
    }

    // JS Qty stepper (panier + fiches produit menuiserie)
    if ( ( function_exists( 'is_cart' ) && is_cart() ) || ( function_exists( 'is_product' ) && is_product() ) ) {
        wp_enqueue_script( 'wwb-v2-cart-qty', $theme_uri . '/assets/js/cart-qty.js', array( 'jquery' ), $version, true );
    }

    // JS conditionnel — pages produit uniquement
    if ( function_exists( 'is_product' ) && is_product() ) {
        wp_enqueue_script( 'wwb-v2-swatches', $theme_uri . '/assets/js/swatches.js', array(), $version, true );
        wp_enqueue_script( 'wwb-v2-product-single', $theme_uri . '/assets/js/product-single.js', array(), $version . '-' . filemtime( $theme_dir . '/assets/js/product-single.js' ), true );
        wp_enqueue_script( 'wwb-v2-hotspots', $theme_uri . '/assets/js/hotspots.js', array(), $version, true );

        // Calculator only for carrelage products
        global $post;
        if ( $post && has_term( 'carrelage', 'product_cat', $post->ID ) ) {
            wp_enqueue_script( 'wwb-v2-calculator', $theme_uri . '/assets/js/calculator.js', array( 'jquery' ), $version . '-' . filemtime( $theme_dir . '/assets/js/calculator.js' ), true );
        }
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
// Fix hardcoded links in HTML template parts
// Uses home_url() so it works in subdirectory (dev) and root (prod)
// ─────────────────────────────────────────────
add_filter( 'render_block', function( $content, $block ) {
    if ( empty( $block['blockName'] ) || $block['blockName'] !== 'core/template-part' ) return $content;
    $base = home_url();

    // Logo link
    $content = str_replace( 'href="/wiwilbild/"', 'href="' . esc_url( $base ) . '/"', $content );

    // Logo image src
    $content = str_replace(
        'src="/wiwilbild/wp-content/themes/wwb-v2/',
        'src="' . esc_url( get_template_directory_uri() ) . '/',
        $content
    );

    // Rewrite all relative navigation links to use home_url()
    // Matches href="/some-path/" but NOT href="/wiwilbild/" (already handled) or href="#"
    $content = preg_replace_callback(
        '#href="(/(?!wiwilbild/)[a-z0-9\-/]+/)"#i',
        function( $m ) {
            return 'href="' . esc_url( home_url( $m[1] ) ) . '"';
        },
        $content
    );

    return $content;
}, 10, 2 );

// ─────────────────────────────────────────────
// Header custom (shortcode [wwb_header])
// ─────────────────────────────────────────────
require_once get_template_directory() . '/inc/wwb-header.php';

// ─────────────────────────────────────────────
// Setup Products Phase 1 (trigger /wp-admin/?wwb_setup_2v=1)
// ─────────────────────────────────────────────
if ( is_admin() ) {
    require_once get_template_directory() . '/inc/wwb-setup-products.php';
    require_once get_template_directory() . '/inc/wwb-setup-carrelage-test.php';
}

// ─────────────────────────────────────────────
// WooCommerce Customizations
// ─────────────────────────────────────────────
if ( class_exists( 'WooCommerce' ) ) {
    require_once get_template_directory() . '/inc/woocommerce.php';
}

// ─────────────────────────────────────────────
// Setup Pages (création auto des pages légales/support)
// ─────────────────────────────────────────────
require_once get_template_directory() . '/inc/wwb-setup-pages.php';

// ─────────────────────────────────────────────
// Bandeau Cookies RGPD
// ─────────────────────────────────────────────
require_once get_template_directory() . '/inc/wwb-cookie-banner.php';

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
