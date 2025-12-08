<?php
/**
 * Section Nouvelle Gamme / Coming soon
 */

$surtitle = get_sub_field('surtitle') ?: 'Toujours plus de produits, toujours au meilleur prix';
$title = get_sub_field('title') ?: 'La dernière gamme à rentrer au catalogue';
$image = get_sub_field('image');
$texte = get_sub_field('texte') ?: 'coming soon';

// Image par défaut si aucune n'est définie
if (!$image) {
    $image = get_template_directory_uri() . '/img/usine.jpg';
}
?>

<section class="next_usine">
    <div class="container usine_title">
        <div>
            <span><?php echo esc_html($surtitle); ?></span>
            <h2><?php echo esc_html($title); ?></h2>
        </div>
    </div>
    <div class="container grid-12">
        <div class="col-12 content">
            <img src="<?php echo esc_url($image); ?>" alt="">
            <p><?php echo esc_html($texte); ?></p>
        </div>
    </div>
</section>
