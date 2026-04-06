<?php
/**
 * WWB — Création automatique des pages essentielles
 *
 * Crée les pages légales, support et institutionnelles au premier
 * chargement admin. Se désactive via option wwb_pages_created.
 *
 * @package WWB_V2
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_init', 'wwb_create_essential_pages' );

function wwb_create_essential_pages() {
	if ( get_option( 'wwb_pages_created' ) ) {
		return;
	}

	$pages = wwb_get_pages_definitions();

	foreach ( $pages as $slug => $page ) {
		if ( get_page_by_path( $slug ) ) {
			continue;
		}

		wp_insert_post( [
			'post_title'   => $page['title'],
			'post_name'    => $slug,
			'post_content' => $page['content'],
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_author'  => 1,
		] );
	}

	update_option( 'wwb_pages_created', true );
}

function wwb_get_pages_definitions() {
	return [

		// ─── PAGES LÉGALES ───────────────────────────────────────

		'mentions-legales' => [
			'title'   => 'Mentions légales',
			'content' => '<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Éditeur du site</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Raison sociale :</strong> [Nom de la société]<br><strong>Forme juridique :</strong> [SAS / SARL / etc.]<br><strong>Capital social :</strong> [Montant] €<br><strong>Siège social :</strong> [Adresse complète]<br><strong>RCS :</strong> [Ville] [Numéro]<br><strong>SIRET :</strong> [Numéro SIRET]<br><strong>TVA intracommunautaire :</strong> [Numéro TVA]<br><strong>Directeur de la publication :</strong> [Nom du dirigeant]<br><strong>Email :</strong> contact@wiwilbild.fr<br><strong>Téléphone :</strong> 01 48 85 00 00</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Hébergeur</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Nom :</strong> [Nom hébergeur]<br><strong>Adresse :</strong> [Adresse hébergeur]<br><strong>Téléphone :</strong> [Téléphone hébergeur]</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Propriété intellectuelle</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>L\'ensemble du contenu de ce site (textes, images, vidéos, logos, icônes, sons, logiciels, etc.) est la propriété exclusive de [Nom de la société] ou de ses partenaires et est protégé par les lois françaises et internationales relatives à la propriété intellectuelle.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Toute reproduction, représentation, modification, publication, adaptation de tout ou partie des éléments du site, quel que soit le moyen ou le procédé utilisé, est interdite sauf autorisation écrite préalable de [Nom de la société].</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Crédits</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Conception et développement :</strong> Agence Binocle<br><strong>Photographies :</strong> [Crédits photos]</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Données personnelles</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Conformément au RGPD et à la loi Informatique et Libertés, vous disposez d\'un droit d\'accès, de rectification, de suppression et de portabilité de vos données personnelles. Pour exercer ces droits : contact@wiwilbild.fr</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Pour plus d\'informations, consultez notre <a href="/politique-de-confidentialite/">Politique de confidentialité</a>.</p>
<!-- /wp:paragraph -->',
		],

		'cgv' => [
			'title'   => 'Conditions Générales de Vente',
			'content' => '<!-- wp:paragraph {"style":{"typography":{"fontStyle":"italic"}}} -->
<p style="font-style:italic">Dernière mise à jour : [Date]</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Article 1 — Objet</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Les présentes Conditions Générales de Vente (CGV) régissent les relations contractuelles entre [Nom de la société], ci-après « le Vendeur », et toute personne effectuant un achat sur wiwilbild.fr, ci-après « le Client ». Toute commande implique l\'acceptation sans réserve des présentes CGV.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Article 2 — Produits</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Les produits proposés sont des menuiseries (fenêtres PVC, portes-fenêtres, volets roulants) et carrelages. Les photographies sont non contractuelles. Les couleurs peuvent varier selon les écrans.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Produits sur mesure :</strong> Les menuiseries configurées sont fabriquées selon les dimensions et options du Client. Le Client est responsable de l\'exactitude des mesures. Un conseiller vérifie systématiquement les mesures avant fabrication.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Article 3 — Prix</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Les prix sont en euros TTC. TVA applicable : 20% (taux normal) ou 5,5% (rénovation énergétique sous conditions). Les produits sont facturés au prix en vigueur lors de la validation de la commande.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Article 4 — Commande</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>La commande est validée après vérification du récapitulatif, renseignement des informations de livraison, acceptation des CGV et validation du paiement. Un email de confirmation est envoyé après validation.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Article 5 — Paiement</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Paiement en ligne par carte bancaire (Visa, Mastercard) via plateforme sécurisée. Paiement en 3 fois sans frais disponible à partir de [montant] €.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Article 6 — Livraison</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Livraison en France métropolitaine. Délais indicatifs :</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li><strong>Produits standards :</strong> [X] à [X] jours ouvrés</li>
<li><strong>Sur mesure :</strong> [X] à [X] semaines après validation des mesures</li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>Livraison offerte dès 500 €. En dessous, frais selon poids et destination.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Article 7 — Droit de rétractation</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Délai de 14 jours après réception (art. L221-18 du Code de la consommation). <strong>Exception :</strong> les produits sur mesure ne sont ni repris ni échangés (art. L221-28).</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Article 8 — Garanties</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Garantie légale de conformité (art. L217-4 à L217-14) et garantie contre les vices cachés (art. 1641 à 1649 du Code civil). Garantie fabricant de [X] ans sur les menuiseries PVC.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Article 9 — Réclamations et médiation</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Contact : contact@wiwilbild.fr ou 01 48 85 00 00. Médiateur : [Nom et coordonnées du médiateur].</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Article 10 — Droit applicable</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Les présentes CGV sont soumises au droit français.</p>
<!-- /wp:paragraph -->',
		],

		'politique-de-confidentialite' => [
			'title'   => 'Politique de confidentialité',
			'content' => '<!-- wp:paragraph {"style":{"typography":{"fontStyle":"italic"}}} -->
<p style="font-style:italic">Dernière mise à jour : [Date]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[Nom de la société] s\'engage à protéger la vie privée des utilisateurs de wiwilbild.fr.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">1. Responsable du traitement</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>[Nom de la société]</strong><br>[Adresse]<br>Email : contact@wiwilbild.fr — Tél : 01 48 85 00 00</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">2. Données collectées</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li><strong>Identification :</strong> nom, prénom, email, téléphone, adresse</li>
<li><strong>Commande :</strong> historique, configurations produits, adresses de livraison</li>
<li><strong>Espace Pro :</strong> raison sociale, SIRET, activité</li>
<li><strong>Navigation :</strong> adresse IP, cookies, pages visitées</li>
<li><strong>Paiement :</strong> traitées par notre prestataire sécurisé (nous ne stockons pas vos données bancaires)</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">3. Finalités</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li>Gestion des commandes et livraisons</li>
<li>Gestion du compte client et de l\'Espace Pro</li>
<li>Communication commerciale (avec consentement)</li>
<li>Amélioration des services</li>
<li>Obligations légales et comptables</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">4. Base légale</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li><strong>Exécution du contrat :</strong> commandes</li>
<li><strong>Consentement :</strong> newsletters, cookies non essentiels</li>
<li><strong>Intérêt légitime :</strong> amélioration du site, sécurité</li>
<li><strong>Obligation légale :</strong> factures</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">5. Durée de conservation</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li><strong>Clients actifs :</strong> durée de la relation + 3 ans</li>
<li><strong>Commandes :</strong> 10 ans (obligation comptable)</li>
<li><strong>Prospection :</strong> 3 ans après dernier contact</li>
<li><strong>Cookies :</strong> 13 mois max</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">6. Cookies</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li><strong>Essentiels :</strong> panier, session, connexion (pas de consentement requis)</li>
<li><strong>Analytiques :</strong> Google Analytics (avec consentement)</li>
<li><strong>Marketing :</strong> publicités personnalisées (avec consentement)</li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>Gérez vos préférences via le bandeau cookies ou les paramètres de votre navigateur.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">7. Vos droits</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Conformément au RGPD : droit d\'accès, rectification, suppression, portabilité, opposition, limitation. Contact : <strong>contact@wiwilbild.fr</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Réclamation possible auprès de la CNIL : <a href="https://www.cnil.fr">www.cnil.fr</a></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">8. Sécurité</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Chiffrement SSL/TLS, accès restreint, sauvegardes régulières.</p>
<!-- /wp:paragraph -->',
		],

		// ─── PAGES SUPPORT ──────────────────────────────────────

		'contact' => [
			'title'   => 'Contact',
			'content' => '<!-- wp:heading {"level":2,"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Une question ? Contactez-nous</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Notre équipe vous répond sous 24h ouvrées.</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns">

<!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%">

<!-- wp:shortcode -->
[contact-form-7 title="Contact WWB"]
<!-- /wp:shortcode -->

</div>
<!-- /wp:column -->

<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%">

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Nos coordonnées</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Téléphone :</strong> 01 48 85 00 00<br><strong>Email :</strong> contact@wiwilbild.fr<br><strong>Horaires :</strong> Lundi — Vendredi, 9h — 18h</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Adresse</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[Adresse complète]<br>[Code postal] [Ville]</p>
<!-- /wp:paragraph -->

</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->',
		],

		'faq' => [
			'title'   => 'Questions fréquentes',
			'content' => '<!-- wp:heading {"level":2,"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Foire aux questions</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Retrouvez les réponses aux questions les plus fréquentes.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Commande & paiement</h3>
<!-- /wp:heading -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Comment passer une commande ?</summary><!-- wp:paragraph -->
<p>Sélectionnez vos produits, configurez vos menuiseries si besoin, puis suivez les étapes du panier. Un conseiller vérifiera vos mesures avant de lancer la fabrication.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Quels modes de paiement acceptez-vous ?</summary><!-- wp:paragraph -->
<p>Cartes bancaires (Visa, Mastercard) et paiement en 3 fois sans frais à partir de [montant] €.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Mes données bancaires sont-elles sécurisées ?</summary><!-- wp:paragraph -->
<p>Oui, paiements traités via plateforme certifiée PCI-DSS. Nous ne stockons jamais vos données bancaires.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Produits & sur-mesure</h3>
<!-- /wp:heading -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Comment prendre mes mesures de fenêtre ?</summary><!-- wp:paragraph -->
<p>Mesurez largeur et hauteur de l\'ouverture dans le mur. Prenez 3 mesures pour chaque dimension et retenez la plus petite. Nos experts vérifient systématiquement vos mesures avant fabrication.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Puis-je modifier ma commande après validation ?</summary><!-- wp:paragraph -->
<p>Oui, tant que la fabrication n\'a pas démarré. Contactez-nous rapidement au 01 48 85 00 00.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Quelle garantie sur vos menuiseries ?</summary><!-- wp:paragraph -->
<p>Garantie fabricant [X] ans + garanties légales de conformité et vices cachés.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Livraison & retours</h3>
<!-- /wp:heading -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Quels sont les délais de livraison ?</summary><!-- wp:paragraph -->
<p>Standards : [X] à [X] jours ouvrés. Sur mesure : [X] à [X] semaines après validation des mesures.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>La livraison est-elle gratuite ?</summary><!-- wp:paragraph -->
<p>Oui dès 500 € d\'achat. En dessous, frais calculés selon poids et destination.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Puis-je retourner un produit ?</summary><!-- wp:paragraph -->
<p>14 jours pour les produits standards. Les produits sur mesure ne sont ni repris ni échangés (art. L221-28).</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Mon produit est arrivé endommagé ?</summary><!-- wp:paragraph -->
<p>Notez les réserves sur le bon de livraison et contactez-nous sous 48h avec photos.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Espace Pro</h3>
<!-- /wp:heading -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Comment accéder aux tarifs professionnels ?</summary><!-- wp:paragraph -->
<p>Inscrivez-vous via notre <a href="/inscription-pro/">formulaire Espace Pro</a>. Après validation de votre SIRET, accès aux tarifs dédiés sous 24h.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->',
		],

		'livraison' => [
			'title'   => 'Livraison',
			'content' => '<!-- wp:heading {"level":2,"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Livraison</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Livraison partout en France métropolitaine.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Frais de livraison</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li><strong>Offerte</strong> dès 500 € d\'achat</li>
<li>En dessous : frais selon poids et destination</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Délais</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li><strong>Standards :</strong> [X] à [X] jours ouvrés</li>
<li><strong>Sur mesure :</strong> [X] à [X] semaines après validation des mesures</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Suivi de commande</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Email de confirmation avec numéro de suivi dès expédition. Suivi depuis votre <a href="/mon-compte/">espace client</a>.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Réception</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Vérifiez l\'état des colis <strong>avant de signer le bon de livraison</strong>. En cas de colis endommagé, notez vos réserves et contactez-nous sous 48h.</p>
<!-- /wp:paragraph -->',
		],

		'retours' => [
			'title'   => 'Retours & échanges',
			'content' => '<!-- wp:heading {"level":2,"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Retours & échanges</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Droit de rétractation</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>14 jours</strong> après réception pour les produits standards. Produit dans son emballage d\'origine, en parfait état.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Exception :</strong> les produits sur mesure ne sont ni repris ni échangés (art. L221-28 du Code de la consommation).</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Procédure</h3>
<!-- /wp:heading -->

<!-- wp:list {"ordered":true} -->
<ol>
<li>Contactez-nous à contact@wiwilbild.fr ou au 01 48 85 00 00</li>
<li>Nous vous envoyons une étiquette de retour</li>
<li>Emballez soigneusement le produit</li>
<li>Déposez le colis au point relais indiqué</li>
</ol>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Remboursement</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Sous 14 jours après réception et vérification du produit retourné, via le même moyen de paiement.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Produit endommagé ou défectueux</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Contactez-nous sous 48h avec photos. Échange ou remboursement intégral.</p>
<!-- /wp:paragraph -->',
		],

		// ─── PAGES INSTITUTIONNELLES ────────────────────────────

		'notre-histoire' => [
			'title'   => 'Notre histoire',
			'content' => '<!-- wp:heading {"level":2,"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Notre histoire</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"18px"}}} -->
<p class="has-text-align-center" style="font-size:18px">Des matériaux premium, direct usine, au vrai prix.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[À compléter — Histoire de Wiwilbild, fondateurs, vision, valeurs, partenariats usines européennes]</p>
<!-- /wp:paragraph -->',
		],

		'nos-clients' => [
			'title'   => 'Nos clients',
			'content' => '<!-- wp:heading {"level":2,"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Ils nous font confiance</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Découvrez les projets réalisés par nos clients particuliers et professionnels.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[À compléter — Témoignages, réalisations, avis clients]</p>
<!-- /wp:paragraph -->',
		],
	];
}
