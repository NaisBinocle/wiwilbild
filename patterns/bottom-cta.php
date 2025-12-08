<?php
/**
 * Title: CTA bas de page
 * Slug: wwb/bottom-cta
 * Categories: wwb-homepage
 * Description: Section CTA avec texte et images
 */
?>

<!-- wp:group {"className":"section-bottom-cta","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"backgroundColor":"background","layout":{"type":"default"}} -->
<div class="wp-block-group section-bottom-cta has-background-background-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">

	<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|50"}}}} -->
	<div class="wp-block-columns">

		<!-- wp:column {"width":"45%","style":{"spacing":{"padding":{"left":"5%"}}}} -->
		<div class="wp-block-column" style="padding-left:5%;flex-basis:45%">

			<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"textColor":"foreground-alt","fontSize":"medium"} -->
			<p class="has-foreground-alt-color has-text-color has-medium-font-size" style="font-style:normal;font-weight:500">Wiwilbild, à l'écoute de vos envies !</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"700"},"spacing":{"margin":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|20"}}},"fontSize":"xx-large"} -->
			<h3 class="wp-block-heading has-xx-large-font-size" style="margin-top:var(--wp--preset--spacing--10);margin-bottom:var(--wp--preset--spacing--20);font-style:normal;font-weight:700">Quel sera votre prochain projet ?</h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.7"}},"textColor":"foreground-alt","fontSize":"medium"} -->
			<p class="has-foreground-alt-color has-text-color has-medium-font-size" style="line-height:1.7">Vous aimez le travail bien fait… surtout quand c'est le vôtre ? Chez Wiwilbild, on parle le même langage. Matériaux, conseils et solutions malignes : tout ce qu'il vous faut pour mener à bien vos projets sans exploser votre budget.</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}}} -->
			<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--30)">
				<!-- wp:button {"backgroundColor":"primary","textColor":"foreground","style":{"border":{"radius":"50px"}}} -->
				<div class="wp-block-button"><a class="wp-block-button__link has-foreground-color has-primary-background-color has-text-color has-background wp-element-button" style="border-radius:50px">Découvrir nos produits</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->

		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"55%"} -->
		<div class="wp-block-column" style="flex-basis:55%">

			<!-- wp:gallery {"columns":3,"linkTo":"none","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|20","left":"var:preset|spacing|20"}}}} -->
			<figure class="wp-block-gallery has-nested-images columns-3 is-cropped">
				<!-- wp:image {"sizeSlug":"large","linkDestination":"none","style":{"border":{"radius":"10px"}}} -->
				<figure class="wp-block-image size-large" style="border-radius:10px"><img src="" alt="Inspiration 1"/></figure>
				<!-- /wp:image -->
				<!-- wp:image {"sizeSlug":"large","linkDestination":"none","style":{"border":{"radius":"10px"}}} -->
				<figure class="wp-block-image size-large" style="border-radius:10px"><img src="" alt="Inspiration 2"/></figure>
				<!-- /wp:image -->
				<!-- wp:image {"sizeSlug":"large","linkDestination":"none","style":{"border":{"radius":"10px"}}} -->
				<figure class="wp-block-image size-large" style="border-radius:10px"><img src="" alt="Inspiration 3"/></figure>
				<!-- /wp:image -->
			</figure>
			<!-- /wp:gallery -->

		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
