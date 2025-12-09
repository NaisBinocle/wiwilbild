<?php
/**
 * Section Shop by Room - Navigation par pièce avec effet hover
 */

$surtitle = get_sub_field('surtitle') ?: 'Parcourir par pièce';
$cta_text = get_sub_field('cta_text') ?: 'Voir par pièce';
$rooms = get_sub_field('rooms');

// Pièces par défaut si non définies dans ACF
$default_rooms = array(
    array(
        'name' => 'Salon',
        'link' => home_url('/etiquette-produit/salon/'),
        'image_left' => get_template_directory_uri() . '/img/rooms/salon-left.jpg',
        'image_right' => get_template_directory_uri() . '/img/rooms/salon-right.jpg',
    ),
    array(
        'name' => 'Salle à manger',
        'link' => home_url('/etiquette-produit/salle-a-manger/'),
        'image_left' => get_template_directory_uri() . '/img/rooms/salle-manger-left.jpg',
        'image_right' => get_template_directory_uri() . '/img/rooms/salle-manger-right.jpg',
    ),
    array(
        'name' => 'Chambre',
        'link' => home_url('/etiquette-produit/chambre/'),
        'image_left' => get_template_directory_uri() . '/img/rooms/chambre-left.jpg',
        'image_right' => get_template_directory_uri() . '/img/rooms/chambre-right.jpg',
    ),
    array(
        'name' => 'Cuisine',
        'link' => home_url('/etiquette-produit/cuisine/'),
        'image_left' => get_template_directory_uri() . '/img/rooms/cuisine-left.jpg',
        'image_right' => get_template_directory_uri() . '/img/rooms/cuisine-right.jpg',
    ),
    array(
        'name' => 'Salle de bain',
        'link' => home_url('/etiquette-produit/salle-de-bain/'),
        'image_left' => get_template_directory_uri() . '/img/rooms/sdb-left.jpg',
        'image_right' => get_template_directory_uri() . '/img/rooms/sdb-right.jpg',
    ),
);

$rooms_data = $rooms ?: $default_rooms;
?>

<section class="shop-by-room">
    <span class="sbr-surtitle"><?php echo esc_html($surtitle); ?></span>

    <div class="sbr-container">
        <!-- Image gauche -->
        <div class="sbr-image sbr-image-left">
            <?php foreach ($rooms_data as $index => $room) :
                $image_left = is_array($room) ? ($room['image_left'] ?? '') : (get_sub_field('image_left') ?: '');
                $is_first = $index === 0;
            ?>
                <img
                    src="<?php echo esc_url($image_left ?: 'https://placehold.co/500x600/EAE9EC/362C49?text=' . urlencode($room['name'] ?? $room)); ?>"
                    alt="<?php echo esc_attr($room['name'] ?? $room); ?>"
                    data-room="<?php echo $index; ?>"
                    class="<?php echo $is_first ? 'active' : ''; ?>"
                >
            <?php endforeach; ?>
        </div>

        <!-- Liste des pièces -->
        <div class="sbr-rooms">
            <ul>
                <?php foreach ($rooms_data as $index => $room) :
                    $name = is_array($room) ? ($room['name'] ?? '') : $room;
                    $link = is_array($room) ? ($room['link'] ?? '#') : '#';
                    $is_first = $index === 0;
                ?>
                    <li>
                        <a
                            href="<?php echo esc_url($link); ?>"
                            data-room="<?php echo $index; ?>"
                            class="<?php echo $is_first ? 'active' : ''; ?>"
                        >
                            <?php echo esc_html($name); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Image droite -->
        <div class="sbr-image sbr-image-right">
            <?php foreach ($rooms_data as $index => $room) :
                $image_right = is_array($room) ? ($room['image_right'] ?? '') : (get_sub_field('image_right') ?: '');
                $is_first = $index === 0;
            ?>
                <img
                    src="<?php echo esc_url($image_right ?: 'https://placehold.co/500x600/EAE9EC/362C49?text=' . urlencode($room['name'] ?? $room)); ?>"
                    alt="<?php echo esc_attr($room['name'] ?? $room); ?>"
                    data-room="<?php echo $index; ?>"
                    class="<?php echo $is_first ? 'active' : ''; ?>"
                >
            <?php endforeach; ?>
        </div>
    </div>

    <a href="<?php echo home_url('/boutique/'); ?>" class="sbr-cta">
        <?php echo esc_html($cta_text); ?> <span class="arrow">→</span>
    </a>
</section>
