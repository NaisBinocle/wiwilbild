<?php
/**
 * ACF Flexible Content - Sections de la page d'accueil
 * Permet d'éditer et réorganiser les sections depuis l'admin WordPress
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enregistrer les groupes de champs ACF pour la page d'accueil
 */
add_action( 'acf/init', 'wwb_register_homepage_sections' );
function wwb_register_homepage_sections() {

    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group( array(
        'key' => 'group_homepage_sections',
        'title' => 'Sections de la page d\'accueil',
        'fields' => array(
            // Image de fond hero
            array(
                'key' => 'field_hp_bg_hero',
                'label' => 'Image de fond Hero',
                'name' => 'bg_hero',
                'type' => 'image',
                'return_format' => 'url',
                'preview_size' => 'medium',
            ),
            // Flexible Content
            array(
                'key' => 'field_hp_sections',
                'label' => 'Sections',
                'name' => 'hp_sections',
                'type' => 'flexible_content',
                'button_label' => 'Ajouter une section',
                'layouts' => array(
                    // HERO SLIDER
                    'layout_hero' => array(
                        'key' => 'layout_hero',
                        'name' => 'hero',
                        'label' => 'Hero (Slider + Infos)',
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_hero_titre_1',
                                'label' => 'Titre ligne 1',
                                'name' => 'titre_1',
                                'type' => 'text',
                                'default_value' => 'Des prix légers, une qualité béton',
                            ),
                            array(
                                'key' => 'field_hero_titre_2',
                                'label' => 'Titre ligne 2',
                                'name' => 'titre_2',
                                'type' => 'text',
                                'default_value' => 'Faites-le vous-même, mais pas tout seul.',
                            ),
                            array(
                                'key' => 'field_hero_slider',
                                'label' => 'Images du slider',
                                'name' => 'slider_images',
                                'type' => 'gallery',
                                'return_format' => 'url',
                                'preview_size' => 'medium',
                                'min' => 1,
                                'max' => 10,
                            ),
                            array(
                                'key' => 'field_hero_infos_mea',
                                'label' => 'Image infos MEA (colonne droite)',
                                'name' => 'infos_mea',
                                'type' => 'image',
                                'return_format' => 'url',
                                'preview_size' => 'medium',
                            ),
                        ),
                    ),
                    // PRODUITS PHARES
                    'layout_produits_phares' => array(
                        'key' => 'layout_produits_phares',
                        'name' => 'produits_phares',
                        'label' => 'Produits phares',
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_phares_surtitle',
                                'label' => 'Sur-titre',
                                'name' => 'surtitle',
                                'type' => 'text',
                                'default_value' => 'Votre projet. Votre savoir-faire.',
                            ),
                            array(
                                'key' => 'field_phares_title',
                                'label' => 'Titre',
                                'name' => 'title',
                                'type' => 'text',
                                'default_value' => 'Nos produits phares',
                            ),
                            array(
                                'key' => 'field_phares_nombre',
                                'label' => 'Nombre de produits',
                                'name' => 'nombre_produits',
                                'type' => 'number',
                                'default_value' => 10,
                                'min' => 1,
                                'max' => 20,
                            ),
                        ),
                    ),
                    // NOUVELLE GAMME
                    'layout_nouvelle_gamme' => array(
                        'key' => 'layout_nouvelle_gamme',
                        'name' => 'nouvelle_gamme',
                        'label' => 'Nouvelle gamme / Coming soon',
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_gamme_surtitle',
                                'label' => 'Sur-titre',
                                'name' => 'surtitle',
                                'type' => 'text',
                                'default_value' => 'Toujours plus de produits, toujours au meilleur prix',
                            ),
                            array(
                                'key' => 'field_gamme_title',
                                'label' => 'Titre',
                                'name' => 'title',
                                'type' => 'text',
                                'default_value' => 'La dernière gamme à rentrer au catalogue',
                            ),
                            array(
                                'key' => 'field_gamme_image',
                                'label' => 'Image',
                                'name' => 'image',
                                'type' => 'image',
                                'return_format' => 'url',
                                'preview_size' => 'medium',
                            ),
                            array(
                                'key' => 'field_gamme_texte',
                                'label' => 'Texte (ex: coming soon)',
                                'name' => 'texte',
                                'type' => 'text',
                                'default_value' => 'coming soon',
                            ),
                        ),
                    ),
                    // UNIVERS
                    'layout_univers' => array(
                        'key' => 'layout_univers',
                        'name' => 'univers',
                        'label' => 'Univers (pièces de la maison)',
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_univers_surtitle',
                                'label' => 'Sur-titre',
                                'name' => 'surtitle',
                                'type' => 'text',
                                'default_value' => 'Wiwilbild vous accompagne dans tous vos projets',
                            ),
                            array(
                                'key' => 'field_univers_title',
                                'label' => 'Titre',
                                'name' => 'title',
                                'type' => 'text',
                                'default_value' => 'À chaque pièce son univers',
                            ),
                            array(
                                'key' => 'field_univers_cards',
                                'label' => 'Cartes univers',
                                'name' => 'cards',
                                'type' => 'repeater',
                                'min' => 1,
                                'max' => 6,
                                'layout' => 'block',
                                'button_label' => 'Ajouter une carte',
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_univers_card_title',
                                        'label' => 'Titre',
                                        'name' => 'card_title',
                                        'type' => 'text',
                                        'wrapper' => array( 'width' => '30' ),
                                    ),
                                    array(
                                        'key' => 'field_univers_card_image',
                                        'label' => 'Image',
                                        'name' => 'card_image',
                                        'type' => 'image',
                                        'return_format' => 'url',
                                        'preview_size' => 'thumbnail',
                                        'wrapper' => array( 'width' => '30' ),
                                    ),
                                    array(
                                        'key' => 'field_univers_card_link',
                                        'label' => 'Lien',
                                        'name' => 'card_link',
                                        'type' => 'url',
                                        'wrapper' => array( 'width' => '40' ),
                                    ),
                                    array(
                                        'key' => 'field_univers_card_size',
                                        'label' => 'Taille',
                                        'name' => 'card_size',
                                        'type' => 'select',
                                        'choices' => array(
                                            'normal' => 'Normal',
                                            'big' => 'Grand (double largeur)',
                                        ),
                                        'default_value' => 'normal',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    // CATALOGUE
                    'layout_catalogue' => array(
                        'key' => 'layout_catalogue',
                        'name' => 'catalogue',
                        'label' => 'Liens catalogue',
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_catalogue_surtitle',
                                'label' => 'Sur-titre',
                                'name' => 'surtitle',
                                'type' => 'text',
                                'default_value' => 'Du choix, de la qualité, des prix',
                            ),
                            array(
                                'key' => 'field_catalogue_title',
                                'label' => 'Titre',
                                'name' => 'title',
                                'type' => 'text',
                                'default_value' => 'Tous nos produits',
                            ),
                            array(
                                'key' => 'field_catalogue_links',
                                'label' => 'Liens',
                                'name' => 'links',
                                'type' => 'repeater',
                                'min' => 1,
                                'layout' => 'table',
                                'button_label' => 'Ajouter un lien',
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_catalogue_link_text',
                                        'label' => 'Texte',
                                        'name' => 'link_text',
                                        'type' => 'text',
                                    ),
                                    array(
                                        'key' => 'field_catalogue_link_url',
                                        'label' => 'URL',
                                        'name' => 'link_url',
                                        'type' => 'url',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    // SECTION BOTTOM CTA
                    'layout_bottom_cta' => array(
                        'key' => 'layout_bottom_cta',
                        'name' => 'bottom_cta',
                        'label' => 'Section bas de page (CTA)',
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_bottom_surtitle',
                                'label' => 'Sur-titre',
                                'name' => 'surtitle',
                                'type' => 'text',
                                'default_value' => 'Wiwilbild, à l\'écoute de vos envies !',
                            ),
                            array(
                                'key' => 'field_bottom_title',
                                'label' => 'Titre',
                                'name' => 'title',
                                'type' => 'text',
                                'default_value' => 'Quel sera votre prochain projet ?',
                            ),
                            array(
                                'key' => 'field_bottom_text',
                                'label' => 'Texte',
                                'name' => 'text',
                                'type' => 'textarea',
                                'rows' => 4,
                            ),
                            array(
                                'key' => 'field_bottom_btn_text',
                                'label' => 'Texte du bouton',
                                'name' => 'btn_text',
                                'type' => 'text',
                                'default_value' => 'Découvrir nos produits',
                            ),
                            array(
                                'key' => 'field_bottom_btn_url',
                                'label' => 'Lien du bouton',
                                'name' => 'btn_url',
                                'type' => 'url',
                            ),
                            array(
                                'key' => 'field_bottom_images',
                                'label' => 'Images slider',
                                'name' => 'images',
                                'type' => 'gallery',
                                'return_format' => 'url',
                                'preview_size' => 'medium',
                            ),
                        ),
                    ),
                    // REASSURANCE
                    'layout_reassurance' => array(
                        'key' => 'layout_reassurance',
                        'name' => 'reassurance',
                        'label' => 'Bandeau de réassurance',
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_reassurance_note',
                                'label' => 'Note',
                                'name' => 'note',
                                'type' => 'message',
                                'message' => 'Ce bloc affiche le template reassurance.php existant.',
                            ),
                        ),
                    ),
                    // SHOP BY ROOM
                    'layout_shop_by_room' => array(
                        'key' => 'layout_shop_by_room',
                        'name' => 'shop_by_room',
                        'label' => 'Shop by Room (Parcourir par pièce)',
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_sbr_surtitle',
                                'label' => 'Sur-titre',
                                'name' => 'surtitle',
                                'type' => 'text',
                                'default_value' => 'Parcourir par pièce',
                            ),
                            array(
                                'key' => 'field_sbr_cta_text',
                                'label' => 'Texte du CTA',
                                'name' => 'cta_text',
                                'type' => 'text',
                                'default_value' => 'Voir par pièce',
                            ),
                            array(
                                'key' => 'field_sbr_rooms',
                                'label' => 'Pièces',
                                'name' => 'rooms',
                                'type' => 'repeater',
                                'min' => 1,
                                'max' => 8,
                                'layout' => 'block',
                                'button_label' => 'Ajouter une pièce',
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_sbr_room_name',
                                        'label' => 'Nom de la pièce',
                                        'name' => 'name',
                                        'type' => 'text',
                                        'wrapper' => array( 'width' => '30' ),
                                    ),
                                    array(
                                        'key' => 'field_sbr_room_link',
                                        'label' => 'Lien',
                                        'name' => 'link',
                                        'type' => 'url',
                                        'wrapper' => array( 'width' => '30' ),
                                    ),
                                    array(
                                        'key' => 'field_sbr_room_image_left',
                                        'label' => 'Image gauche',
                                        'name' => 'image_left',
                                        'type' => 'image',
                                        'return_format' => 'url',
                                        'preview_size' => 'thumbnail',
                                        'wrapper' => array( 'width' => '20' ),
                                    ),
                                    array(
                                        'key' => 'field_sbr_room_image_right',
                                        'label' => 'Image droite',
                                        'name' => 'image_right',
                                        'type' => 'image',
                                        'return_format' => 'url',
                                        'preview_size' => 'thumbnail',
                                        'wrapper' => array( 'width' => '20' ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'front-page.php',
                ),
            ),
            array(
                array(
                    'param' => 'page_type',
                    'operator' => '==',
                    'value' => 'front_page',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
    ));
}
