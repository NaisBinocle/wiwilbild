<?php
/**
 * Title: Hero Section
 * Slug: wwb/hero-section
 * Categories: wwb-homepage
 * Description: Section hero avec slider et image MEA
 */
?>

<!-- wp:cover {"dimRatio":0,"minHeight":600,"minHeightUnit":"px","isDark":false,"className":"hero-section","style":{"spacing":{"padding":{"top":"0","bottom":"0"}}}} -->
<div class="wp-block-cover is-light hero-section" style="padding-top:0;padding-bottom:0;min-height:600px">
	<span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span>
	<div class="wp-block-cover__inner-container">

		<!-- wp:group {"className":"hero-titles","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group hero-titles" style="margin-bottom:var(--wp--preset--spacing--40)">

			<!-- wp:heading {"level":2,"style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"textColor":"foreground","fontSize":"huge"} -->
			<h2 class="wp-block-heading has-foreground-color has-text-color has-huge-font-size" style="font-style:normal;font-weight:700">Des prix légers, une qualité béton</h2>
			<!-- /wp:heading -->

			<!-- wp:heading {"level":2,"style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"textColor":"foreground","fontSize":"huge"} -->
			<h2 class="wp-block-heading has-foreground-color has-text-color has-huge-font-size" style="font-style:normal;font-weight:700">Faites-le vous-même, mais pas tout seul.</h2>
			<!-- /wp:heading -->

		</div>
		<!-- /wp:group -->

		<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
		<div class="wp-block-columns">

			<!-- wp:column {"width":"66.66%"} -->
			<div class="wp-block-column" style="flex-basis:66.66%">

				<!-- wp:gallery {"columns":1,"linkTo":"none","className":"hero-slider","style":{"border":{"radius":"15px"}}} -->
				<figure class="wp-block-gallery has-nested-images columns-1 is-cropped hero-slider" style="border-radius:15px">
					<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
					<figure class="wp-block-image size-large"><img src="" alt="Slide 1"/></figure>
					<!-- /wp:image -->
					<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
					<figure class="wp-block-image size-large"><img src="" alt="Slide 2"/></figure>
					<!-- /wp:image -->
				</figure>
				<!-- /wp:gallery -->

			</div>
			<!-- /wp:column -->

			<!-- wp:column {"width":"33.33%"} -->
			<div class="wp-block-column" style="flex-basis:33.33%">

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none","className":"info-mea","style":{"border":{"radius":"15px"}}} -->
				<figure class="wp-block-image size-large info-mea" style="border-radius:15px"><img src="" alt="Offre spéciale"/></figure>
				<!-- /wp:image -->

			</div>
			<!-- /wp:column -->

		</div>
		<!-- /wp:columns -->

	</div>
</div>
<!-- /wp:cover -->
