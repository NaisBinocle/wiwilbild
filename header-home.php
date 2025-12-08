<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="">
    <meta charset="utf-8" />

    <title><?php echo wp_title(); ?></title>

    <script src="https://kit.fontawesome.com/58ffc8e3e3.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">


    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style/owl.theme.default.css">

    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style/app.css">   


    <?php wp_head();?>

</head>
    <body <?php body_class(); ?> >
        
        <header class="home">
            <div class="pre_header">
                <p>Frais de livraison offerts dès 250€ d'achat !</p>
            </div>
            
            <div class="wrapper">
                <div class="container"> 
                    <div class="link_to_home">
                        <a class="lien_home col-2" href="<?php echo bloginfo('url'); ?>">
                            <img class="logo" src="<?php echo get_template_directory_uri().'/img/main_logo.webp'; ?>" alt="">
                        </a>
                    </div>
                    <div class="mobile_wrapper">
                        <div class="searchbar">
                            <?php echo do_shortcode('[fibosearch]'); ?>
                        </div>
            
                        <div class="pages"> 
                            <?php
                                wp_nav_menu(array(
                                    'theme_location'  => 'menu-principal', 
                                    'container'       => 'nav', 
                                    'container_class' => 'main-navigation'
                                ));
                            ?>
                        </div>
                        
                        <!-- PANIER -->
                        <a class="cart-customlocation" href="<?php echo wc_get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/cart.png" alt="">
                            <div class="nbr_item_cart">
                                <p>
                                    <?php echo WC()->cart->get_cart_contents_count(); ?>
                                </p>
                            </div>
                        </a>
                    </div>
    
                    <button class="hamburger"></button>

                    <div class="mobile_menu">
                        <?php
                            wp_nav_menu(array(
                                'theme_location'  => 'menu-mobile', 
                                'container'       => 'nav', 
                                'container_class' => 'mobile-navigation'
                            ));
                        ?>
                    </div>
                </div>
    
    
                <!-- CATEGORIES -->
                <div class="hp_cat">
                    <?php
                        $categories = get_terms(array(
                            'taxonomy' => 'product_cat',
                            'parent' => 0,
                            'hide_empty' => false, // Mettre true pour ne pas afficher les catégories vides
                        ));
    
                        //var_dump($categories);
    
                        if (!empty($categories)) :
                        ?>
                            <nav class="menu-categories">
                                <ul>
                                    <?php foreach ($categories as $category) : ?>
                                        <?php 
                                        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                                        $image_url = wp_get_attachment_url($thumbnail_id);
                                        ?>
                                        <li>
                                            <a href="<?php echo esc_url(get_term_link($category)); ?>">
                                                <?php if ($image_url) : ?>
                                                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($category->name); ?>">
                                                <?php endif; ?>
                                                <span><?php echo esc_html($category->name); ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </nav>
                        <?php endif; 
                    ?>
                </div>
                
                <!-- ETIQUETTES -->
                <div class="hp_tags">
                <?php
                    $tags = get_terms(array(
                        'taxonomy' => 'product_tag',
                        'hide_empty' => false,
                    ));
    
                    if (!empty($tags)) :
                    ?>
                        <nav class="menu-tags">
                            <ul>
                                <?php foreach ($tags as $tag) : ?>
                                    <li>
                                        <a href="<?php echo get_term_link($tag); ?>">
                                            <?php echo esc_html($tag->name); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </nav>
                <?php endif; ?>
                </div>
            </div>
        </header>