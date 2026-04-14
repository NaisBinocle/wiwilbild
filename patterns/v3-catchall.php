<?php
/**
 * Title: V3 Catch-all CTA
 * Slug: wwb-v2/v3-catchall
 * Categories: wwb-homepage
 * Keywords: cta, conversion, action, contact
 */
?>

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|70","right":"var:preset|spacing|70"}},"color":{"gradient":"linear-gradient(135deg, #362C49 0%, #4A3D66 100%)"}},"layout":{"type":"constrained","contentSize":"680px"}} -->
<div class="wp-block-group alignfull has-background" style="background:linear-gradient(135deg, #362C49 0%, #4A3D66 100%);padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--70)">

	<!-- wp:heading {"textAlign":"center","level":2,"style":{"typography":{"fontSize":"clamp(1.8rem, 3.5vw, 2.5rem)"},"color":{"text":"#FFFFFF"}}} -->
	<h2 class="wp-block-heading has-text-align-center has-text-color" style="color:#FFFFFF;font-size:clamp(1.8rem, 3.5vw, 2.5rem)">Prêt à changer vos fenêtres ?</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"17px","lineHeight":"1.7"},"spacing":{"margin":{"top":"var:preset|spacing|20"}}},"textColor":"muted"} -->
	<p class="has-text-align-center has-muted-color has-text-color" style="font-size:17px;line-height:1.7;margin-top:var(--wp--preset--spacing--20)">Configurez vos fenêtres en quelques clics, obtenez votre prix en temps réel et profitez d'une livraison partout en France.</p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|15"}}} -->
	<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
		<!-- wp:button {"backgroundColor":"primary","textColor":"primary-dark","style":{"typography":{"fontWeight":"600"}}} -->
		<div class="wp-block-button"><a class="wp-block-button__link has-primary-dark-color has-primary-background-color has-text-color has-background wp-element-button" style="font-weight:600" href="<?php echo esc_url( home_url( '/produit/fenetre-pvc-sur-mesure/' ) ); ?>">Configurer ma fenêtre</a></div>
		<!-- /wp:button -->

		<!-- wp:button {"className":"is-style-outline","style":{"border":{"width":"1.5px","color":"#FFFFFF"},"typography":{"fontWeight":"600"},"color":{"text":"#FFFFFF"}}} -->
		<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-text-color wp-element-button" style="border-color:#FFFFFF;border-width:1.5px;color:#FFFFFF;font-weight:600">Parler à un expert</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</div>
<!-- /wp:group -->
