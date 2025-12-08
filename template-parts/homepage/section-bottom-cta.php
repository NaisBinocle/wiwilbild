<?php
/**
 * Section Bottom CTA
 */

$surtitle = get_sub_field('surtitle') ?: 'Wiwilbild, à l\'écoute de vos envies !';
$title = get_sub_field('title') ?: 'Quel sera votre prochain projet ?';
$text = get_sub_field('text') ?: 'Vous aimez le travail bien fait… surtout quand c\'est le vôtre ? Chez Wiwilbild, on parle le même langage. Matériaux, conseils et solutions malignes : tout ce qu\'il vous faut pour mener à bien vos projets sans exploser votre budget.';
$btn_text = get_sub_field('btn_text') ?: 'Découvrir nos produits';
$btn_url = get_sub_field('btn_url') ?: '#';
$images = get_sub_field('images');
?>

<section class="hp_bottom">
    <div class="container_fluid right">
        <div class="content">
            <span><?php echo esc_html($surtitle); ?></span>
            <h3><?php echo esc_html($title); ?></h3>
            <p class="text"><?php echo esc_html($text); ?></p>
            <a class="pink" href="<?php echo esc_url($btn_url); ?>">
                <img src="<?php echo get_template_directory_uri(); ?>/img/jumelles.png" alt="">
                <p><?php echo esc_html($btn_text); ?></p>
            </a>
        </div>

        <div class="bottom_hp_slider">
            <?php if ($images) : ?>
                <?php foreach ($images as $image_url) : ?>
                    <div class="item">
                        <img src="<?php echo esc_url($image_url); ?>" alt="">
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="item">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/exterieur.jpg" alt="">
                </div>
                <div class="item">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/exterieur.jpg" alt="">
                </div>
                <div class="item">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/exterieur.jpg" alt="">
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
