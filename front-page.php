<?php /* template name: Accueil */ ?>

<?php
    $bg_hero = get_field('bg_hero');
    $hp_sections = get_field('hp_sections');
?>

<?php get_header('home'); ?>

    <main class="hp">
        <?php if ($bg_hero) : ?>
            <div class="bg_wrapper">
                <img class="hero_bg_img" src="<?php echo esc_url($bg_hero); ?>" alt="">
            </div>
        <?php endif; ?>

        <?php
        // Nouveau système flexible ACF
        if ($hp_sections) :
            while (have_rows('hp_sections')) : the_row();
                $layout = get_row_layout();

                switch ($layout) {
                    case 'hero':
                        get_template_part('template-parts/homepage/section', 'hero');
                        break;

                    case 'produits_phares':
                        get_template_part('template-parts/homepage/section', 'produits-phares');
                        break;

                    case 'nouvelle_gamme':
                        get_template_part('template-parts/homepage/section', 'nouvelle-gamme');
                        break;

                    case 'univers':
                        get_template_part('template-parts/homepage/section', 'univers');
                        break;

                    case 'catalogue':
                        get_template_part('template-parts/homepage/section', 'catalogue');
                        break;

                    case 'bottom_cta':
                        get_template_part('template-parts/homepage/section', 'bottom-cta');
                        break;

                    case 'reassurance':
                        get_template_part('reassurance');
                        break;
                }

            endwhile;

        else :
            // Fallback : ancien code si aucune section flexible n'est définie
            $infos_mea = get_field('infos_mea');
        ?>

            <section class="hero">
                <div class="container hero_title">
                    <div>
                        <h2>Des prix légers, une qualité béton</h2>
                        <h2>Faites-le vous-même, mais pas tout seul.</h2>
                    </div>
                </div>

                <div class="container grid-12">
                    <div class="hero_slider owl-carousel owl-theme col-8">
                        <?php if (have_rows('slider_hero_homepage')) : ?>
                            <?php while (have_rows('slider_hero_homepage')) : the_row();
                                $slider_hero = get_sub_field('image_slider_hero_hp');

                                if ($slider_hero) : ?>
                                    <div class="slide">
                                        <img src="<?php echo esc_url($slider_hero); ?>" alt="">
                                    </div>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>

                    <div class="col-4 info_mea">
                        <img src="<?php echo $infos_mea ;?>" alt="">
                    </div>
                </div>
            </section>

            <section class="pdts_phares">
                <div class="container ft_title">
                    <span>Votre projet. Votre savoir-faire.</span>
                    <h2>Nos produits phares</h2>
                </div>
                <div class="featured_slider">
                    <?php
                        $args = array(
                            'limit' => 10,
                            'status' => 'publish',
                            'featured' => true,
                        );
                        $products = wc_get_products($args);
                    ?>
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

            <section class="next_usine">
                <div class="container usine_title">
                    <div>
                        <span>Toujours plus de produits, toujours au meilleur prix</span>
                        <h2>La dernière gamme à rentrer au catalogue </h2>
                    </div>
                </div>
                <div class="container grid-12">
                    <div class="col-12 content">
                        <img src="<?php echo get_template_directory_uri(); ?>/img/usine.jpg" alt="">
                        <p>coming soon</p>
                    </div>
                </div>
            </section>

            <section class="univers">
                <div class="container univers_title">
                    <div>
                        <span>Wiwilbild vous accompagne dans tous vos projets</span>
                        <h2>À chaque pièce son univers</h2>
                    </div>
                </div>

                <div class="container grid-12 univers_content">
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
                </div>
            </section>

            <section class="hp_catalogue">
                <div class="container catalogue_title">
                    <div>
                        <span>Du choix, de la qualité, des prix</span>
                        <h2>Tous nos produits</h2>
                    </div>
                </div>
                <div class="container grid-12">
                    <div class="col-2">
                        <a href="<?php echo home_url('/categorie-produit/parquet/parquet-stratifie'); ?>">Parquet stratifié</a>
                    </div>
                </div>
            </section>

            <section class="hp_bottom">
                <div class="container_fluid right">
                    <div class="content">
                        <span>Wiwilbild, à l'écoute de vos envies !</span>
                        <h3>Quel sera votre prochain projet ?</h3>
                        <p class="text">
                        Vous aimez le travail bien fait… surtout quand c'est le vôtre ? Chez Wiwilbild, on parle le même langage. Matériaux, conseils et solutions malignes : tout ce qu'il vous faut pour mener à bien vos projets sans exploser votre budget.
                        </p>
                        <a class="pink" href="#">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/jumelles.png" alt="">
                            <p>Découvrir nos produits</p>
                        </a>
                    </div>

                    <div class="bottom_hp_slider">
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

        <?php endif; ?>
    </main>

<?php get_footer(); ?>
