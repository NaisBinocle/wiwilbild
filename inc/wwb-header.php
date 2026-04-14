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
                <li class="wwb-header__link-item <?php echo $is_carr ? 'is-active' : ''; ?>">
                    <a class="wwb-header__link" href="<?php echo esc_url( $home . 'categorie-produit/carrelage/' ); ?>">Carrelages</a>
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
                <a class="wwb-header__icon-btn" href="<?php echo esc_url( $account_url ); ?>" aria-label="Mon compte">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </a>
                <a class="wwb-header__btn wwb-header__btn--cart" href="<?php echo esc_url( $cart_url ); ?>">Panier (<span class="wwb-header__cart-count"><?php echo (int) $cart_count; ?></span>)</a>
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
                        <span class="wwb-mega__card-sub">12 dimensions · dès 180 €</span>
                    </span>
                </a>
                <a class="wwb-mega__card" href="<?php echo esc_url( $home . 'categorie-produit/fenetre-pvc-2-vantaux/' ); ?>">
                    <span class="wwb-mega__card-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="8" height="18" rx="1.5"/><rect x="13" y="3" width="8" height="18" rx="1.5"/></svg>
                    </span>
                    <span class="wwb-mega__card-text">
                        <span class="wwb-mega__card-title">2 vantaux</span>
                        <span class="wwb-mega__card-sub">24 dimensions · dès 310 €</span>
                    </span>
                </a>
                <a class="wwb-mega__card" href="<?php echo esc_url( $home . 'categorie-produit/fenetres-pvc-3-vantaux/' ); ?>">
                    <span class="wwb-mega__card-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="6" height="18" rx="1.2"/><rect x="9" y="3" width="6" height="18" rx="1.2"/><rect x="16" y="3" width="6" height="18" rx="1.2"/></svg>
                    </span>
                    <span class="wwb-mega__card-text">
                        <span class="wwb-mega__card-title">3 vantaux</span>
                        <span class="wwb-mega__card-sub">18 dimensions · dès 520 €</span>
                    </span>
                </a>
                <a class="wwb-mega__card" href="<?php echo esc_url( $home . 'categorie-produit/chassis-fixe/' ); ?>">
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
