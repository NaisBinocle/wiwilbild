<?php
/**
 * WWB — Setup test carrelage product
 *
 * Crée un carrelage de test "Vintage Soleil Menthe 33×33" pour tester
 * le template fiche produit carrelage (calculateur surface, swatches, IA encart).
 *
 * Trigger (admin only): /wp-admin/?wwb_setup_carrelage_test=1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_init', 'wwb_setup_carrelage_test_trigger' );
function wwb_setup_carrelage_test_trigger() {
	if ( ! current_user_can( 'manage_options' ) ) return;
	if ( empty( $_GET['wwb_setup_carrelage_test'] ) ) return;

	$result = wwb_setup_carrelage_test_run();

	wp_die(
		'<h1>Setup Carrelage Test</h1><pre>' . esc_html( print_r( $result, true ) ) . '</pre>' .
		'<p><a href="' . esc_url( get_permalink( $result['product_id'] ?? 0 ) ) . '">Voir le produit</a></p>',
		'Setup Carrelage Test',
		array( 'response' => 200 )
	);
}

function wwb_setup_carrelage_test_run() {
	$log = array();

	// 1. Catégorie "carrelage" (parent)
	$parent_cat = term_exists( 'carrelage', 'product_cat' );
	if ( ! $parent_cat ) {
		$parent_cat = wp_insert_term( 'Carrelage', 'product_cat', array( 'slug' => 'carrelage' ) );
		$log[] = 'Catégorie carrelage créée';
	}
	$parent_id = is_array( $parent_cat ) ? (int) $parent_cat['term_id'] : 0;

	// 2. Sous-catégorie "carreaux-de-ciment"
	$sub_cat = term_exists( 'carreaux-de-ciment', 'product_cat' );
	if ( ! $sub_cat ) {
		$sub_cat = wp_insert_term(
			'Carreaux de ciment',
			'product_cat',
			array( 'slug' => 'carreaux-de-ciment', 'parent' => $parent_id )
		);
		$log[] = 'Sous-catégorie carreaux-de-ciment créée';
	}
	$sub_id = is_array( $sub_cat ) ? (int) $sub_cat['term_id'] : 0;

	// 3. Attribut global pa_couleurs (si absent)
	global $wpdb;
	$attr_slug = 'couleurs';
	$attr_id   = wc_attribute_taxonomy_id_by_name( $attr_slug );
	if ( ! $attr_id ) {
		$attr_id = wc_create_attribute( array(
			'name'         => 'Coloris',
			'slug'         => $attr_slug,
			'type'         => 'select',
			'order_by'     => 'menu_order',
			'has_archives' => false,
		) );
		$log[] = 'Attribut pa_couleurs créé';
		// Force refresh taxonomy registration
		delete_transient( 'wc_attribute_taxonomies' );
		register_taxonomy( 'pa_couleurs', 'product' );
	}

	// 4. Termes couleurs (4)
	$colors = array(
		'menthe'    => array( 'label' => 'Menthe',    'hex' => '#A8D5BA' ),
		'bordeaux'  => array( 'label' => 'Bordeaux',  'hex' => '#8B2635' ),
		'orange'    => array( 'label' => 'Orange',    'hex' => '#E8A95C' ),
		'bleu'      => array( 'label' => 'Bleu',      'hex' => '#4A6FA5' ),
	);
	foreach ( $colors as $slug => $data ) {
		$term = get_term_by( 'slug', $slug, 'pa_couleurs' );
		if ( ! $term ) {
			$t = wp_insert_term( $data['label'], 'pa_couleurs', array( 'slug' => $slug ) );
			if ( ! is_wp_error( $t ) && function_exists( 'update_field' ) ) {
				update_field( 'product_color_pick', $data['hex'], 'pa_couleurs_' . $t['term_id'] );
			}
			$log[] = "Terme couleur {$slug} créé";
		}
	}

	// 5. Produit parent (variable)
	$product_slug = 'carrelage-vintage-soleil-menthe-33x33';
	$existing     = get_page_by_path( $product_slug, OBJECT, 'product' );

	if ( $existing ) {
		$product_id = $existing->ID;
		$log[]      = 'Produit existant, ID=' . $product_id;
	} else {
		$product = new WC_Product_Variable();
		$product->set_name( 'Carrelage sol effet carreaux de ciment Vintage Soleil menthe 33×33 cm' );
		$product->set_slug( $product_slug );
		$product->set_status( 'publish' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_description(
			"Inspiré des authentiques carreaux de ciment, le modèle Soleil menthe apporte une touche rétro-chic à votre intérieur. Son motif géométrique en étoile s'associe parfaitement aux intérieurs scandinaves, bohèmes ou contemporains.\n\n" .
			"Fabriqué en grès cérame émaillé, il offre la robustesse moderne du carrelage tout en conservant l'âme des carreaux de ciment traditionnels. Posable en sol et mural dans toutes les pièces, y compris sur plancher chauffant."
		);
		$product->set_short_description( 'Carrelage grès cérame émaillé 33,15 × 33,15 cm — effet carreaux de ciment vintage, motif étoile.' );
		$product->set_category_ids( array( $parent_id, $sub_id ) );
		$product->set_regular_price( '42.54' );
		$product->set_sale_price( '31.90' );
		$product->set_price( '31.90' );

		// Dimensions physiques du carreau (pour le calculateur via variation)
		$product->set_length( '33.15' );
		$product->set_width( '33.15' );
		$product->set_weight( '1.2' );

		// Attribut couleurs en variation
		$attr = new WC_Product_Attribute();
		$attr->set_id( wc_attribute_taxonomy_id_by_name( 'couleurs' ) );
		$attr->set_name( 'pa_couleurs' );
		$attr->set_options( array_map( function( $slug ) {
			$t = get_term_by( 'slug', $slug, 'pa_couleurs' );
			return $t ? $t->term_id : 0;
		}, array_keys( $colors ) ) );
		$attr->set_position( 0 );
		$attr->set_visible( true );
		$attr->set_variation( true );
		$product->set_attributes( array( $attr ) );

		// Meta carrelage : surface par boîte + pièces
		$product->update_meta_data( '_wwb_surface_par_boite', '1.32' );
		$product->update_meta_data( '_wwb_pieces_par_boite', '12' );
		$product->update_meta_data( '_wwb_matiere',          'Grès cérame émaillé' );
		$product->update_meta_data( '_wwb_finition',         'Mate · bords non rectifiés' );
		$product->update_meta_data( '_wwb_resistance',       'Gr4 — très résistant' );
		$product->update_meta_data( '_wwb_usages',           'Intérieur sol & mural · plancher chauffant compatible' );
		$product->update_meta_data( '_wwb_epaisseur',        '8 mm' );

		$product_id = $product->save();
		$log[]      = 'Produit créé, ID=' . $product_id;

		// Variations (une par couleur)
		foreach ( $colors as $slug => $data ) {
			$variation = new WC_Product_Variation();
			$variation->set_parent_id( $product_id );
			$variation->set_attributes( array( 'pa_couleurs' => $slug ) );
			$variation->set_regular_price( '42.54' );
			$variation->set_sale_price( '31.90' );
			$variation->set_price( '31.90' );
			$variation->set_stock_status( 'instock' );
			$variation->set_manage_stock( false );
			$variation->set_length( '33.15' );
			$variation->set_width( '33.15' );
			$variation->set_weight( '1.2' );
			$variation->save();
		}
		$log[] = count( $colors ) . ' variations créées';

		WC_Product_Variable::sync( $product_id );
	}

	return array(
		'product_id' => $product_id,
		'url'        => get_permalink( $product_id ),
		'log'        => $log,
	);
}
