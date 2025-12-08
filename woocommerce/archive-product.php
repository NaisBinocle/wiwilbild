<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

?>

<main class="template_cat">
    <div class="container breadcrumbs">
        <?php
            if ( function_exists('yoast_breadcrumb') ) {
                yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
            }
        ?>
    </div>

    <?php 
        /**
         * Hook: woocommerce_before_main_content.
         *
         * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
         * @hooked woocommerce_breadcrumb - 20
         * @hooked WC_Structured_Data::generate_website_data() - 30
         */
        do_action( 'woocommerce_before_main_content' );
    ?>
    
    <div class="container grid-12 category">
            
        <div class="woocommerce-products-header col-3">

            <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
            <?php endif; ?>

            <?php
            /**
             * Hook: woocommerce_archive_description.
             *
             * @hooked woocommerce_taxonomy_archive_description - 10
             * @hooked woocommerce_product_archive_description - 10
             */
            do_action( 'woocommerce_archive_description' );


            
             echo do_shortcode('[fe_widget]');
            
            ?>



        </div>

        <div class="content_products col-9">
            <?php
                if ( woocommerce_product_loop() ) {

                    
                    // Récupère l'objet de la catégorie en cours
                    $term = get_queried_object();

                    if ($term && is_a($term, 'WP_Term')) {
                        $args = array(
                            'taxonomy'   => 'product_cat',  // Taxonomie des catégories WooCommerce
                            'parent'     => $term->term_id, // ID de la catégorie en cours
                            'hide_empty' => false,           // Afficher les catégories vides
                        );

                        $subcategories = get_terms($args);

                        if (!empty($subcategories)) : ?>
                            <div class="subcategory-grid grid-9">
                                <?php foreach ($subcategories as $subcategory) :
                                    $thumbnail_id = get_term_meta($subcategory->term_id, 'thumbnail_id', true);
                                    $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : wc_placeholder_img_src();
                                ?>
                                    <div class="subcategory-item">
                                        <a href="<?php echo get_term_link($subcategory); ?>">
                                            <img src="<?php echo esc_url($image_url); ?>" alt="">
                                            <span><?php echo esc_html($subcategory->name); ?></span>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif;
                    }


                    woocommerce_product_loop_start();

                    if ( wc_get_loop_prop( 'total' ) ) {
                        while ( have_posts() ) {
                            the_post();

                            /**
                             * Hook: woocommerce_shop_loop.
                             */
                            do_action( 'woocommerce_shop_loop' );

                            wc_get_template_part( 'content', 'product' );
                        }
                    }

                    woocommerce_product_loop_end();

                    /**
                     * Hook: woocommerce_after_shop_loop.
                     *
                     * @hooked woocommerce_pagination - 10
                     */
                    do_action( 'woocommerce_after_shop_loop' );

                } else {
                    /**
                     * Hook: woocommerce_no_products_found.
                     *
                     * @hooked wc_no_products_found - 10
                     */
                    do_action( 'woocommerce_no_products_found' );
                }
            ?>
        </div>

        
    </div>

    <section class="cat_bottom">
        <div class="container_fluid right">
            <div class="content">
                <span>Wiwilbild, à l'écoute de vos envies !</span>
                <h3>Quel sera votre prochain projet ?</h3>
                <p class="text">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>
                <a class="pink" href="#">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/jumelles.png" alt="">
                    <p>Découvrir nos produits</p>
                </a>
            </div>

            <div class="bottom_cat_slider">
                <div class="item">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/exterieur.jpg" alt="">
                </div>
                <div class="item">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/exterieur.jpg" alt="">
                </div>
                <div class="item">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/exterieur.jpg" alt="">
                </div>
            </div>
        </div>
    </section>

    <?php get_template_part('reassurance'); ?>  
    
    <?php

    /**
     * Hook: woocommerce_after_main_content.
     *
     * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
     */
    do_action( 'woocommerce_after_main_content' );


    get_footer( 'shop' ); ?>


</main>