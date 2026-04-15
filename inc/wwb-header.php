<?php
/**
 * WWB Header — shortcode [wwb_header]
 * Rendu dynamique du header (top bar + nav + méga menu Menuiseries)
 * D'après les maquettes Pencil (fichier wiwilbild.pen).
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function wwb_header_is_active( $slug ) {
    if ( $slug === 'menuiseries' ) {
        return is_product_category( 'menuiseries' ) || is_product_category( 'fenetres-pvc' ) || is_product_category( 'portes-fenetres-pvc' );
    }
    if ( $slug === 'carrelages' ) return is_product_category( 'carrelage' );
    if ( $slug === 'couvertures' ) return is_product_category( 'couvertures' );
    if ( $slug === 'realisations' ) return is_page( 'nos-realisations' );
    if ( $slug === 'blog' ) return is_home() || is_singular( 'post' ) || is_category() || is_tag();
    return false;
}

function wwb_header_mini_cart() {
    if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
        return '<div class="wwb-mini-cart__empty">Panier indisponible.</div>';
    }
    $cart = WC()->cart;
    $items = $cart->get_cart();
    ob_start(); ?>
    <div class="wwb-mini-cart">
        <?php if ( empty( $items ) ) : ?>
            <div class="wwb-mini-cart__empty">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"/></svg>
                <p>Votre panier est vide</p>
                <a href="<?php echo esc_url( home_url( '/boutique/' ) ); ?>" class="wwb-mini-cart__shop-btn">Découvrir nos produits</a>
            </div>
        <?php else : ?>
            <div class="wwb-mini-cart__list">
                <?php foreach ( $items as $key => $item ) :
                    $product = $item['data'];
                    if ( ! $product ) continue;
                    $title   = $product->get_name();
                    $qty     = (int) $item['quantity'];
                    $line    = $item['line_total'];
                    $image   = $product->get_image( array( 56, 56 ) );
                    $link    = $product->get_permalink( $item );
                    $coloris = ! empty( $item['wwb_coloris_label'] ) ? $item['wwb_coloris_label'] : '';
                ?>
                    <div class="wwb-mini-cart__item">
                        <a class="wwb-mini-cart__item-img" href="<?php echo esc_url( $link ); ?>"><?php echo $image; ?></a>
                        <div class="wwb-mini-cart__item-body">
                            <a class="wwb-mini-cart__item-title" href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $title ); ?></a>
                            <?php if ( $coloris ) : ?>
                                <span class="wwb-mini-cart__item-meta">Coloris : <?php echo esc_html( $coloris ); ?></span>
                            <?php endif; ?>
                            <span class="wwb-mini-cart__item-price"><?php echo $qty; ?> × <?php echo wc_price( $line / max( $qty, 1 ) ); ?></span>
                        </div>
                        <a class="wwb-mini-cart__item-remove" href="<?php echo esc_url( wc_get_cart_remove_url( $key ) ); ?>" aria-label="Retirer">×</a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="wwb-mini-cart__footer">
                <div class="wwb-mini-cart__total">
                    <span>Sous-total</span>
                    <strong><?php echo wp_kses_post( $cart->get_subtotal() ? wc_price( $cart->get_subtotal() ) : '' ); ?></strong>
                </div>
                <div class="wwb-mini-cart__ctas">
                    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="wwb-mini-cart__btn wwb-mini-cart__btn--secondary">Voir le panier</a>
                    <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="wwb-mini-cart__btn wwb-mini-cart__btn--primary">Commander</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

// Expose mini-cart as a WC fragment so it updates via AJAX
add_filter( 'woocommerce_add_to_cart_fragments', function( $fragments ) {
    $fragments['div.wwb-header__mini-cart'] = '<div class="wwb-header__mini-cart">' . wwb_header_mini_cart() . '</div>';
    return $fragments;
} );

function wwb_header_cart_count() {
    if ( function_exists( 'WC' ) && WC()->cart ) {
        return (int) WC()->cart->get_cart_contents_count();
    }
    return 0;
}

function wwb_header_render() {
    $home         = home_url( '/' );
    $theme_uri    = get_template_directory_uri();
    $cart_count   = wwb_header_cart_count();
    $cart_url     = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : $home . 'panier/';
    $account_url  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : $home . 'mon-compte/';
    $pro_url      = $home . 'inscription-pro/';

    $is_menu   = wwb_header_is_active( 'menuiseries' );
    $is_carr   = wwb_header_is_active( 'carrelages' );
    $is_couv   = wwb_header_is_active( 'couvertures' );
    $is_real   = wwb_header_is_active( 'realisations' );
    $is_blog   = wwb_header_is_active( 'blog' );

    ob_start(); ?>
<div class="wwb-header-wrap"><header class="wwb-header">

    <!-- Top utility bar (dark) -->
    <div class="wwb-header__top">
        <div class="wwb-header__top-inner">
            <span class="wwb-header__top-left">Livraison offerte dès 500€ · Franco entière</span>
            <a class="wwb-header__top-right" href="tel:+33148850000">01 48 85 00 00</a>
        </div>
    </div>

    <!-- Main nav -->
    <nav class="wwb-header__nav" aria-label="Navigation principale">
        <div class="wwb-header__nav-inner">

            <a class="wwb-header__logo" href="<?php echo esc_url( $home ); ?>" aria-label="Wiwilbild — Accueil">
                <img src="<?php echo esc_url( $theme_uri . '/assets/img/logo.png' ); ?>" alt="Wiwilbild" />
            </a>

            <ul class="wwb-header__links">
                <li class="wwb-header__link-item wwb-header__link-item--has-mega <?php echo $is_menu ? 'is-active' : ''; ?>">
                    <a class="wwb-header__link" href="<?php echo esc_url( $home . 'categorie-produit/menuiseries/' ); ?>">
                        Menuiseries
                        <svg class="wwb-header__chevron" width="10" height="10" viewBox="0 0 10 10" aria-hidden="true"><path d="M1 3l4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <div class="wwb-header__mega" role="menu">
                        <?php echo wwb_header_mega_menu(); ?>
                    </div>
                </li>
                <li class="wwb-header__link-item wwb-header__link-item--has-mega <?php echo $is_carr ? 'is-active' : ''; ?>">
                    <a class="wwb-header__link" href="<?php echo esc_url( $home . 'categorie-produit/carrelage/' ); ?>">
                        Carrelages
                        <svg class="wwb-header__chevron" width="10" height="10" viewBox="0 0 10 10" aria-hidden="true"><path d="M1 3l4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <div class="wwb-header__mega wwb-header__mega--carrelage" role="menu">
                        <?php echo wwb_header_mega_carrelage(); ?>
                    </div>
                </li>
                <li class="wwb-header__link-item <?php echo $is_couv ? 'is-active' : ''; ?>">
                    <a class="wwb-header__link" href="<?php echo esc_url( $home . 'categorie-produit/couvertures/' ); ?>">Couvertures</a>
                </li>
                <li class="wwb-header__link-item <?php echo $is_real ? 'is-active' : ''; ?>">
                    <a class="wwb-header__link" href="<?php echo esc_url( $home . 'nos-realisations/' ); ?>">Nos réalisations</a>
                </li>
                <li class="wwb-header__link-item <?php echo $is_blog ? 'is-active' : ''; ?>">
                    <a class="wwb-header__link" href="<?php echo esc_url( $home . 'blog/' ); ?>">Blog</a>
                </li>
            </ul>

            <div class="wwb-header__actions">
                <a class="wwb-header__btn wwb-header__btn--pro" href="<?php echo esc_url( $pro_url ); ?>">Espace Pro</a>
                <?php if ( is_user_logged_in() ) :
                    $u = wp_get_current_user();
                    $initials = strtoupper( substr( $u->first_name, 0, 1 ) . substr( $u->last_name, 0, 1 ) );
                    if ( ! trim( $initials ) ) $initials = strtoupper( substr( $u->display_name, 0, 1 ) );
                ?>
                    <a class="wwb-header__avatar" href="<?php echo esc_url( $account_url ); ?>" aria-label="Mon compte">
                        <?php echo esc_html( $initials ); ?>
                    </a>
                <?php else : ?>
                    <a class="wwb-header__icon-btn" href="<?php echo esc_url( $account_url ); ?>" aria-label="Mon compte">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </a>
                <?php endif; ?>
                <div class="wwb-header__cart-wrap">
                    <a class="wwb-header__btn wwb-header__btn--cart" href="<?php echo esc_url( $cart_url ); ?>">Panier (<span class="wwb-header__cart-count"><?php echo (int) $cart_count; ?></span>)</a>
                    <div class="wwb-header__mini-cart"><?php echo wwb_header_mini_cart(); ?></div>
                </div>
            </div>

            <button class="wwb-header__burger" type="button" aria-label="Menu" data-wwb-burger>
                <span></span><span></span><span></span>
            </button>

        </div>
    </nav>

</header></div>
<?php
    $html = ob_get_clean();
    // Strip whitespace between tags to prevent wpautop from injecting <br>/<p>
    $html = preg_replace( '/>\s+</', '><', $html );
    return $html;
}

function wwb_header_mega_menu() {
    $home = home_url( '/' );
    ob_start(); ?>
    <div class="wwb-mega">
        <div class="wwb-mega__left">
            <div class="wwb-mega__left-top">
                <span class="wwb-mega__tag">CONFIGURATEUR</span>
                <h3 class="wwb-mega__title">Fenêtres sur mesure</h3>
                <p class="wwb-mega__desc">Des dimensions exactes, fabriquées en France en 3 à 5 semaines. Devis instantané.</p>
            </div>
            <a class="wwb-mega__cta" href="<?php echo esc_url( $home . 'produit/fenetre-pvc-sur-mesure/' ); ?>">
                Configurer ma fenêtre
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>
        <div class="wwb-mega__right">
            <div class="wwb-mega__right-head">
                <h3 class="wwb-mega__right-title">Fenêtres PVC</h3>
                <a class="wwb-mega__right-link" href="<?php echo esc_url( $home . 'categorie-produit/fenetres-pvc/' ); ?>">
                    Voir toutes les fenêtres
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </div>
            <div class="wwb-mega__grid">
                <a class="wwb-mega__card" href="<?php echo esc_url( $home . 'categorie-produit/fenetres-pvc-1-vantail/' ); ?>">
                    <span class="wwb-mega__card-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="8" y="2" width="8" height="20" rx="2"/></svg>
                    </span>
                    <span class="wwb-mega__card-text">
                        <span class="wwb-mega__card-title">1 vantail</span>
                        <span class="wwb-mega__card-sub">10 dimensions · dès 180 €</span>
                    </span>
                </a>
                <a class="wwb-mega__card" href="<?php echo esc_url( $home . 'categorie-produit/fenetres-pvc-2-vantaux/' ); ?>">
                    <span class="wwb-mega__card-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="8" height="18" rx="1.5"/><rect x="13" y="3" width="8" height="18" rx="1.5"/></svg>
                    </span>
                    <span class="wwb-mega__card-text">
                        <span class="wwb-mega__card-title">2 vantaux</span>
                        <span class="wwb-mega__card-sub">10 dimensions · dès 310 €</span>
                    </span>
                </a>
                <a class="wwb-mega__card" href="<?php echo esc_url( $home . 'categorie-produit/fenetres-pvc-3-vantaux/' ); ?>">
                    <span class="wwb-mega__card-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="6" height="18" rx="1.2"/><rect x="9" y="3" width="6" height="18" rx="1.2"/><rect x="16" y="3" width="6" height="18" rx="1.2"/></svg>
                    </span>
                    <span class="wwb-mega__card-text">
                        <span class="wwb-mega__card-title">3 vantaux</span>
                        <span class="wwb-mega__card-sub">10 dimensions · dès 520 €</span>
                    </span>
                </a>
                <a class="wwb-mega__card" href="<?php echo esc_url( $home . 'categorie-produit/chassis-fixe-pvc/' ); ?>">
                    <span class="wwb-mega__card-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="1.5"/></svg>
                    </span>
                    <span class="wwb-mega__card-text">
                        <span class="wwb-mega__card-title">Châssis fixe</span>
                        <span class="wwb-mega__card-sub">10 dimensions · dès 140 €</span>
                    </span>
                </a>
            </div>
        </div>
    </div>
    <?php
    $html = ob_get_clean();
    return preg_replace( '/>\s+</', '><', $html );
}

function wwb_header_mega_carrelage() {
    $home = home_url( '/' );
    ob_start(); ?>
    <div class="wwb-mega wwb-mega--carrelage">
        <div class="wwb-mega__right wwb-mega__right--full">
            <div class="wwb-mega__right-head">
                <h3 class="wwb-mega__right-title">Carrelages intérieur, extérieur &amp; faïence</h3>
                <a class="wwb-mega__right-link" href="<?php echo esc_url( $home . 'categorie-produit/carrelage/' ); ?>">
                    Voir tous les carrelages
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </div>
            <div class="wwb-mega__grid wwb-mega__grid--cols-3">
                <a class="wwb-mega__card wwb-mega__card--active" href="<?php echo esc_url( $home . 'categorie-produit/carrelage/' ); ?>">
                    <span class="wwb-mega__card-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    </span>
                    <span class="wwb-mega__card-text">
                        <span class="wwb-mega__card-title">Carrelage intérieur</span>
                        <span class="wwb-mega__card-sub">Salle de bain, cuisine, salon · dès 19 €/m²</span>
                    </span>
                </a>
                <a class="wwb-mega__card" href="<?php echo esc_url( $home . 'categorie-produit/carrelage-exterieur/' ); ?>">
                    <span class="wwb-mega__card-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
                    </span>
                    <span class="wwb-mega__card-text">
                        <span class="wwb-mega__card-title">Extérieur &amp; terrasse</span>
                        <span class="wwb-mega__card-sub">Dalles, margelles, plots · dès 24 €/m²</span>
                    </span>
                </a>
                <a class="wwb-mega__card" href="<?php echo esc_url( $home . 'categorie-produit/faience/' ); ?>">
                    <span class="wwb-mega__card-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="6" height="6" rx="1"/><rect x="11" y="3" width="6" height="6" rx="1"/><rect x="3" y="11" width="6" height="6" rx="1"/><rect x="11" y="11" width="6" height="6" rx="1"/><rect x="7" y="15" width="6" height="6" rx="1"/></svg>
                    </span>
                    <span class="wwb-mega__card-text">
                        <span class="wwb-mega__card-title">Faïence &amp; mosaïque</span>
                        <span class="wwb-mega__card-sub">Murs, crédences, douches · dès 29 €/m²</span>
                    </span>
                </a>
            </div>
        </div>
    </div>
    <?php
    $html = ob_get_clean();
    return preg_replace( '/>\s+</', '><', $html );
}

add_shortcode( 'wwb_header', 'wwb_header_render' );

// Dynamic block wwb/header (bypass wpautop applied to shortcodes)
add_action( 'init', function() {
    register_block_type( 'wwb/header', array(
        'render_callback' => 'wwb_header_render',
    ) );
} );

// Refresh cart count fragment (WC AJAX)
add_filter( 'woocommerce_add_to_cart_fragments', function( $fragments ) {
    $fragments['span.wwb-header__cart-count'] = '<span class="wwb-header__cart-count">' . wwb_header_cart_count() . '</span>';
    return $fragments;
} );
