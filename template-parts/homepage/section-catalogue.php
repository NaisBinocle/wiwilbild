<?php
/**
 * Section Catalogue - Liens vers catégories
 */

$surtitle = get_sub_field('surtitle') ?: 'Du choix, de la qualité, des prix';
$title = get_sub_field('title') ?: 'Tous nos produits';
$links = get_sub_field('links');
?>

<section class="hp_catalogue">
    <div class="container catalogue_title">
        <div>
            <span><?php echo esc_html($surtitle); ?></span>
            <h2><?php echo esc_html($title); ?></h2>
        </div>
    </div>
    <div class="container grid-12">
        <?php if ($links) : ?>
            <?php foreach ($links as $link) : ?>
                <div class="col-2">
                    <a href="<?php echo esc_url($link['link_url']); ?>"><?php echo esc_html($link['link_text']); ?></a>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="col-2">
                <a href="<?php echo home_url('/categorie-produit/parquet/parquet-stratifie'); ?>">Parquet stratifié</a>
            </div>
        <?php endif; ?>
    </div>
</section>
