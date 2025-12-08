<?php

// Champs ACF pour les hotspots produit
require_once get_template_directory() . '/acf-hotspots-field.php';

// Sections flexibles ACF pour la page d'accueil
require_once get_template_directory() . '/acf-homepage-sections.php';

/**
 * Support FSE (Full Site Editing) hybride
 */
function wwb_setup_theme() {
    // Menus
    register_nav_menus(array(
        'menu-principal' => __('Menu Principal', 'textdomain'),
        'menu-mobile'    => __('Menu Mobile', 'textdomain'),
    ));

    // Support blocs Gutenberg
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');

    // Support éditeur de site
    add_theme_support('block-templates');
    add_theme_support('block-template-parts');

    // WooCommerce
    add_theme_support('woocommerce');

    // Images
    add_theme_support('post-thumbnails');

    // Titre automatique
    add_theme_support('title-tag');

    // HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
}
add_action('after_setup_theme', 'wwb_setup_theme');

/**
 * Enregistrer la catégorie de patterns Wiwilbild
 */
function wwb_register_pattern_categories() {
    register_block_pattern_category('wwb-homepage', array(
        'label' => __('Wiwilbild - Page d\'accueil', 'wwb'),
    ));
    register_block_pattern_category('wwb-blog', array(
        'label' => __('Wiwilbild - Blog', 'wwb'),
    ));
}
add_action('init', 'wwb_register_pattern_categories');

/**
 * Charger les styles de l'éditeur
 */
function wwb_editor_styles() {
    add_editor_style('style/app.css');
}
add_action('after_setup_theme', 'wwb_editor_styles');

remove_action("wp_head", "wp_generator");

/* Afficher "À partir de" pour les produits variables */
add_filter( 'woocommerce_variable_sale_price_html', 'wpm_variation_price_format', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'wpm_variation_price_format', 10, 2 );

function wpm_variation_price_format( $price, $product ) {
	//On récupère le prix min et max du produit variable
	$min_price = $product->get_variation_price( 'min', true );
	$max_price = $product->get_variation_price( 'max', true );

	// Si les prix sont différents on affiche "À partir de ..."
	if ($min_price != $max_price){
		$price = sprintf( __( 'À partir de %1$s', 'woocommerce' ), wc_price( $min_price ) );
		return $price;
	// Sinon on affiche juste le prix
	} else {
		$price = sprintf( __( '%1$s', 'woocommerce' ), wc_price( $min_price ) );
		return $price;
	}
}

/* Calculateur surface couverte - Intégré directement dans le template variable.php */


/* Prix au mètre carré pour les catégories concernées */
function ajouter_suffixe_m2_prix($price, $product) {
    $categories_ciblees = array('carrelage');

    if ($product && has_term($categories_ciblees, 'product_cat', $product->get_id())) {
        
        if ($product->is_type('variable')) {
            $price .= ' /m²';
        }
        
        if ($product->is_type('simple') || $product->is_type('variation')) {
            $price .= ' /m²';
        }
    }

    return $price;
}

add_filter('woocommerce_get_price_html', 'ajouter_suffixe_m2_prix', 10, 2);

/**
 * Show cart contents / total Ajax
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );

function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;

	ob_start();

	?>
	<a class="cart-customlocation" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>">
		<?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?> – <?php echo $woocommerce->cart->get_cart_total(); ?>
	</a>
	<?php
	$fragments['a.cart-customlocation'] = ob_get_clean();
	return $fragments;
}


remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 ); 
remove_action( 'woocommerce_after_shop_loop_item' , 'woocommerce_template_loop_add_to_cart', 10);

function filter_wpseo_breadcrumb_separator($this_options_breadcrumbs_sep) {
    return '<img src="'.get_template_directory_uri().'/img/arrow_right.svg">';
};

// add the filter
add_filter('wpseo_breadcrumb_separator', 'filter_wpseo_breadcrumb_separator', 10, 1);

/**
 * Register our sidebars and widgetized areas.
 */
function arphabet_widgets_init() {

	register_sidebar( array(
		'name'          => 'Home right sidebar',
		'id'            => 'home_right_1',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="rounded">',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', 'arphabet_widgets_init' );


	// Filtrer les sélecteurs de variation pour la couleur
	function custom_color_swatches( $html, $args ) {
		if ( 'pa_couleurs' === $args['attribute'] ) { // slug de l'attribut
			global $product;

			$options = $args['options'];
			$attribute = $args['attribute'];
			$product_id = $args['product_id'];

			if ( empty( $options ) && ! empty( $args['selected'] ) ) {
				$options = array( $args['selected'] );
			}

			// Récupérer les termes de l'attribut couleur
			$terms = wc_get_product_terms( $product_id, $attribute, array( 'fields' => 'all' ) );

			if ( ! empty( $terms ) ) {
				$html = '<div class="color-swatches">';

				foreach ( $terms as $term ) {
					// Utiliser ACF pour récupérer la couleur du champ 'product_color_pick'
					$color = get_field('product_color_pick', 'pa_couleurs_' . $term->term_id);

					// Si la couleur est vide ou non définie, utiliser une couleur par défaut
					if ( empty($color) ) {
						$color = '#ffffff'; // Couleur par défaut
					}

					$selected = ( in_array( $term->slug, $options, true ) ) ? 'selected' : '';

					// Ajouter un bouton avec la couleur associée
					$html .= '<button type="button" class="color-swatch ' . esc_attr( $selected ) . '" 
								data-value="' . esc_attr( $term->slug ) . '" 
								style="background-color:' . esc_attr( $color ) . ';"
								title="' . esc_attr( $term->name ) . '"></button>';
				}

				$html .= '</div>';
			}
		}
		return $html;
	}

	add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'custom_color_swatches', 10, 2 );


add_filter('woocommerce_product_description_heading', function($title) {
    return 'Description du produit'; 
});

add_filter('woocommerce_product_additional_information_heading', function($title) {
    return 'Caractéristiques techniques';
});

/**
 * Hotspots interactifs sur les images produits
 * Récupère les hotspots depuis les métadonnées de l'image à la une
 */
function wwb_get_product_image_hotspots( $product_id ) {
    $thumbnail_id = get_post_thumbnail_id( $product_id );
    if ( ! $thumbnail_id ) {
        return array();
    }

    $hotspots_data = get_post_meta( $thumbnail_id, '_wwb_hotspots', true );
    if ( empty( $hotspots_data ) ) {
        return array();
    }

    $hotspots = json_decode( $hotspots_data, true );
    return is_array( $hotspots ) ? $hotspots : array();
}

function wwb_display_product_hotspots( $hotspots ) {
    if ( empty( $hotspots ) ) {
        return;
    }
    ?>
    <div class="product-hotspots-overlay" id="product-hotspots">
        <?php foreach ($hotspots as $index => $hotspot) :
            $pos_x = isset($hotspot['x']) ? floatval($hotspot['x']) : 50;
            $pos_y = isset($hotspot['y']) ? floatval($hotspot['y']) : 50;
            $title = isset($hotspot['title']) ? esc_html($hotspot['title']) : '';
            $description = isset($hotspot['description']) ? esc_html($hotspot['description']) : '';
        ?>
            <div class="hotspot"
                 style="left: <?php echo $pos_x; ?>%; top: <?php echo $pos_y; ?>%;"
                 data-index="<?php echo $index; ?>">
                <span class="hotspot-pulse"></span>
                <span class="hotspot-dot">+</span>
                <div class="hotspot-tooltip">
                    <strong><?php echo $title; ?></strong>
                    <?php if ($description) : ?>
                        <p><?php echo $description; ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}

// Injecter les hotspots via JavaScript après le chargement de la galerie
add_action('wp_footer', 'wwb_hotspots_init_script');
function wwb_hotspots_init_script() {
    if ( ! is_product() ) {
        return;
    }

    global $product;
    if ( ! $product ) {
        return;
    }

    $hotspots = wwb_get_product_image_hotspots( $product->get_id() );
    if ( empty( $hotspots ) ) {
        return;
    }
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Créer le conteneur des hotspots
        var hotspotsHTML = <?php
            ob_start();
            wwb_display_product_hotspots( $hotspots );
            echo json_encode(ob_get_clean());
        ?>;

        // Trouver l'image principale WooCommerce
        var mainImage = document.querySelector('.woocommerce-product-gallery__image');

        if (mainImage) {
            mainImage.style.position = 'relative';
            mainImage.insertAdjacentHTML('beforeend', hotspotsHTML);
        }
    });
    </script>
    <?php
}
