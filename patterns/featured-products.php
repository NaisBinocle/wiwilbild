<?php
/**
 * Title: Produits phares
 * Slug: wwb/featured-products
 * Categories: wwb-homepage
 * Description: Carousel des produits mis en avant
 */
?>

<!-- wp:group {"className":"section-featured-products","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"backgroundColor":"background","layout":{"type":"constrained"}} -->
<div class="wp-block-group section-featured-products has-background-background-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">

	<!-- wp:group {"className":"section-header","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group section-header" style="margin-bottom:var(--wp--preset--spacing--40)">

		<!-- wp:paragraph {"align":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"textColor":"foreground-alt","fontSize":"medium"} -->
		<p class="has-text-align-center has-foreground-alt-color has-text-color has-medium-font-size" style="font-style:normal;font-weight:500">Votre projet. Votre savoir-faire.</p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"textAlign":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"fontSize":"xx-large"} -->
		<h2 class="wp-block-heading has-text-align-center has-xx-large-font-size" style="font-style:normal;font-weight:700">Nos produits phares</h2>
		<!-- /wp:heading -->

	</div>
	<!-- /wp:group -->

	<!-- wp:woocommerce/product-collection {"queryId":10,"query":{"perPage":6,"pages":0,"offset":0,"postType":"product","order":"desc","orderBy":"date","search":"","exclude":[],"inherit":false,"taxQuery":{},"isProductCollectionBlock":true,"featured":true,"woocommerceOnSale":false,"woocommerceStockStatus":["instock","outofstock","onbackorder"],"woocommerceAttributes":[],"woocommerceHandPickedProducts":[]},"tagName":"div","displayLayout":{"type":"flex","columns":4,"shrinkColumns":true},"className":"featured-products-grid"} -->
	<div class="wp-block-woocommerce-product-collection featured-products-grid">

		<!-- wp:woocommerce/product-template -->

			<!-- wp:woocommerce/product-image {"imageSizing":"thumbnail","isDescendentOfQueryLoop":true,"style":{"border":{"radius":"10px"}}} /-->

			<!-- wp:post-title {"textAlign":"center","level":3,"isLink":true,"style":{"typography":{"fontSize":"1rem","fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"var:preset|spacing|20"}}}} /-->

			<!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true,"textAlign":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"textColor":"primary","fontSize":"medium"} /-->

		<!-- /wp:woocommerce/product-template -->

	</div>
	<!-- /wp:woocommerce/product-collection -->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
	<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
		<!-- wp:button {"backgroundColor":"primary","textColor":"foreground","style":{"border":{"radius":"50px"}}} -->
		<div class="wp-block-button"><a class="wp-block-button__link has-foreground-color has-primary-background-color has-text-color has-background wp-element-button" style="border-radius:50px">Voir tous les produits</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</div>
<!-- /wp:group -->
