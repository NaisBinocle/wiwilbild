<?php
/**
 * Title: Nouvelle collection
 * Slug: wwb/new-collection
 * Categories: wwb-homepage
 * Description: Section nouvelle gamme / coming soon
 */
?>

<!-- wp:group {"className":"section-new-collection","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"backgroundColor":"background-alt","layout":{"type":"constrained"}} -->
<div class="wp-block-group section-new-collection has-background-alt-background-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">

	<!-- wp:group {"className":"section-header","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group section-header" style="margin-bottom:var(--wp--preset--spacing--40)">

		<!-- wp:paragraph {"align":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"textColor":"foreground-alt","fontSize":"medium"} -->
		<p class="has-text-align-center has-foreground-alt-color has-text-color has-medium-font-size" style="font-style:normal;font-weight:500">Toujours plus de produits, toujours au meilleur prix</p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"textAlign":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"fontSize":"xx-large"} -->
		<h2 class="wp-block-heading has-text-align-center has-xx-large-font-size" style="font-style:normal;font-weight:700">La dernière gamme à rentrer au catalogue</h2>
		<!-- /wp:heading -->

	</div>
	<!-- /wp:group -->

	<!-- wp:cover {"dimRatio":50,"overlayColor":"secondary","minHeight":400,"minHeightUnit":"px","contentPosition":"center center","isDark":true,"style":{"border":{"radius":"15px"}}} -->
	<div class="wp-block-cover is-dark" style="border-radius:15px;min-height:400px">
		<span aria-hidden="true" class="wp-block-cover__background has-secondary-background-color has-background-dim"></span>
		<div class="wp-block-cover__inner-container">

			<!-- wp:heading {"textAlign":"center","level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"700","textTransform":"uppercase","letterSpacing":"5px"}},"textColor":"background","fontSize":"huge"} -->
			<h3 class="wp-block-heading has-text-align-center has-background-color has-text-color has-huge-font-size" style="font-style:normal;font-weight:700;letter-spacing:5px;text-transform:uppercase">Coming Soon</h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"align":"center","textColor":"background","fontSize":"large"} -->
			<p class="has-text-align-center has-background-color has-text-color has-large-font-size">Une nouvelle gamme arrive bientôt...</p>
			<!-- /wp:paragraph -->

		</div>
	</div>
	<!-- /wp:cover -->

</div>
<!-- /wp:group -->
