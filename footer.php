    <footer>
      <div class="container grid-12 liens">
        <div class="col-2 footer_logo">
          <a href="<?php echo bloginfo('url'); ?>">
            <img src="<?php echo get_template_directory_uri(); ?>/img/main_logo.webp" alt="">
          </a>
        </div>


        <div class="col-7 footer_catalogue">
          <div class="categorie">
            <h3>Catégorie</h3>
            <a href="#">Produit</a>
            <a href="#">Produit</a>
            <a href="#">Produit nom long</a>
            <a href="#">Produit</a>
            <a href="#">Produit</a>
          </div>

          <div class="categorie">
            <h3>Catégorie</h3>
            <a href="#">Produit nom long</a>
            <a href="#">Produit</a>
            <a href="#">Produit</a>
            <a href="#">Produit</a>
            <a href="#">Produit</a>
          </div>

          <div class="categorie">
            <h3>Catégorie</h3>
            <a href="#">Produit</a>
            <a href="#">Produit</a>
            <a href="#">Produit</a>
            <a href="#">Produit</a>
            <a href="#">Produit nom long</a>
          </div>
        </div>

        <div class="grid-offset col-1"></div>

        <div class="col-2 socials_footer">
          <a href="">
            <img src="<?php echo get_template_directory_uri().'/img/instagram.png'; ?>" alt="">
          </a>
          <a href="">
            <img src="<?php echo get_template_directory_uri().'/img/youtube.png'; ?>" alt="">
          </a>
          <a href="">
            <img src="<?php echo get_template_directory_uri().'/img/linkedin.png'; ?>" alt="">
          </a>
        </div>

      </div>

      <div class="container payments">

      </div>

      <div class="container legal">
        <a href="#">Mentions légales</a>
        <p>-</p>
        <a href="#">CGV</a>
        <p>-</p>
        <a href="#">Politique de confidentialité</a>
      </div>

      <div class="bandeau_bottom">
        <div class="container">
          <p>©<?php echo date('Y'); ?> - Wiwilbild x</p>
          <a target="_blank" href="https://agencebinocle.bzh/">Binocle</a>
        </div>
      </div>

    </footer>
  </body>

<?php wp_footer() ?>

    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/owl.carousel.min.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/script.js"></script>


</html>

