<?php
/**
 * Section Univers - Pièces de la maison
 */

$surtitle = get_sub_field('surtitle') ?: 'Wiwilbild vous accompagne dans tous vos projets';
$title = get_sub_field('title') ?: 'À chaque pièce son univers';
$cards = get_sub_field('cards');

// Définir les classes CSS selon la position
$card_classes = array('first', 'second', 'third', 'fourth', 'big');
?>

<section class="univers">
    <div class="container univers_title">
        <div>
            <span><?php echo esc_html($surtitle); ?></span>
            <h2><?php echo esc_html($title); ?></h2>
        </div>
    </div>

    <div class="container grid-12 univers_content">
        <?php if ($cards) : ?>
            <?php $index = 0; foreach ($cards as $card) :
                $card_title = $card['card_title'];
                $card_image = $card['card_image'];
                $card_link = $card['card_link'];
                $card_size = $card['card_size'] ?? 'normal';

                // Classe CSS
                $class = $card_size === 'big' ? 'big' : ($card_classes[$index] ?? '');
            ?>
                <div class="card <?php echo esc_attr($class); ?>">
                    <a href="<?php echo esc_url($card_link); ?>">
                        <?php if ($card_image) : ?>
                            <img src="<?php echo esc_url($card_image); ?>" alt="<?php echo esc_attr($card_title); ?>">
                        <?php endif; ?>
                        <p><?php echo esc_html($card_title); ?></p>
                    </a>
                </div>
            <?php $index++; endforeach; ?>
        <?php else : ?>
            <!-- Fallback avec les valeurs par défaut -->
            <div class="card first">
                <a href="<?php echo home_url('/etiquette-produit/chambre/'); ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/exterieur.jpg" alt="">
                    <p>Chambre</p>
                </a>
            </div>
            <div class="card second">
                <a href="<?php echo home_url('/etiquette-produit/salon/'); ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/exterieur.jpg" alt="">
                    <p>Salon</p>
                </a>
            </div>
            <div class="card third">
                <a href="<?php echo home_url('/etiquette-produit/salle-de-bain/'); ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/exterieur.jpg" alt="">
                    <p>Salle de bain</p>
                </a>
            </div>
            <div class="card fourth">
                <a href="<?php echo home_url('/etiquette-produit/cuisine/'); ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/cuisine.jpg" alt="">
                    <p>Cuisine</p>
                </a>
            </div>
            <div class="card big">
                <a href="<?php echo home_url('/etiquette-produit/exterieur/'); ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/exterieur.jpg" alt="">
                    <p>Extérieur</p>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
