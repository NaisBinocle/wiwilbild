<?php
/**
 * WWB V2 — Fenêtre Sur Mesure Configurator
 *
 * Handles: custom add-to-cart, price calculation, cart item meta, order meta.
 * Attached to WooCommerce products with meta '_wwb_configurator' = 'yes'.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class WWB_Configurator {

    // Price matrix (fictive — to be replaced with real pricing)
    const BASE_PRICE_PER_M2 = [
        '1-vantail'    => 320,
        '2-vantaux'    => 280,
        '3-vantaux'    => 260,
        'chassis-fixe' => 200,
    ];

    const SUPPLEMENTS = [
        // Dormant
        'dormant-72mm'         => 0,
        'dormant-renovation'   => 45,
        'dormant-monobloc-iso' => 85,
        // Vitrage
        '4-16-4-standard'  => 0,
        'securite-44-2'    => 55,
        'phonique-6-16-4'  => 40,
        'depoli-4-16-4'    => 30,
        'securite-depoli'  => 70,
        // Ouverture
        'oscillo-battant'  => 0,
        'a-la-francaise'   => 0,
        'fixe'             => -20,
        'soufflet'         => -10,
        // Coloris
        'blanc'                    => 0,
        'anthracite-2f'            => 65,
        'noir-2f'                  => 65,
        'chene-dore-2f'            => 75,
        'chene-irlandais-2f'       => 75,
        'noyer-2f'                 => 75,
        'bicol-blanc-anthracite'   => 50,
        'bicol-blanc-noir'         => 50,
        'bicol-blanc-chene-dore'   => 60,
        'bicol-blanc-chene-irl'    => 60,
        'bicol-blanc-noyer'        => 60,
        // Ferrage
        'ferrage-ob'           => 0,
        'ferrage-2pts-securite' => 15,
        // Grille aération
        'grille-sans'              => 0,
        'grille-autoreglable-15'   => 12,
        'grille-autoreglable-45'   => 18,
        'grille-hygroreglable'     => 25,
        // Volet roulant
        'vr-sans'              => 0,
        'vr-sangle'            => 120,
        'vr-filaire-standard'  => 180,
        'vr-filaire-somfy'     => 250,
        'vr-radio-standard'    => 220,
        'vr-radio-rts-somfy'   => 320,
    ];

    // Dimension constraints (mm)
    const MIN_WIDTH  = 400;
    const MAX_WIDTH  = 2400;
    const MIN_HEIGHT = 400;
    const MAX_HEIGHT = 2200;

    public static function init() {
        // Replace add-to-cart for configurator products
        add_action( 'woocommerce_single_product_summary', [ __CLASS__, 'maybe_render_configurator' ], 25 );
        // Hide default add-to-cart for configurator products
        add_filter( 'woocommerce_product_add_to_cart_text', [ __CLASS__, 'modify_button_text' ], 10, 2 );
        // Handle custom add-to-cart
        add_action( 'wp_ajax_wwb_add_configured_product', [ __CLASS__, 'ajax_add_to_cart' ] );
        add_action( 'wp_ajax_nopriv_wwb_add_configured_product', [ __CLASS__, 'ajax_add_to_cart' ] );
        // Price calculation AJAX
        add_action( 'wp_ajax_wwb_calculate_price', [ __CLASS__, 'ajax_calculate_price' ] );
        add_action( 'wp_ajax_nopriv_wwb_calculate_price', [ __CLASS__, 'ajax_calculate_price' ] );
        // Display config in cart
        add_filter( 'woocommerce_get_item_data', [ __CLASS__, 'display_cart_item_data' ], 10, 2 );
        // Set custom price in cart
        add_action( 'woocommerce_before_calculate_totals', [ __CLASS__, 'set_cart_item_price' ], 10, 1 );
        // Save config to order
        add_action( 'woocommerce_checkout_create_order_line_item', [ __CLASS__, 'save_order_item_meta' ], 10, 4 );
        // Enqueue scripts
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
    }

    public static function is_configurator_product( $product = null ) {
        if ( ! $product ) {
            global $product;
        }
        if ( ! $product ) return false;
        return $product->get_meta( '_wwb_configurator' ) === 'yes';
    }

    public static function maybe_render_configurator() {
        global $product;
        if ( ! self::is_configurator_product( $product ) ) return;

        // Remove default add-to-cart
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

        // Render configurator
        wc_get_template( 'single-product/add-to-cart/configurator.php', [
            'product' => $product,
            'config'  => self::get_config_options(),
        ] );
    }

    public static function modify_button_text( $text, $product ) {
        if ( self::is_configurator_product( $product ) ) {
            return 'Configurer';
        }
        return $text;
    }

    public static function get_config_options() {
        return [
            'types' => [
                '1-vantail'    => [ 'label' => '1 vantail', 'icon' => '1v' ],
                '2-vantaux'    => [ 'label' => '2 vantaux', 'icon' => '2v' ],
                '3-vantaux'    => [ 'label' => '3 vantaux', 'icon' => '3v' ],
                'chassis-fixe' => [ 'label' => 'Châssis fixe', 'icon' => 'fix' ],
            ],
            'ouvertures' => [
                'oscillo-battant' => 'Oscillo-battant',
                'a-la-francaise'  => 'À la française',
                'fixe'            => 'Fixe (non ouvrante)',
                'soufflet'        => 'Soufflet (abattant)',
            ],
            'dormants' => [
                'dormant-72mm'         => 'Dormant 72mm (neuf)',
                'dormant-renovation'   => 'Dormant rénovation',
                'dormant-monobloc-iso' => 'Dormant monobloc ISO',
            ],
            'vitrages' => [
                '4-16-4-standard' => '4/16/4 standard',
                'securite-44-2'   => 'Sécurité 44.2/16/4',
                'phonique-6-16-4' => 'Phonique 6/16/4',
                'depoli-4-16-4'   => 'Dépoli 4/16/4',
                'securite-depoli' => 'Sécurité + Dépoli',
            ],
            'coloris' => [
                'blanc'                  => [ 'label' => 'Blanc', 'hex' => '#FFFFFF' ],
                'anthracite-2f'          => [ 'label' => 'Anthracite', 'hex' => '#3C3C3C' ],
                'noir-2f'               => [ 'label' => 'Noir', 'hex' => '#1A1A1A' ],
                'chene-dore-2f'         => [ 'label' => 'Chêne doré', 'hex' => '#B8860B' ],
                'chene-irlandais-2f'    => [ 'label' => 'Chêne irlandais', 'hex' => '#6B3A2A' ],
                'noyer-2f'             => [ 'label' => 'Noyer', 'hex' => '#4A2C2A' ],
                'bicol-blanc-anthracite' => [ 'label' => 'Bicolore blanc/anthracite', 'hex' => 'linear-gradient(90deg, #FFF 50%, #3C3C3C 50%)' ],
                'bicol-blanc-noir'       => [ 'label' => 'Bicolore blanc/noir', 'hex' => 'linear-gradient(90deg, #FFF 50%, #1A1A1A 50%)' ],
                'bicol-blanc-chene-dore' => [ 'label' => 'Bicolore blanc/chêne doré', 'hex' => 'linear-gradient(90deg, #FFF 50%, #B8860B 50%)' ],
                'bicol-blanc-chene-irl'  => [ 'label' => 'Bicolore blanc/chêne irl.', 'hex' => 'linear-gradient(90deg, #FFF 50%, #6B3A2A 50%)' ],
                'bicol-blanc-noyer'      => [ 'label' => 'Bicolore blanc/noyer', 'hex' => 'linear-gradient(90deg, #FFF 50%, #4A2C2A 50%)' ],
            ],
            'ferrages' => [
                'ferrage-ob'             => 'Oscillo-battant standard',
                'ferrage-2pts-securite'  => '2 points de sécurité',
            ],
            'grilles' => [
                'grille-sans'            => 'Sans grille',
                'grille-autoreglable-15' => 'Autoréglable 15-30 m³/h',
                'grille-autoreglable-45' => 'Autoréglable 45 m³/h',
                'grille-hygroreglable'   => 'Hygroréglable',
            ],
            'volets' => [
                'vr-sans'             => 'Sans volet roulant',
                'vr-sangle'           => 'VR manœuvre sangle',
                'vr-filaire-standard' => 'VR moteur filaire',
                'vr-filaire-somfy'    => 'VR moteur filaire Somfy',
                'vr-radio-standard'   => 'VR moteur radio',
                'vr-radio-rts-somfy'  => 'VR moteur radio RTS Somfy',
            ],
            'dimensions' => [
                'min_width'  => self::MIN_WIDTH,
                'max_width'  => self::MAX_WIDTH,
                'min_height' => self::MIN_HEIGHT,
                'max_height' => self::MAX_HEIGHT,
            ],
            'supplements' => self::SUPPLEMENTS,
            'base_prices' => self::BASE_PRICE_PER_M2,
        ];
    }

    public static function calculate_price( $config ) {
        $type    = sanitize_text_field( $config['type'] ?? '1-vantail' );
        $width   = intval( $config['width'] ?? 800 );
        $height  = intval( $config['height'] ?? 1000 );

        // Clamp dimensions
        $width  = max( self::MIN_WIDTH, min( self::MAX_WIDTH, $width ) );
        $height = max( self::MIN_HEIGHT, min( self::MAX_HEIGHT, $height ) );

        // Base price: per m² × surface
        $surface_m2 = ( $width / 1000 ) * ( $height / 1000 );
        $base_per_m2 = self::BASE_PRICE_PER_M2[ $type ] ?? 300;
        $price = $base_per_m2 * $surface_m2;

        // Minimum price
        $price = max( $price, 89 );

        // Supplements
        $supplement_keys = [ 'dormant', 'vitrage', 'ouverture', 'coloris', 'ferrage', 'grille', 'volet' ];
        foreach ( $supplement_keys as $key ) {
            $val = sanitize_text_field( $config[ $key ] ?? '' );
            if ( $val && isset( self::SUPPLEMENTS[ $val ] ) ) {
                $price += self::SUPPLEMENTS[ $val ];
            }
        }

        return round( $price, 2 );
    }

    public static function ajax_calculate_price() {
        $config = $_POST['config'] ?? [];
        $price = self::calculate_price( $config );
        wp_send_json_success( [ 'price' => $price, 'formatted' => number_format( $price, 2, ',', ' ' ) . ' €' ] );
    }

    public static function ajax_add_to_cart() {
        $product_id = intval( $_POST['product_id'] ?? 0 );
        $config     = $_POST['config'] ?? [];

        if ( ! $product_id ) {
            wp_send_json_error( 'Produit invalide.' );
        }

        $price = self::calculate_price( $config );

        // Generate unique cart item key
        $cart_item_data = [
            'wwb_configurator' => true,
            'wwb_config'       => array_map( 'sanitize_text_field', $config ),
            'wwb_price'        => $price,
        ];

        $cart_item_key = WC()->cart->add_to_cart( $product_id, 1, 0, [], $cart_item_data );

        if ( $cart_item_key ) {
            wp_send_json_success( [
                'cart_item_key' => $cart_item_key,
                'cart_url'      => wc_get_cart_url(),
                'fragments'     => apply_filters( 'woocommerce_add_to_cart_fragments', [] ),
            ] );
        } else {
            wp_send_json_error( 'Erreur ajout au panier.' );
        }
    }

    public static function display_cart_item_data( $item_data, $cart_item ) {
        if ( empty( $cart_item['wwb_configurator'] ) ) return $item_data;

        $config = $cart_item['wwb_config'];
        $labels = self::get_config_options();

        $display_map = [
            'type'      => 'Type',
            'width'     => 'Largeur',
            'height'    => 'Hauteur',
            'ouverture' => 'Ouverture',
            'dormant'   => 'Dormant',
            'vitrage'   => 'Vitrage',
            'coloris'   => 'Coloris',
            'ferrage'   => 'Ferrage',
            'grille'    => 'Grille aération',
            'volet'     => 'Volet roulant',
        ];

        foreach ( $display_map as $key => $label ) {
            if ( ! empty( $config[ $key ] ) ) {
                $value = $config[ $key ];
                // Format dimensions
                if ( $key === 'width' || $key === 'height' ) {
                    $value = $value . ' mm';
                }
                $item_data[] = [
                    'name'  => $label,
                    'value' => $value,
                ];
            }
        }

        return $item_data;
    }

    public static function set_cart_item_price( $cart ) {
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

        foreach ( $cart->get_cart() as $cart_item ) {
            if ( ! empty( $cart_item['wwb_configurator'] ) && isset( $cart_item['wwb_price'] ) ) {
                $cart_item['data']->set_price( $cart_item['wwb_price'] );
            }
        }
    }

    public static function save_order_item_meta( $item, $cart_item_key, $values, $order ) {
        if ( empty( $values['wwb_configurator'] ) ) return;

        $item->add_meta_data( '_wwb_configurator', 'yes' );
        $item->add_meta_data( '_wwb_config', $values['wwb_config'] );
        $item->add_meta_data( '_wwb_price', $values['wwb_price'] );
    }

    public static function enqueue_scripts() {
        if ( ! is_product() ) return;

        global $product;
        if ( ! $product || ! self::is_configurator_product( $product ) ) return;

        $theme_uri = get_template_directory_uri();
        $version   = wp_get_theme()->get( 'Version' );

        wp_enqueue_style( 'wwb-configurator', $theme_uri . '/assets/css/configurator.css', [], $version );
        wp_enqueue_script( 'wwb-configurator', $theme_uri . '/assets/js/configurator.js', [], $version, true );

        wp_localize_script( 'wwb-configurator', 'wwbConfigurator', [
            'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
            'nonce'     => wp_create_nonce( 'wwb_configurator' ),
            'productId' => $product->get_id(),
            'config'    => self::get_config_options(),
        ] );
    }
}

WWB_Configurator::init();
