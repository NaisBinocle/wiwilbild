<?php
/**
 * Section Hero - Slider + Infos MEA
 */

$titre_1 = get_sub_field('titre_1') ?: 'Des prix légers, une qualité béton';
$titre_2 = get_sub_field('titre_2') ?: 'Faites-le vous-même, mais pas tout seul.';
$slider_images = get_sub_field('slider_images');
$infos_mea = get_sub_field('infos_mea');
?>

<section class="hero">
    <div class="container hero_title">
        <div>
            <h2><?php echo esc_html($titre_1); ?></h2>
            <h2><?php echo esc_html($titre_2); ?></h2>
        </div>
    </div>

    <div class="container grid-12">
        <div class="hero_slider owl-carousel owl-theme col-8">
            <?php if ($slider_images) : ?>
                <?php foreach ($slider_images as $image_url) : ?>
                    <div class="slide">
                        <img src="<?php echo esc_url($image_url); ?>" alt="">
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if ($infos_mea) : ?>
            <div class="col-4 info_mea">
                <img src="<?php echo esc_url($infos_mea); ?>" alt="">
            </div>
        <?php endif; ?>
    </div>
</section>
