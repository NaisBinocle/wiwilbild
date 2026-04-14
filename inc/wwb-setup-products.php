<?php
/**
 * WWB — Setup Phase 1 : Catalogue Fenêtres PVC
 *
 * Crée les produits variables par dimension pour chaque sous-catégorie.
 * Modèle hybride : taille intrinsèque (pa_taille), vitrage en variation (pa_vitrage).
 * Scripts idempotents — safe re-run.
 *
 * Triggers (admin only) :
 *   /wp-admin/?wwb_setup_format=2v    → format spécifique (1v, 2v, 3v, cf, ob)
 *   /wp-admin/?wwb_setup_format=all   → tous les formats
 *   /wp-admin/?wwb_setup_cleanup=1    → supprime les produits legacy parent
 *
 * Dimensions sourcées depuis Obsidian : Dimensions fenêtres.md
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ─────────────────────────────────────────────
// Config catalogue par format
// ─────────────────────────────────────────────
function wwb_setup_formats_config() {
    return array(
        '1v' => array(
            'cat_slug'     => 'fenetres-pvc-1-vantail',
            'product_slug' => 'fenetre-pvc-1-vantail',
            'cat_name'     => 'Fenêtres PVC 1 vantail',
            'title_prefix' => 'Fenêtre PVC 1 vantail',
            'short_label'  => '1 vantail',
            'description'  => 'Fenêtre PVC 1 vantail, ouverture à la française ou oscillo-battant. Fabrication française, cote tableau.',
            'base_price'   => 180,
            'triple_extra' => 60,
            'dimensions'   => array(
                '45x60', '60x80', '60x95', '75x60', '60x120',
                '75x100', '80x95', '80x120', '60x135', '75x140',
            ),
        ),
        '2v' => array(
            'cat_slug'     => 'fenetres-pvc-2-vantaux',
            'product_slug' => 'fenetre-pvc-2-vantaux',
            'cat_name'     => 'Fenêtres PVC 2 vantaux',
            'title_prefix' => 'Fenêtre PVC 2 vantaux',
            'short_label'  => '2 vantaux',
            'description'  => 'Fenêtre PVC 2 vantaux, ouverture à la française ou oscillo-battant. Fabrication française, cote tableau.',
            'base_price'   => 310,
            'triple_extra' => 75,
            'dimensions'   => array(
                '100x100', '120x115', '120x120', '100x135', '120x135',
                '140x120', '140x135', '120x140', '100x115', '140x115',
            ),
        ),
        '3v' => array(
            'cat_slug'     => 'fenetres-pvc-3-vantaux',
            'product_slug' => 'fenetre-pvc-3-vantaux',
            'cat_name'     => 'Fenêtres PVC 3 vantaux',
            'title_prefix' => 'Fenêtre PVC 3 vantaux',
            'short_label'  => '3 vantaux',
            'description'  => 'Fenêtre PVC 3 vantaux, ouverture à la française ou oscillo-battant. Fabrication française, cote tableau.',
            'base_price'   => 520,
            'triple_extra' => 120,
            'dimensions'   => array(
                '180x120', '180x135', '210x120', '210x135', '180x140',
                '240x120', '240x135', '210x140', '180x115', '240x140',
            ),
        ),
        'cf' => array(
            'cat_slug'     => 'chassis-fixe-pvc',
            'product_slug' => 'chassis-fixe-pvc',
            'cat_name'     => 'Châssis fixe PVC',
            'title_prefix' => 'Châssis fixe PVC',
            'short_label'  => 'Châssis fixe',
            'description'  => 'Châssis fixe PVC, apport lumineux maximal (cage d\'escalier, imposte, allège). Fabrication française, cote tableau.',
            'base_price'   => 140,
            'triple_extra' => 55,
            'dimensions'   => array(
                '40x50', '50x50', '60x60', '40x80', '60x80',
                '60x95', '80x60', '80x80', '80x95', '100x100',
            ),
        ),
        'ob' => array(
            'cat_slug'     => 'oscillo-battant-pvc',
            'product_slug' => 'fenetre-pvc-oscillo-battant',
            'cat_name'     => 'Oscillo-battant PVC',
            'title_prefix' => 'Fenêtre PVC oscillo-battant',
            'short_label'  => 'Oscillo-battant',
            'description'  => 'Fenêtre PVC 2 vantaux oscillo-battant, double ouverture (pivotante + basculante) pour aération sécurisée. Fabrication française, cote tableau.',
            'base_price'   => 340,
            'triple_extra' => 80,
            'dimensions'   => array( '120x120', '120x135', '140x135' ),
        ),
    );
}

// ─────────────────────────────────────────────
// Admin triggers
// ─────────────────────────────────────────────
add_action( 'admin_init', function() {
    if ( ! current_user_can( 'manage_woocommerce' ) ) return;
    if ( ! class_exists( 'WooCommerce' ) ) return;

    // Format setup (single or "all")
    if ( ! empty( $_GET['wwb_setup_format'] ) ) {
        $format = sanitize_key( $_GET['wwb_setup_format'] );
        $log = array();
        $configs = wwb_setup_formats_config();
        if ( $format === 'all' ) {
            foreach ( $configs as $key => $cfg ) {
                $log[] = "══════════════ {$cfg['title_prefix']} ══════════════";
                $log = array_merge( $log, wwb_setup_format_run( $cfg ) );
                $log[] = '';
            }
        } elseif ( isset( $configs[ $format ] ) ) {
            $log = wwb_setup_format_run( $configs[ $format ] );
        } else {
            $log[] = "✗ Format inconnu : {$format}. Options : " . implode( ', ', array_keys( $configs ) ) . ', all';
        }
        wwb_setup_render( 'WWB — Setup catalogue', $log );
    }

    // Backwards compat legacy trigger
    if ( ! empty( $_GET['wwb_setup_2v'] ) ) {
        $configs = wwb_setup_formats_config();
        wwb_setup_render( 'WWB — Setup 2 vantaux', wwb_setup_format_run( $configs['2v'] ) );
    }

    // Cleanup legacy products
    if ( ! empty( $_GET['wwb_setup_cleanup'] ) ) {
        wwb_setup_render( 'WWB — Cleanup produits legacy', wwb_setup_cleanup_run() );
    }
} );

function wwb_setup_render( $title, $log ) {
    wp_die(
        '<h1>' . esc_html( $title ) . '</h1><pre>' . esc_html( implode( "\n", $log ) ) . '</pre><p><a href="' . esc_url( admin_url( 'edit.php?post_type=product' ) ) . '">→ Voir les produits</a></p>',
        esc_html( $title ),
        array( 'response' => 200 )
    );
}

// ─────────────────────────────────────────────
// Setup format (générique)
// ─────────────────────────────────────────────
function wwb_setup_format_run( $cfg ) {
    $log = array();

    // Parent category (fenetres-pvc)
    $parent_cat = get_term_by( 'slug', 'fenetres-pvc', 'product_cat' );
    if ( ! $parent_cat ) {
        $log[] = '✗ Catégorie parent "fenetres-pvc" introuvable. Abort.';
        return $log;
    }

    // Sous-catégorie (créée si besoin)
    $cat = get_term_by( 'slug', $cfg['cat_slug'], 'product_cat' );
    if ( ! $cat ) {
        $res = wp_insert_term( $cfg['cat_name'], 'product_cat', array(
            'slug'   => $cfg['cat_slug'],
            'parent' => $parent_cat->term_id,
        ) );
        if ( is_wp_error( $res ) ) {
            $log[] = '✗ Création sous-cat échouée : ' . $res->get_error_message();
            return $log;
        }
        $cat = get_term( $res['term_id'], 'product_cat' );
        $log[] = "✓ Sous-cat créée : {$cat->name} (id {$cat->term_id})";
    } else {
        $log[] = "= Sous-cat : {$cat->name} (id {$cat->term_id})";
    }

    // Attributs globaux pa_taille + pa_vitrage
    $taille_attr_id  = wwb_setup_get_or_create_attribute( 'taille',  'Taille' );
    $vitrage_attr_id = wwb_setup_get_or_create_attribute( 'vitrage', 'Vitrage' );
    if ( ! $taille_attr_id || ! $vitrage_attr_id ) {
        $log[] = '✗ Création attributs échouée.';
        return $log;
    }

    // Termes vitrage
    foreach ( array(
        'double-vitrage' => 'Double vitrage',
        'triple-vitrage' => 'Triple vitrage',
    ) as $slug => $name ) {
        if ( ! get_term_by( 'slug', $slug, 'pa_vitrage' ) ) {
            wp_insert_term( $name, 'pa_vitrage', array( 'slug' => $slug ) );
            $log[] = "✓ Terme vitrage : {$name}";
        }
    }

    // Produits par dimension
    foreach ( $cfg['dimensions'] as $dim ) {
        $log = array_merge( $log, wwb_setup_create_product( $dim, $cat->term_id, $cfg ) );
    }

    $log[] = '── Terminé. Idempotent : safe re-run.';
    return $log;
}

function wwb_setup_get_or_create_attribute( $slug, $name ) {
    global $wpdb;
    $existing = $wpdb->get_var( $wpdb->prepare(
        "SELECT attribute_id FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name = %s",
        $slug
    ) );
    if ( $existing ) return (int) $existing;

    $attr_id = wc_create_attribute( array(
        'name'         => $name,
        'slug'         => $slug,
        'type'         => 'select',
        'order_by'     => 'menu_order',
        'has_archives' => false,
    ) );
    if ( is_wp_error( $attr_id ) ) return false;

    $taxonomy = wc_attribute_taxonomy_name( $slug );
    register_taxonomy( $taxonomy, 'product', array(
        'hierarchical' => false,
        'labels'       => array( 'name' => $name ),
        'public'       => true,
        'show_ui'      => false,
        'query_var'    => true,
        'rewrite'      => false,
    ) );

    delete_transient( 'wc_attribute_taxonomies' );
    return $attr_id;
}

function wwb_setup_create_product( $dim, $cat_id, $cfg ) {
    $log = array();

    list( $w, $h ) = explode( 'x', $dim );
    $label = "{$w} × {$h} cm";
    $title = "{$cfg['title_prefix']} {$label}";
    $slug  = sanitize_title( ( $cfg['product_slug'] ?? $cfg['cat_slug'] ) . '-' . $dim );

    $existing = get_page_by_path( $slug, OBJECT, 'product' );
    if ( $existing ) {
        $log[] = "= {$title} (id {$existing->ID})";
        return $log;
    }

    // Term taille
    $taille_term = get_term_by( 'slug', $dim, 'pa_taille' );
    if ( ! $taille_term ) {
        $res = wp_insert_term( $label, 'pa_taille', array( 'slug' => $dim ) );
        if ( is_wp_error( $res ) ) {
            $log[] = "✗ Term taille {$dim} : " . $res->get_error_message();
            return $log;
        }
        $taille_term = get_term( $res['term_id'], 'pa_taille' );
    }

    $base  = (int) $cfg['base_price'];
    $extra = (int) $cfg['triple_extra'];

    // Produit variable
    $product = new WC_Product_Variable();
    $product->set_name( $title );
    $product->set_slug( $slug );
    $product->set_status( 'publish' );
    $product->set_catalog_visibility( 'visible' );
    $product->set_description( "{$cfg['description']} Dimension {$label} (largeur × hauteur). Disponible en double ou triple vitrage." );
    $product->set_short_description( "{$cfg['short_label']} · {$label} · à partir de {$base} €" );
    $product->set_category_ids( array( $cat_id ) );

    $attributes = array();

    // pa_taille (visible, non-variation)
    $attr_taille = new WC_Product_Attribute();
    $attr_taille->set_id( wc_attribute_taxonomy_id_by_name( 'pa_taille' ) );
    $attr_taille->set_name( 'pa_taille' );
    $attr_taille->set_options( array( $taille_term->term_id ) );
    $attr_taille->set_visible( true );
    $attr_taille->set_variation( false );
    $attributes[] = $attr_taille;

    // pa_vitrage (variation)
    $vit_double = get_term_by( 'slug', 'double-vitrage', 'pa_vitrage' );
    $vit_triple = get_term_by( 'slug', 'triple-vitrage', 'pa_vitrage' );
    $attr_vitrage = new WC_Product_Attribute();
    $attr_vitrage->set_id( wc_attribute_taxonomy_id_by_name( 'pa_vitrage' ) );
    $attr_vitrage->set_name( 'pa_vitrage' );
    $attr_vitrage->set_options( array( $vit_double->term_id, $vit_triple->term_id ) );
    $attr_vitrage->set_visible( true );
    $attr_vitrage->set_variation( true );
    $attributes[] = $attr_vitrage;

    $product->set_attributes( $attributes );
    $product_id = $product->save();

    // Variations
    foreach ( array(
        'double-vitrage' => $base,
        'triple-vitrage' => $base + $extra,
    ) as $term_slug => $price ) {
        $var = new WC_Product_Variation();
        $var->set_parent_id( $product_id );
        $var->set_attributes( array( 'pa_vitrage' => $term_slug ) );
        $var->set_regular_price( $price );
        $var->set_status( 'publish' );
        $var->save();
    }

    WC_Product_Variable::sync( $product_id );

    $log[] = "✓ {$title} (id {$product_id}) — {$base} € / +{$extra} €";
    return $log;
}

// ─────────────────────────────────────────────
// Cleanup legacy parent-level products
// ─────────────────────────────────────────────
function wwb_setup_cleanup_run() {
    $log = array();

    // Slugs/titles des produits legacy à supprimer. On conserve "Fenêtre PVC sur mesure".
    $legacy_titles = array(
        'Fenêtre coulissante PVC',
        'Fenetre fixe / Chassis fixe PVC',
        'Fenêtre PVC 1 vantail',
        'Fenêtre PVC 2 vantaux',
        'Fenêtre PVC 3 vantaux',
        'Fenêtre PVC avec volet roulant',
        'Fenêtre PVC oscillo-battant',
        'Fenêtre PVC Oscillo-Battante',
        'Fenêtre PVC rénovation',
        'Fenêtre soufflet PVC',
    );

    $parent_cat = get_term_by( 'slug', 'fenetres-pvc', 'product_cat' );
    if ( ! $parent_cat ) {
        $log[] = '✗ Parent cat introuvable.';
        return $log;
    }

    foreach ( $legacy_titles as $title ) {
        // Match exact title, uniquement si directement dans "fenetres-pvc" (pas dans une sous-cat)
        $products = get_posts( array(
            'post_type'      => 'product',
            'post_status'    => 'any',
            'title'          => $title,
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'tax_query'      => array(
                array(
                    'taxonomy'         => 'product_cat',
                    'field'            => 'term_id',
                    'terms'            => $parent_cat->term_id,
                    'include_children' => false, // uniquement directement dans fenetres-pvc
                ),
            ),
        ) );

        if ( empty( $products ) ) {
            $log[] = "= Aucun produit legacy trouvé : {$title}";
            continue;
        }

        foreach ( $products as $pid ) {
            // Supprime variations aussi
            $variations = get_posts( array(
                'post_type'      => 'product_variation',
                'post_parent'    => $pid,
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'post_status'    => 'any',
            ) );
            foreach ( $variations as $vid ) {
                wp_delete_post( $vid, true );
            }
            wp_delete_post( $pid, true );
            $log[] = "✗ Supprimé : {$title} (id {$pid}) — " . count( $variations ) . ' variation(s)';
        }
    }

    $log[] = '';
    $log[] = '── Terminé. "Fenêtre PVC sur mesure" conservée.';
    return $log;
}
