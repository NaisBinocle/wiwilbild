<?php
/**
 * Section Produits Phares
 */

$surtitle = get_sub_field('surtitle') ?: 'Votre projet. Votre savoir-faire.';
$title = get_sub_field('title') ?: 'Nos produits phares';
$nombre_produits = get_sub_field('nombre_produits') ?: 10;

$args = array(
    'limit' => $nombre_produits,
    'status' => 'publish',
    'featured' => true,
);

$products = wc_get_products($args);
?>

<section class="pdts_phares">
    <div class="container ft_title">
        <span><?php echo esc_html($surtitle); ?></span>
        <h2><?php echo esc_html($title); ?></h2>
    </div>
    <div class="featured_slider">
        <div class="featured owl-carousel owl-theme">
            <?php foreach ($products as $product) : ?>
                <div class="item">
                    <a href="<?php echo esc_url($product->get_permalink()); ?>">
                        <?php echo $product->get_image(); ?>
                        <h3><?php echo esc_html($product->get_name()); ?></h3>
                        <span class="price"><?php echo wc_price($product->get_price()); ?></span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
