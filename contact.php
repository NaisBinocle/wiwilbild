<?php /* template name: Nous rejoindre */ ?>

<?php get_header(); ?>


    <main class="contact">
        <section class="contact_hero">
            <div class="container grid-12">
                <div class="grid-offset col-3"></div>
                <div class="col-6 hero_content cn_content">
                    <p>Nous <span>rejoindre.</span></p>
                    <img class="blue_smile" src="<?php echo get_template_directory_uri(); ?>/img/blue_smile.png" alt="">
                </div>
            </div>
        </section>


        <section class="contact_desc">
            <div class="container grid-12">
                <div class="grid-offset col-2"></div>
                <div class="col-8 content">
                    <p>Notre groupe se développe rapidement.</p>
                    <p>Toujours en recherche de nouveaux talents et de nouvelles expertises, nous recrutons régulièrement.</p>
                </div>
            </div>
        </section>

        <section class="contact_form">
            <div class="container grid-12">
                <div class="grid-offset"></div>

                <div class="col-4 blue">
                    <a class="logo" href="<?php echo bloginfo('url'); ?>">
                        <img src="<?php echo get_template_directory_uri(); ?>/img/logo_footer.svg" alt="">
                    </a>
                    <a href="mailto:contact@bonumgroup.com">contact@bonumgroup.com</a>
                </div>

                <div class="col-6">
                    <?php echo the_content(); ?>
                </div>
            </div>
        </section>
    </main>

<?php get_footer(); ?>