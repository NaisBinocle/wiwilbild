$(document).ready(function(){

    $(window).scroll(function(){
      if ($(window).scrollTop() <= 20) {
          $('header').removeClass('fixed-header');
      }
      else {
          $('header').addClass('fixed-header');
      }
    });


    $('input, textarea').change(function () {

      const value = $(this).val();
      if (value != '') {
      $(this).addClass('rempli');
      }

      else {
      $(this).removeClass('rempli');
      }

  });
});

jQuery( document ).ready(function($){

  
$('.accordion').click(function(){
  var sibling = $(this).next('.panel');
  sibling.slideToggle('slow');
  $(this).toggleClass('active');
});

});

jQuery( document ).ready(function($){
  
  $(".hero_slider.owl-carousel").owlCarousel({
    items: 1,
    loop: true,
  });
  
});

jQuery( document ).ready(function($){
  
  $(".featured.owl-carousel").owlCarousel({
    items: 4,
    loop: true,
    dots: true,
    dotsEach: 1,

    responsive: {
      0: {
        items: 1,
        dots: true,
        dotsEach: 1,
        margin: 0,
      },

      768: {
        items: 3,
        dots: true,
        dotsEach: 3,
        margin: 24,
      },

      1024: {
        items: 4,
        dots: true,
        dotsEach: 3,
        margin: 24,
      },

      1280: {
        items: 6,
        dots: true,
        dotsEach: true,
        margin: 24,
      },
    }
  });
  
});

document.addEventListener("DOMContentLoaded", function () {
    // Gestion des swatches couleurs
    document.querySelectorAll(".color-swatches .swatch").forEach(function (swatch) {
        swatch.addEventListener("click", function () {
            let value = this.getAttribute("data-value");
            let select = this.closest('td').querySelector('select');

            if (select) {
                select.value = value;
                select.dispatchEvent(new Event("change", { bubbles: true }));
            }

            // Retirer la sélection des autres swatches du même groupe
            this.closest('.color-swatches').querySelectorAll('.swatch').forEach(s => s.classList.remove("selected"));
            this.classList.add("selected");
        });
    });

    // Gestion des étiquettes attributs (dimensions, etc.)
    document.querySelectorAll(".attribute-swatches .attribute-swatch").forEach(function (swatch) {
        swatch.addEventListener("click", function () {
            let value = this.getAttribute("data-value");
            let select = this.closest('td').querySelector('select');

            if (select) {
                select.value = value;
                select.dispatchEvent(new Event("change", { bubbles: true }));
            }

            // Retirer la sélection des autres étiquettes du même groupe
            this.closest('.attribute-swatches').querySelectorAll('.attribute-swatch').forEach(s => s.classList.remove("selected"));
            this.classList.add("selected");
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
  const hamburger = document.querySelector(".hamburger");
  const menu = document.querySelector(".menu");
  const menu_mobile = document.querySelector("#menu-menu-mobile");

  hamburger.addEventListener("click", function () {
      hamburger.classList.toggle("active");
      menu.classList.toggle("open");
      menu_mobile.classList.toggle("open");
  });
});

/*Script calculateur surface - Carrelage avec saisie m² et calcul boîtes*/
jQuery(document).ready(function ($) {
    const calculator = $('#surface-calculator');
    if (!calculator.length) return;

    let surfaceUnitaire = parseFloat(calculator.data('surface-unitaire')) || 0;

    // Met à jour la surface unitaire quand on change de variation
    function updateSurfaceUnitaire(variation) {
        let length = parseFloat(variation?.dimensions?.length || 0);
        let width = parseFloat(variation?.dimensions?.width || 0);
        if (length > 0 && width > 0) {
            surfaceUnitaire = (length / 100) * (width / 100);
            calculator.data('surface-unitaire', surfaceUnitaire);
            $('#surface-per-box').text(surfaceUnitaire.toFixed(2).replace('.', ','));
        }
    }

    // Calcule le nombre de boîtes nécessaires pour une surface donnée
    function calculateBoxesFromSurface() {
        let surface = parseFloat($('#surface-input').val()) || 0;
        let addMargin = $('#add-margin').is(':checked');

        if (addMargin) {
            surface = surface * 1.10; // +10%
        }

        if (surface > 0 && surfaceUnitaire > 0) {
            let boxes = Math.ceil(surface / surfaceUnitaire);
            $('#box-quantity').val(boxes);
            updateResults();
        }
    }

    // Met à jour les résultats affichés
    function updateResults() {
        let boxes = parseInt($('#box-quantity').val()) || 1;
        let totalSurface = (boxes * surfaceUnitaire).toFixed(2);

        $('#surface-result').text(totalSurface.replace('.', ','));
        $('#box-count').text(boxes);

        // Synchroniser avec la quantité WooCommerce
        $('.input-text.qty').val(boxes).trigger('change');
    }

    // Event: saisie de surface
    $('#surface-input').on('input', function() {
        calculateBoxesFromSurface();
    });

    // Event: checkbox marge 10%
    $('#add-margin').on('change', function() {
        calculateBoxesFromSurface();
    });

    // Event: boutons +/-
    $('.qty-btn.plus').on('click', function() {
        let current = parseInt($('#box-quantity').val()) || 1;
        $('#box-quantity').val(current + 1);
        $('#surface-input').val(''); // Reset le champ surface
        updateResults();
    });

    $('.qty-btn.minus').on('click', function() {
        let current = parseInt($('#box-quantity').val()) || 1;
        if (current > 1) {
            $('#box-quantity').val(current - 1);
            $('#surface-input').val(''); // Reset le champ surface
            updateResults();
        }
    });

    // Event: changement de variation WooCommerce
    $(document).on('found_variation', 'form.variations_form', function (event, variation) {
        updateSurfaceUnitaire(variation);
        updateResults();
    });

    // Initialisation
    updateResults();
});

/* Prix au mètre carré pour les variations */
jQuery(document).ready(function($) {
  function ajouterSuffixeM2() {
      var categoriesCiblees = ['carrelage'];
      
      // Vérifier si le produit appartient à une des catégories ciblées
      var productCategories = $('body').attr('class').match(/product-cat-([^\s]+)/g);
      if (productCategories) {
          productCategories = productCategories.map(cat => cat.replace('product-cat-', ''));
          
          if (productCategories.some(cat => categoriesCiblees.includes(cat))) {
              // Ajouter "/m²" après tous les prix visibles
              $('.woocommerce-variation-price .woocommerce-Price-amount.amount').each(function() {
                  var priceText = $(this).html();
                  if (!priceText.includes('/m²')) {
                      $(this).html(priceText + ' /m²');
                  }
              });
          }
      }
  }

  // Appliquer au chargement de la page
  ajouterSuffixeM2();

  // Appliquer après la sélection d'une variation
  $('form.variations_form').on('found_variation', function() {
      ajouterSuffixeM2();
  });
});

/* Hotspots interactifs - Gestion clic mobile */
document.addEventListener('DOMContentLoaded', function() {
    // Attendre que les hotspots soient injectés
    setTimeout(function() {
        const hotspots = document.querySelectorAll('.hotspot');

        hotspots.forEach(function(hotspot) {
            hotspot.addEventListener('click', function(e) {
                e.stopPropagation();

                // Sur mobile, toggle la classe active pour afficher/masquer la tooltip
                const isActive = this.classList.contains('active');

                // Fermer tous les autres hotspots
                hotspots.forEach(function(h) {
                    h.classList.remove('active');
                });

                // Toggle celui cliqué
                if (!isActive) {
                    this.classList.add('active');
                }
            });
        });

        // Fermer les tooltips au clic ailleurs
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.hotspot')) {
                hotspots.forEach(function(h) {
                    h.classList.remove('active');
                });
            }
        });
    }, 500); // Délai pour attendre l'injection des hotspots
});

/* Shop by Room - Changement d'images au survol */
document.addEventListener('DOMContentLoaded', function() {
    const shopByRoom = document.querySelector('.shop-by-room');
    if (!shopByRoom) return;

    const roomLinks = shopByRoom.querySelectorAll('.sbr-rooms a');
    const leftImages = shopByRoom.querySelectorAll('.sbr-image-left img');
    const rightImages = shopByRoom.querySelectorAll('.sbr-image-right img');

    function setActiveRoom(roomIndex) {
        // Mettre à jour les liens
        roomLinks.forEach(function(link, index) {
            if (parseInt(link.getAttribute('data-room')) === roomIndex) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });

        // Mettre à jour les images gauche
        leftImages.forEach(function(img) {
            if (parseInt(img.getAttribute('data-room')) === roomIndex) {
                img.classList.add('active');
            } else {
                img.classList.remove('active');
            }
        });

        // Mettre à jour les images droite
        rightImages.forEach(function(img) {
            if (parseInt(img.getAttribute('data-room')) === roomIndex) {
                img.classList.add('active');
            } else {
                img.classList.remove('active');
            }
        });
    }

    // Événement hover sur les liens
    roomLinks.forEach(function(link) {
        link.addEventListener('mouseenter', function() {
            const roomIndex = parseInt(this.getAttribute('data-room'));
            setActiveRoom(roomIndex);
        });
    });

    // Sur mobile : événement click
    roomLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            // Ne pas empêcher la navigation, mais mettre à jour l'affichage
            const roomIndex = parseInt(this.getAttribute('data-room'));
            setActiveRoom(roomIndex);
        });
    });
});
