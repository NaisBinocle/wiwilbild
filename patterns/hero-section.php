<?php
/**
 * Title: Hero Section
 * Slug: wwb-v2/hero-section
 * Categories: wwb-homepage
 * Keywords: hero, accueil, banner
 */
?>

<!-- wp:group {"align":"full","gradient":"hero","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|70","right":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-hero-gradient-background has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--70)">

	<!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50"}}}} -->
	<div class="wp-block-columns are-vertically-aligned-center">

		<!-- wp:column {"verticalAlignment":"center","width":"50%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%">

			<!-- wp:paragraph {"style":{"typography":{"fontSize":"12px","fontWeight":"700","textTransform":"uppercase","letterSpacing":"2px"}},"textColor":"primary"} -->
			<p class="has-primary-color has-text-color" style="font-size:12px;font-weight:700;letter-spacing:2px;text-transform:uppercase">N°1 matériaux premium en ligne</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":1,"style":{"spacing":{"margin":{"top":"var:preset|spacing|20"}}}} -->
			<h1 class="wp-block-heading" style="margin-top:var(--wp--preset--spacing--20)">Commandez vos matériaux <em>premium</em> en ligne, comme en showroom</h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"var:preset|spacing|25"}},"typography":{"fontSize":"17px","lineHeight":"1.7"}},"textColor":"body"} -->
			<p class="has-body-color has-text-color" style="font-size:17px;line-height:1.7;margin-top:var(--wp--preset--spacing--25)">Carrelages, menuiseries, couvertures : explorez nos gammes et nos experts vous accompagnent à chaque étape.</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
			<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
				<!-- wp:button {"backgroundColor":"primary","textColor":"primary-dark","style":{"typography":{"fontWeight":"600"}}} -->
				<div class="wp-block-button"><a class="wp-block-button__link has-primary-dark-color has-primary-background-color has-text-color has-background wp-element-button" style="font-weight:600" href="/boutique/">Découvrir le catalogue →</a></div>
				<!-- /wp:button -->

				<!-- wp:button {"className":"is-style-outline","style":{"border":{"width":"1.5px","color":"var:preset|color|border"},"typography":{"fontWeight":"600"}},"textColor":"foreground"} -->
				<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-foreground-color has-text-color wp-element-button" style="border-color:var(--wp--preset--color--border);border-width:1.5px;font-weight:600" href="/contact/">Parler à un conseiller</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->

		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center","width":"50%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%">
			<!-- wp:image {"sizeSlug":"large","style":{"border":{"radius":"var:custom|radius|card"}}} -->
			<figure class="wp-block-image size-large" style="border-radius:var(--wp--custom--radius--card)"><img src="https://placehold.co/720x520/EAE9EC/362C49?text=Hero+Image" alt="Matériaux premium Wiwilbild"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
