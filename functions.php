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
