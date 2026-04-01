<?php
/**
 * Title: Grille catégories
 * Slug: wwb-v2/categories-grid
 * Categories: wwb-homepage
 * Keywords: catégories, carrelage, menuiserie, couverture
 */
?>

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|70","right":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--70)">

	<!-- wp:group {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"600px"}} -->
	<div class="wp-block-group" style="margin-bottom:var(--wp--preset--spacing--50)">
		<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"12px","fontWeight":"700","textTransform":"uppercase","letterSpacing":"2px"}},"textColor":"primary"} -->
		<p class="has-text-align-center has-primary-color has-text-color" style="font-size:12px;font-weight:700;letter-spacing:2px;text-transform:uppercase">Nos univers</p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"textAlign":"center","level":2} -->
		<h2 class="wp-block-heading has-text-align-center">Votre prochaine pièce commence ici</h2>
		<!-- /wp:heading -->
	</div>
	<!-- /wp:group -->

	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|30"}}}} -->
	<div class="wp-block-columns">

		<!-- wp:column {"width":"55%"} -->
		<div class="wp-block-column" style="flex-basis:55%">
			<!-- wp:group {"style":{"border":{"radius":"var:custom|radius|card"},"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}},"dimensions":{"minHeight":"400px"}},"backgroundColor":"surface","layout":{"type":"constrained","justifyContent":"left"}} -->
			<div class="wp-block-group has-surface-background-color has-background" style="border-radius:var(--wp--custom--radius--card);min-height:400px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
				<!-- wp:paragraph {"style":{"typography":{"fontSize":"12px","fontWeight":"700","textTransform":"uppercase","letterSpacing":"2px"}},"textColor":"primary"} -->
				<p class="has-primary-color has-text-color" style="font-size:12px;font-weight:700;letter-spacing:2px;text-transform:uppercase">Carrelages</p>
				<!-- /wp:paragraph -->

				<!-- wp:heading {"level":3,"fontSize":"xl"} -->
				<h3 class="wp-block-heading has-xl-font-size">Intérieur, extérieur, tous les formats</h3>
				<!-- /wp:heading -->

				<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|25"}}}} -->
				<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--25)">
					<!-- wp:button {"backgroundColor":"primary","textColor":"primary-dark","style":{"typography":{"fontWeight":"600","fontSize":"14px"}}} -->
					<div class="wp-block-button"><a class="wp-block-button__link has-primary-dark-color has-primary-background-color has-text-color has-background wp-element-button" style="font-size:14px;font-weight:600" href="<?php echo esc_url( home_url( '/categorie-produit/carrelages/' ) ); ?>">Découvrir →</a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"45%"} -->
		<div class="wp-block-column" style="flex-basis:45%">

			<!-- wp:group {"style":{"border":{"radius":"var:custom|radius|card"},"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"},"margin":{"bottom":"var:preset|spacing|30"}},"dimensions":{"minHeight":"185px"}},"backgroundColor":"surface","layout":{"type":"constrained"}} -->
			<div class="wp-block-group has-surface-background-color has-background" style="border-radius:var(--wp--custom--radius--card);min-height:185px;margin-bottom:var(--wp--preset--spacing--30);padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
				<!-- wp:heading {"level":4,"style":{"typography":{"fontWeight":"700"}},"fontSize":"lg"} -->
				<h4 class="wp-block-heading has-lg-font-size" style="font-weight:700">Menuiseries</h4>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"body","fontSize":"sm"} -->
				<p class="has-body-color has-text-color has-sm-font-size">Fenêtres, portes-fenêtres et volets PVC</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->

			<!-- wp:group {"style":{"border":{"radius":"var:custom|radius|card"},"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}},"dimensions":{"minHeight":"185px"}},"backgroundColor":"surface","layout":{"type":"constrained"}} -->
			<div class="wp-block-group has-surface-background-color has-background" style="border-radius:var(--wp--custom--radius--card);min-height:185px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
				<!-- wp:heading {"level":4,"style":{"typography":{"fontWeight":"700"}},"fontSize":"lg"} -->
				<h4 class="wp-block-heading has-lg-font-size" style="font-weight:700">Couvertures</h4>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"body","fontSize":"sm"} -->
				<p class="has-body-color has-text-color has-sm-font-size">Tuiles, étanchéité et accessoires de toiture</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->

		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
