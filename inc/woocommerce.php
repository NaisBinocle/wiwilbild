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

// 3b. Coloris libre (non-variation) — persist en cart/order meta
add_filter( 'woocommerce_add_cart_item_data', function( $cart_item_data, $product_id ) {
    if ( ! empty( $_POST['wwb_coloris'] ) ) {
        $cart_item_data['wwb_coloris'] = sanitize_text_field( wp_unslash( $_POST['wwb_coloris'] ) );
    }
    if ( ! empty( $_POST['wwb_coloris_label'] ) ) {
        $cart_item_data['wwb_coloris_label'] = sanitize_text_field( wp_unslash( $_POST['wwb_coloris_label'] ) );
    }
    return $cart_item_data;
}, 10, 2 );

add_filter( 'woocommerce_get_item_data', function( $item_data, $cart_item ) {
    if ( ! empty( $cart_item['wwb_coloris_label'] ) ) {
        $item_data[] = array(
            'key'     => __( 'Coloris', 'wwb-v2' ),
            'value'   => $cart_item['wwb_coloris_label'],
            'display' => '',
        );
    }
    return $item_data;
}, 10, 2 );

add_action( 'woocommerce_checkout_create_order_line_item', function( $item, $cart_item_key, $values, $order ) {
    if ( ! empty( $values['wwb_coloris_label'] ) ) {
        $item->add_meta_data( 'Coloris', $values['wwb_coloris_label'] );
    }
    if ( ! empty( $values['wwb_coloris'] ) ) {
        $item->add_meta_data( '_wwb_coloris_slug', $values['wwb_coloris'], true );
    }
}, 10, 4 );

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
    return '<span class="wwb-breadcrumb__sep" aria-hidden="true">›</span>';
} );

// 6. Color swatches
add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'wwb_v2_color_swatches', 10, 2 );

// 7. Size switcher (sibling products) — affiché avant les variations sur les fiches menuiserie
add_action( 'woocommerce_before_variations_form', 'wwb_v2_render_size_switcher' );

// 8. Placeholder image pour les produits sans photo (Unsplash fenêtres)
define( 'WWB_PLACEHOLDER_FENETRE', 'https://images.unsplash.com/photo-1484154218962-a197022b5858?w=800&q=80&auto=format&fit=crop' );

add_filter( 'woocommerce_placeholder_img_src', function( $src ) {
    return WWB_PLACEHOLDER_FENETRE;
} );
add_filter( 'woocommerce_placeholder_img', function( $image_html, $size, $dimensions ) {
    return '<img src="' . esc_url( WWB_PLACEHOLDER_FENETRE ) . '" alt="Fenêtre placeholder" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" class="woocommerce-placeholder wp-post-image" style="object-fit:cover;width:100%;height:100%;" />';
}, 10, 3 );
// Remplace l'image de galerie produit (fiche) quand aucune featured image
add_filter( 'woocommerce_single_product_image_thumbnail_html', function( $html, $attachment_id ) {
    if ( $attachment_id ) return $html;
    $url = WWB_PLACEHOLDER_FENETRE;
    return '<div data-thumb="' . esc_url( $url ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $url ) . '"><img src="' . esc_url( $url ) . '" alt="Fenêtre placeholder" class="wp-post-image" style="width:100%;height:auto;display:block;" /></a></div>';
}, 10, 2 );
// Fallback dans les loops (catégorie, related, etc.)
add_filter( 'woocommerce_product_get_image', function( $html, $product ) {
    if ( $product && ! $product->get_image_id() ) {
        return '<img src="' . esc_url( WWB_PLACEHOLDER_FENETRE ) . '" alt="' . esc_attr( $product->get_name() ) . '" class="wp-post-image attachment-woocommerce_thumbnail" style="width:100%;height:100%;object-fit:cover;" />';
    }
    return $html;
}, 10, 2 );

function wwb_v2_render_size_switcher() {
    global $product;
    if ( ! $product ) return;

    // Only menuiserie products with pa_taille
    $taille_terms = wc_get_product_terms( $product->get_id(), 'pa_taille', array( 'fields' => 'all' ) );
    if ( empty( $taille_terms ) ) return;

    $current_taille = $taille_terms[0];

    // Find the leaf sub-category (e.g. "fenetres-pvc-2-vantaux")
    $cats = wc_get_product_terms( $product->get_id(), 'product_cat', array( 'fields' => 'all' ) );
    if ( empty( $cats ) ) return;
    $sub_cat = null;
    foreach ( $cats as $c ) {
        if ( $c->parent ) { $sub_cat = $c; break; }
    }
    if ( ! $sub_cat ) $sub_cat = $cats[0];

    // Query siblings (same sub-cat, with pa_taille)
    $siblings = get_posts( array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $sub_cat->term_id,
            ),
        ),
        'orderby'        => 'menu_order title',
        'order'          => 'ASC',
        'fields'         => 'ids',
    ) );

    if ( count( $siblings ) <= 1 ) return;

    $items = array();
    foreach ( $siblings as $sid ) {
        $sib_terms = wc_get_product_terms( $sid, 'pa_taille', array( 'fields' => 'all' ) );
        if ( empty( $sib_terms ) ) continue;
        $items[] = array(
            'id'     => $sid,
            'taille' => $sib_terms[0],
            'url'    => get_permalink( $sid ),
            'is_current' => ( $sid === $product->get_id() ),
        );
    }
    // Sort by width then height (parse "100x100" slug)
    usort( $items, function( $a, $b ) {
        list( $aw, $ah ) = array_map( 'intval', explode( 'x', $a['taille']->slug . 'x0' ) );
        list( $bw, $bh ) = array_map( 'intval', explode( 'x', $b['taille']->slug . 'x0' ) );
        return ( $aw === $bw ) ? ( $ah - $bh ) : ( $aw - $bw );
    } );

    $count = count( $items );
    ?>
    <div class="wwb-size-switcher">
        <div class="wwb-size-switcher__head">
            <span class="wwb-size-switcher__badge">
                <span class="wwb-size-switcher__badge-num">1</span>
                <span class="wwb-size-switcher__label">LES MESURES STANDARD <span class="wwb-size-switcher__sep">|</span> LES + VENDUES</span>
            </span>
        </div>
        <p class="wwb-size-switcher__desc">Sélectionnez l'une de nos <?php echo (int) $count; ?> dimensions standards les plus vendues. Besoin d'une taille spécifique ? Passez par nos fenêtres sur mesure.</p>
        <div class="wwb-size-switcher__grid">
            <?php foreach ( $items as $it ) :
                $name = $it['taille']->name; ?>
                <?php if ( $it['is_current'] ) : ?>
                    <span class="wwb-size-switcher__btn is-current" aria-current="true"><?php echo esc_html( $name ); ?></span>
                <?php else : ?>
                    <a href="<?php echo esc_url( $it['url'] ); ?>" class="wwb-size-switcher__btn"><?php echo esc_html( $name ); ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

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

// Helper: detect if current archive is carrelage
function wwb_v2_is_carrelage_archive() {
    $term = get_queried_object();
    if ( ! $term || ! isset( $term->term_id ) ) return false;
    if ( $term->slug === 'carrelage' ) return true;
    // Check ancestors
    $ancestors = get_ancestors( $term->term_id, 'product_cat', 'taxonomy' );
    foreach ( $ancestors as $ancestor_id ) {
        $ancestor = get_term( $ancestor_id, 'product_cat' );
        if ( $ancestor && $ancestor->slug === 'carrelage' ) return true;
    }
    return false;
}

// Open custom wrapper + breadcrumbs + header + filters (archives only)
add_action( 'woocommerce_before_main_content', function() {
    if ( is_product() ) return;
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

    $is_carrelage = wwb_v2_is_carrelage_archive();
    $filter_pills = $is_carrelage
        ? [ 'Format', 'Style', 'Couleur', 'Prix', 'Usage', 'Pièce' ]
        : [ 'Type', 'Matériau', 'Vantaux', 'Prix', 'Vitrage', 'Couleur' ];
    ?>
    <main class="wwb-shop">
        <div class="wwb-shop__breadcrumbs">
            <div class="wwb-shop__container">
                <?php if ( function_exists( 'yoast_breadcrumb' ) ) yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' ); ?>
            </div>
        </div>
        <div class="wwb-shop__container">
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
            <div class="wwb-shop__filters-left">
                <div class="wwb-shop__filters-label">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M2 4h14M5 9h8M7 14h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    <span>Filtres</span>
                </div>
                <div class="wwb-shop__filters-pills">
                    <?php foreach ( $filter_pills as $label ) : ?>
                        <button type="button" class="wwb-shop__pill"><?php echo esc_html( $label ); ?> <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php
            $current_sort = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );
            $sort_options = [
                'popularity' => 'Popularité',
                'rating'     => 'Mieux notés',
                'date'       => 'Nouveautés',
                'price'      => 'Prix croissant',
                'price-desc' => 'Prix décroissant',
            ];
            ?>
            <form class="wwb-shop__sort" method="get">
                <label class="wwb-shop__sort-label">
                    <span>Trier : </span>
                    <select name="orderby" class="wwb-shop__sort-select" onchange="this.form.submit()">
                        <?php foreach ( $sort_options as $value => $label ) : ?>
                            <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $current_sort, $value ); ?>><?php echo esc_html( $label ); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </label>
                <?php
                // Preserve other query vars (filters, paged)
                foreach ( $_GET as $key => $val ) {
                    if ( $key === 'orderby' ) continue;
                    if ( is_array( $val ) ) {
                        foreach ( $val as $v ) echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $v ) . '">';
                    } else {
                        echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '">';
                    }
                }
                ?>
            </form>
        </div>
    <?php
}, 5 );

// Close custom wrapper + add extra sections after loop (archives only)
add_action( 'woocommerce_after_main_content', function() {
    if ( is_product() ) return;

    $is_carrelage = wwb_v2_is_carrelage_archive();

    if ( $is_carrelage ) :
        // ── CARRELAGE SECTIONS ──
    ?>
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

        <section class="wwb-shop__blog">
            <h2>Nos conseils carrelage</h2>
            <p class="wwb-shop__blog-intro">Inspirations, tutoriels et conseils pour réussir votre projet.</p>
            <div class="wwb-shop__blog-grid">
                <?php
                $q = new WP_Query( [ 'post_type' => 'post', 'posts_per_page' => 3, 'post_status' => 'publish', 'tax_query' => [ [ 'taxonomy' => 'category', 'field' => 'slug', 'terms' => 'carrelage' ] ] ] );
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

    <?php else :
        // ── MENUISERIE SECTIONS ──
    ?>
        </div><!-- /.wwb-shop__container (close to break out for full-width bandeau) -->
        <!-- Bandeau certifications (full-width, pink) -->
        <section class="wwb-shop__certifications">
            <div class="wwb-shop__container wwb-shop__certifications-inner">
            <?php
            $certs = [
                [ 'icon' => 'factory',     'title' => 'Fabriqué en France',    'sub' => 'Usines Hauts-de-France' ],
                [ 'icon' => 'shield',      'title' => 'Garantie 10 ans',       'sub' => "Pièces et main d'œuvre" ],
                [ 'icon' => 'award',       'title' => 'Normes NF · CE · Cekal', 'sub' => 'Classe A+ thermique' ],
                [ 'icon' => 'truck',       'title' => 'Livraison offerte',     'sub' => "Dès 500€ d'achat" ],
                [ 'icon' => 'credit-card', 'title' => 'Paiement 2x · 3x · 4x',  'sub' => 'Sans frais via Alma' ],
            ];
            $cert_svgs = [
                'factory'     => '<path d="M3 21V9l6 4V9l6 4V9l4 3v9H3z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>',
                'shield'      => '<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6l8-3z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
                'award'       => '<circle cx="12" cy="9" r="6" stroke="currentColor" stroke-width="1.8"/><path d="M8.5 14l-2 7 5.5-3 5.5 3-2-7" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>',
                'truck'       => '<path d="M3 7h11v10H3zM14 10h4l3 3v4h-7zM7 20a2 2 0 100-4 2 2 0 000 4zM18 20a2 2 0 100-4 2 2 0 000 4z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>',
                'credit-card' => '<rect x="3" y="6" width="18" height="13" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M3 10h18" stroke="currentColor" stroke-width="1.8"/>',
            ];
            foreach ( $certs as $c ) : ?>
                <div class="wwb-shop__cert-item">
                    <span class="wwb-shop__cert-badge"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><?php echo $cert_svgs[ $c['icon'] ]; ?></svg></span>
                    <div>
                        <strong><?php echo esc_html( $c['title'] ); ?></strong>
                        <span><?php echo esc_html( $c['sub'] ); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </section>
        <div class="wwb-shop__container"><!-- reopen container for rest of sections -->

        <?php
        // ── Catégories dynamiques ──
        // Sur une cat feuille : affiche les siblings (autres formats du même parent)
        // Sur une cat parent  : affiche ses enfants directs
        $current_term = get_queried_object();
        $related_terms = [];
        $related_heading = 'Explorer par catégorie';

        if ( $current_term && isset( $current_term->term_id ) ) {
            $parent_id = (int) $current_term->parent;
            if ( $parent_id > 0 ) {
                // On est sur une sous-cat : siblings
                $related_terms = get_terms( [
                    'taxonomy'   => 'product_cat',
                    'parent'     => $parent_id,
                    'hide_empty' => false,
                    'exclude'    => [ $current_term->term_id ],
                ] );
                $parent_term = get_term( $parent_id, 'product_cat' );
                if ( $parent_term && ! is_wp_error( $parent_term ) ) {
                    $related_heading = 'Autres formats de ' . $parent_term->name;
                }
            } else {
                // On est sur un top-level : enfants
                $related_terms = get_terms( [
                    'taxonomy'   => 'product_cat',
                    'parent'     => $current_term->term_id,
                    'hide_empty' => false,
                ] );
                $related_heading = 'Explorer ' . strtolower( $current_term->name );
            }
        }

        if ( ! empty( $related_terms ) && ! is_wp_error( $related_terms ) ) : ?>
        </div><!-- /.wwb-shop__container -->
        <section class="wwb-shop__styles">
            <div class="wwb-shop__container">
                <h2><?php echo esc_html( $related_heading ); ?></h2>
                <div class="wwb-shop__styles-grid">
                    <?php foreach ( $related_terms as $term ) :
                        $href = get_term_link( $term );
                        $tid  = get_term_meta( $term->term_id, 'thumbnail_id', true );
                        $img  = $tid ? wp_get_attachment_image_url( $tid, 'medium' ) : '';
                    ?>
                        <a href="<?php echo esc_url( $href ); ?>" class="wwb-shop__style-card">
                            <div class="wwb-shop__style-card-img">
                                <?php if ( $img ) : ?>
                                    <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $term->name ); ?>" loading="lazy" />
                                <?php else : ?>
                                    <span class="wwb-shop__style-card-icon">🪟</span>
                                <?php endif; ?>
                            </div>
                            <span><?php echo esc_html( $term->name ); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <div class="wwb-shop__container"><!-- reopen container -->
        <?php endif; ?>

        <!-- Cross-sell : complétez votre fenêtre -->
        <section class="wwb-shop__crosssell">
            <header class="wwb-shop__section-header">
                <h2>Complétez votre fenêtre</h2>
                <a href="<?php echo esc_url( home_url( '/accessoires/' ) ); ?>" class="wwb-shop__see-all">Voir tous les accessoires <span aria-hidden="true">→</span></a>
            </header>
            <div class="wwb-shop__crosssell-grid">
                <?php
                $crosssell = [
                    [ 'title' => 'Appuis de fenêtres', 'meta' => 'dès 2,80 € · 12 modèles',         'slug' => 'appuis-fenetres' ],
                    [ 'title' => 'Volets roulants',    'meta' => 'dès 54 € · monobloc ou rénovation', 'slug' => 'volets-roulants' ],
                    [ 'title' => 'Poignées',           'meta' => 'dès 5 € · 8 finitions',            'slug' => 'poignees' ],
                    [ 'title' => 'Moustiquaires',      'meta' => 'dès 38 € · sur mesure',            'slug' => 'moustiquaires' ],
                ];
                foreach ( $crosssell as $x ) :
                    $term = get_term_by( 'slug', $x['slug'], 'product_cat' );
                    $href = $term ? get_term_link( $term ) : '#';
                    $tid  = $term ? get_term_meta( $term->term_id, 'thumbnail_id', true ) : 0;
                    $img  = $tid ? wp_get_attachment_image_url( $tid, 'medium' ) : '';
                ?>
                    <a href="<?php echo esc_url( $href ); ?>" class="wwb-shop__crosssell-card">
                        <div class="wwb-shop__crosssell-card-img">
                            <?php if ( $img ) : ?><img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $x['title'] ); ?>" loading="lazy" /><?php endif; ?>
                        </div>
                        <div class="wwb-shop__crosssell-card-body">
                            <strong><?php echo esc_html( $x['title'] ); ?></strong>
                            <span><?php echo esc_html( $x['meta'] ); ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="wwb-shop__guide">
            <h2>Comment choisir vos fenêtres ?</h2>
            <ul class="wwb-shop__guide-list">
                <?php foreach ( [
                    'Identifiez vos besoins : isolation thermique, phonique ou sécurité',
                    'Choisissez le matériau adapté : PVC (rapport qualité-prix), aluminium (design) ou bois (authenticité)',
                    "Mesurez précisément l'ouverture dans le mur (côtes tableau) pour un ajustement parfait",
                    'Optez pour le vitrage adapté : double vitrage standard ou triple vitrage pour les expositions Nord',
                ] as $tip ) : ?>
                    <li>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="12" fill="var(--wp--preset--color--secondary)"/><path d="M8 12l3 3 5-5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span><?php echo esc_html( $tip ); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <a href="<?php echo esc_url( home_url( '/guide-fenetre/' ) ); ?>" class="wwb-shop__guide-btn">Lire le guide complet &rarr;</a>
        </section>

        <!-- Comparatif matériaux -->
        <section class="wwb-shop__materials">
            <h2>PVC, Aluminium ou Bois : quel matériau choisir ?</h2>
            <div class="wwb-shop__materials-grid">
                <div class="wwb-shop__material-card wwb-shop__material-card--highlight">
                    <div class="wwb-shop__material-head">
                        <span class="wwb-shop__material-title">PVC</span>
                        <span class="wwb-shop__material-badge">Meilleur rapport Q/P</span>
                    </div>
                    <p>Isolation thermique et acoustique excellente. Entretien quasi nul. Prix attractif. Durée de vie : 30-40 ans.</p>
                    <span class="wwb-shop__material-price">dès 310 € · Idéal neuf et rénovation</span>
                </div>
                <div class="wwb-shop__material-card">
                    <div class="wwb-shop__material-head">
                        <span class="wwb-shop__material-title">Aluminium</span>
                    </div>
                    <p>Design fin et contemporain. Grandes baies possibles. Résistant à la corrosion. Moins isolant que le PVC (sauf rupture pont thermique).</p>
                    <span class="wwb-shop__material-price">dès 580 € · Architectures modernes</span>
                </div>
                <div class="wwb-shop__material-card">
                    <div class="wwb-shop__material-head">
                        <span class="wwb-shop__material-title">Bois</span>
                    </div>
                    <p>Matériau noble et chaleureux. Isolation naturelle. Entretien régulier (lasure ou peinture tous 5-7 ans). Cachet des vieilles bâtisses.</p>
                    <span class="wwb-shop__material-price">dès 650 € · Maisons anciennes</span>
                </div>
            </div>
        </section>

        <!-- Témoignages clients -->
        <section class="wwb-shop__testimonials">
            <header class="wwb-shop__section-header">
                <div>
                    <h2>Ils ont choisi Wiwilbild</h2>
                    <p class="wwb-shop__testimonials-intro">★★★★★ 4,8/5 sur 2 147 avis Trustpilot vérifiés</p>
                </div>
                <a href="<?php echo esc_url( home_url( '/avis/' ) ); ?>" class="wwb-shop__see-all">Voir tous les avis <span aria-hidden="true">→</span></a>
            </header>
            <div class="wwb-shop__testimonials-grid">
                <?php
                $testimonials = [
                    [ 'name' => 'Marie L.',  'city' => 'Lyon',     'color' => '#7B74D0', 'quote' => 'Livraison dans les temps, fenêtres nickel et installation sans accroc. Je recommande à 100 %.', 'product' => 'Fenêtre PVC 2 vantaux 120×135' ],
                    [ 'name' => 'Julien R.', 'city' => 'Bordeaux', 'color' => '#FF99DA', 'quote' => "30 % moins cher que chez Lapeyre pour une qualité équivalente. Le conseiller m'a bien aiguillé.", 'product' => 'Lot 6 fenêtres PVC 1 vantail' ],
                    [ 'name' => 'Sophie D.', 'city' => 'Rennes',   'color' => '#362C49', 'quote' => 'Configurateur très clair, devis reçu en 5 min. Livraison soignée, rien à redire.',             'product' => 'Fenêtre PVC 3 vantaux sur mesure' ],
                ];
                foreach ( $testimonials as $t ) : ?>
                    <article class="wwb-shop__testimonial">
                        <header class="wwb-shop__testimonial-head">
                            <span class="wwb-shop__testimonial-avatar" style="background:<?php echo esc_attr( $t['color'] ); ?>" aria-hidden="true"></span>
                            <div>
                                <strong><?php echo esc_html( $t['name'] ); ?></strong>
                                <span><?php echo esc_html( $t['city'] ); ?> · Achat vérifié</span>
                            </div>
                        </header>
                        <span class="wwb-shop__testimonial-stars" aria-label="5 étoiles sur 5">★★★★★</span>
                        <blockquote>« <?php echo esc_html( $t['quote'] ); ?> »</blockquote>
                        <footer class="wwb-shop__testimonial-product"><?php echo esc_html( $t['product'] ); ?></footer>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        </div><!-- /.wwb-shop__container -->
        <!-- FAQ -->
        <section class="wwb-shop__faq">
            <div class="wwb-shop__container">
            <h2>Questions fréquentes</h2>
            <div class="wwb-shop__faq-list">
                <?php
                $faqs = [
                    [ 'q' => 'Combien coûte une fenêtre PVC 2 vantaux ?',      'a' => 'Le prix varie de 310 € à 685 € selon la dimension standard choisie, hors pose. Compter 180 € à 350 € de plus pour la pose par un artisan certifié. Des aides (MaPrimeRénov\', TVA 5,5 %) peuvent réduire la facture de 30 à 40 %.' ],
                    [ 'q' => "Quel vitrage choisir selon l'exposition ?",       'a' => "Pour une exposition nord ou une façade donnant sur la rue, privilégiez un vitrage phonique 6/16/4 ou sécurité 44.2/16/4. En exposition sud, le double vitrage thermique standard 4/16/4 suffit. Pour une chambre côté jardin bruyant, optez pour le vitrage phonique." ],
                    [ 'q' => 'Comment prendre ses mesures correctement ?',      'a' => "Mesurez la largeur et la hauteur de votre tableau côté intérieur, à trois endroits différents. Retenez la plus petite mesure. Déduisez 10 mm en largeur et 20 mm en hauteur pour les jeux de pose. Notre guide PDF détaille la méthode en images." ],
                    [ 'q' => 'Quel délai de fabrication et livraison ?',        'a' => 'Pour une dimension standard en stock, comptez 3 à 4 semaines. Pour du sur-mesure, 4 à 6 semaines. Livraison transporteur avec prise de rendez-vous, déchargement et mise en sécurité sur palette.' ],
                    [ 'q' => 'Est-ce que je peux poser la fenêtre moi-même ?', 'a' => "Oui, la pose est accessible à un bon bricoleur avec les bons outils (niveau, mousse expansive, visserie). Nous fournissons une notice détaillée et une vidéo pas-à-pas. Pour conserver la garantie décennale ou bénéficier des aides, la pose par un artisan RGE est requise." ],
                    [ 'q' => 'Quelle garantie sur les fenêtres PVC ?',          'a' => "Nous offrons une garantie 10 ans sur les pièces et la main d'œuvre de fabrication. La quincaillerie est garantie 5 ans. En cas de défaut, nous remplaçons la pièce ou la fenêtre sans frais." ],
                    [ 'q' => "Puis-je bénéficier de MaPrimeRénov' ?",            'a' => 'Oui, à condition de faire poser par un artisan RGE et de remplacer une fenêtre existante (pas pour une construction neuve). Le montant varie de 40 à 100 € par fenêtre selon vos revenus. Nous proposons un service de mise en relation avec nos poseurs partenaires certifiés RGE.' ],
                ];
                foreach ( $faqs as $i => $f ) : ?>
                    <details class="wwb-shop__faq-item"<?php echo $i === 0 ? ' open' : ''; ?>>
                        <summary>
                            <span><?php echo esc_html( $f['q'] ); ?></span>
                            <svg class="wwb-shop__faq-icon" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9h12M9 3v12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                        </summary>
                        <p><?php echo esc_html( $f['a'] ); ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
            </div>
        </section>
        <div class="wwb-shop__container"><!-- reopen container -->

        <!-- Process : 3 étapes avec badges + icônes + liaison -->
        <section class="wwb-shop__process">
            <div class="wwb-shop__process-head">
                <span class="wwb-shop__process-eyebrow">COMMENT ÇA MARCHE</span>
                <h2>Commander sans se tromper</h2>
                <p class="wwb-shop__process-intro">3 étapes simples pour recevoir vos fenêtres.</p>
            </div>
            <ol class="wwb-shop__process-grid">
                <li class="wwb-shop__process-step">
                    <span class="wwb-shop__process-badge">ÉTAPE 01</span>
                    <span class="wwb-shop__process-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                    </span>
                    <strong>Configurez en ligne</strong>
                    <p>Choisissez dimensions, vitrage et couleur. Prix calculé en temps réel.</p>
                </li>
                <li class="wwb-shop__process-step">
                    <span class="wwb-shop__process-badge">ÉTAPE 02</span>
                    <span class="wwb-shop__process-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="m9 11 3 3L22 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                    <strong>Validez votre devis</strong>
                    <p>Prix ferme. Paiement sécurisé ou financement possible jusqu'à 10x.</p>
                </li>
                <li class="wwb-shop__process-step">
                    <span class="wwb-shop__process-badge">ÉTAPE 03</span>
                    <span class="wwb-shop__process-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="1" y="3" width="15" height="13" stroke="currentColor" stroke-width="1.8"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" fill="none"/><circle cx="5.5" cy="18.5" r="2.5" stroke="currentColor" stroke-width="1.8"/><circle cx="18.5" cy="18.5" r="2.5" stroke="currentColor" stroke-width="1.8"/></svg>
                    </span>
                    <strong>Recevez chez vous</strong>
                    <p>Livraison 4-6 semaines sur rdv. Pose par artisan RGE en option.</p>
                </li>
            </ol>
        </section>

        <!-- Bloc aide : 3 canaux centralisés (full width, gradient dark) -->
        <section class="wwb-shop__help">
            <div class="wwb-shop__help-inner">
                <div class="wwb-shop__help-head">
                    <span class="wwb-shop__help-eyebrow">BESOIN D'AIDE ?</span>
                    <h2>Un conseiller vous répond en moins de 20 min</h2>
                    <p class="wwb-shop__help-intro">Choisissez le canal qui vous va — téléphone, visio, ou email.</p>
                </div>
                <div class="wwb-shop__help-channels">
                    <a href="tel:+33148850000" class="wwb-shop__help-channel">
                        <span class="wwb-shop__help-channel-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.13.96.37 1.9.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.91.33 1.85.57 2.81.7a2 2 0 011.72 2.03z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                        </span>
                        <span class="wwb-shop__help-channel-label">Téléphone</span>
                        <span class="wwb-shop__help-channel-value">01 48 85 00 00</span>
                        <span class="wwb-shop__help-channel-sub">Lun-ven · 9h-18h</span>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/prendre-rdv/' ) ); ?>" class="wwb-shop__help-channel">
                        <span class="wwb-shop__help-channel-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="2" y="5" width="14" height="14" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="m16 10 6-3v10l-6-3z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                        </span>
                        <span class="wwb-shop__help-channel-label">Visio</span>
                        <span class="wwb-shop__help-channel-value">Prendre RDV</span>
                        <span class="wwb-shop__help-channel-sub">Créneaux sous 24h</span>
                    </a>
                    <a href="mailto:conseil@wiwilbild.fr" class="wwb-shop__help-channel">
                        <span class="wwb-shop__help-channel-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="m3 7 9 6 9-6" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                        </span>
                        <span class="wwb-shop__help-channel-label">Email</span>
                        <span class="wwb-shop__help-channel-value">conseil@wiwilbild.fr</span>
                        <span class="wwb-shop__help-channel-sub">Réponse sous 2h</span>
                    </a>
                </div>
            </div>
        </section>

        </div><!-- /.wwb-shop__container -->
        <section class="wwb-shop__blog">
            <div class="wwb-shop__container">
            <h2>Nos conseils menuiserie</h2>
            <p class="wwb-shop__blog-intro">Guides, astuces et inspirations pour vos projets de rénovation.</p>
            <div class="wwb-shop__blog-grid">
                <?php
                $q = new WP_Query( [ 'post_type' => 'post', 'posts_per_page' => 3, 'post_status' => 'publish', 'tax_query' => [ [ 'taxonomy' => 'category', 'field' => 'slug', 'terms' => [ 'menuiserie', 'fenetres', 'fenetre' ], 'operator' => 'IN' ] ] ] );
                if ( ! $q->have_posts() ) {
                    // Fallback: latest posts if no menuiserie category yet
                    $q = new WP_Query( [ 'post_type' => 'post', 'posts_per_page' => 3, 'post_status' => 'publish' ] );
                }
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
            </div>
        </section>
        <div class="wwb-shop__container"><!-- reopen container -->

        <!-- Newsletter compacte (juste avant footer) -->
        <section class="wwb-shop__newsletter">
            <div class="wwb-shop__newsletter-text">
                <strong>Guide PDF offert + 30 € de réduction dès 500 €</strong>
                <span>Le guide complet pour bien choisir et installer sa fenêtre.</span>
            </div>
            <form class="wwb-shop__newsletter-form" method="post" action="#newsletter">
                <input type="email" name="email" placeholder="votre@email.fr" aria-label="Votre email" required>
                <button type="submit">Recevoir le guide</button>
            </form>
        </section>

    <?php endif; ?>
        </div><!-- /.wwb-shop__container -->
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
