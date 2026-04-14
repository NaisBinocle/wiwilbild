<?php
/**
 * Title: Produits phares
 * Slug: wwb-v2/featured-products
 * Categories: wwb-homepage
 * Keywords: produits, bestseller, woocommerce
 */
?>

<!-- wp:group {"align":"full","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|70","right":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--70)">

	<!-- wp:group {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"600px"}} -->
	<div class="wp-block-group" style="margin-bottom:var(--wp--preset--spacing--50)">
		<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"12px","fontWeight":"700","textTransform":"uppercase","letterSpacing":"2px"}},"textColor":"primary"} -->
		<p class="has-text-align-center has-primary-color has-text-color" style="font-size:12px;font-weight:700;letter-spacing:2px;text-transform:uppercase">Nos fenêtres populaires</p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"textAlign":"center","level":2} -->
		<h2 class="wp-block-heading has-text-align-center">Les fenêtres les plus demandées</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","textColor":"body"} -->
		<p class="has-text-align-center has-body-color has-text-color">PVC, aluminium, sur-mesure : trouvez la fenêtre adaptée à votre projet.</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:woocommerce/product-collection {"queryId":10,"query":{"perPage":4,"pages":1,"offset":0,"postType":"product","order":"desc","orderBy":"popularity","featured":true,"woocommerceOnSale":false,"woocommerceStockStatus":["instock"],"woocommerceHandPickedProducts":[],"taxQuery":{},"isProductCollectionBlock":true},"displayLayout":{"type":"flex","columns":4},"collection":"woocommerce/product-collection/best-sellers"} -->
		<!-- wp:woocommerce/product-template -->
			<!-- wp:woocommerce/product-image {"isDescendentOfQueryLoop":true,"style":{"border":{"radius":"var:custom|radius|card"}},"aspectRatio":"4/3"} /-->
			<!-- wp:post-title {"textAlign":"left","level":3,"isLink":true,"style":{"typography":{"fontSize":"14px","fontWeight":"600"}},"textColor":"foreground"} /-->
			<!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true,"style":{"typography":{"fontSize":"18px","fontWeight":"800"}},"textColor":"primary-dark"} /-->
		<!-- /wp:woocommerce/product-template -->
	<!-- /wp:woocommerce/product-collection -->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
	<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
		<!-- wp:button {"className":"is-style-outline","style":{"border":{"width":"1.5px","color":"var:preset|color|border"},"typography":{"fontWeight":"600"}},"textColor":"foreground"} -->
		<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-foreground-color has-text-color wp-element-button" style="border-color:var(--wp--preset--color--border);border-width:1.5px;font-weight:600" href="<?php echo esc_url( home_url( '/boutique/' ) ); ?>">Voir toutes les fenêtres →</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</div>
<!-- /wp:group -->
