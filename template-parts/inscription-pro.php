<?php
/**
 * WWB V2 — Inscription Pro (Espace professionnel)
 * Figma node 26:2
 */
if ( ! defined( 'ABSPATH' ) ) exit;
$theme_uri = get_template_directory_uri();
?>

<!-- ─── Hero Banner ─── -->
<section class="wwb-pro-hero">
	<span class="wwb-pro-hero__badge">
		<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
		Espace réservé aux professionnels du bâtiment
	</span>
	<h1 class="wwb-pro-hero__title">
		Rejoignez <em>+400 professionnels</em> qui<br>font confiance à Wiwibild
	</h1>
	<div class="wwb-pro-hero__benefits">
		<div class="wwb-pro-hero__benefit">
			<div class="wwb-pro-hero__benefit-icon">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
			</div>
			<div>
				<strong>Tarifs Pro exclusifs</strong>
				<span>Jusqu'à 35% de remise négociée</span>
			</div>
		</div>
		<div class="wwb-pro-hero__benefit">
			<div class="wwb-pro-hero__benefit-icon">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
			</div>
			<div>
				<strong>Paiement 30 jours</strong>
				<span>Factures réglées sans frais</span>
			</div>
		</div>
		<div class="wwb-pro-hero__benefit">
			<div class="wwb-pro-hero__benefit-icon">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
			</div>
			<div>
				<strong>Devis sous 2h</strong>
				<span>Envoyez vos métrés, on calcule tout</span>
			</div>
		</div>
		<div class="wwb-pro-hero__benefit">
			<div class="wwb-pro-hero__benefit-icon">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
			</div>
			<div>
				<strong>Conseiller dédié</strong>
				<span>Un seul interlocuteur, joignable direct</span>
			</div>
		</div>
	</div>
</section>

<!-- ─── Main Content: Form + Sidebar ─── -->
<section class="wwb-pro-content">

	<!-- ─── Left: Form ─── -->
	<div class="wwb-pro-form-col">

		<!-- Step indicator -->
		<div class="wwb-pro-step">
			<span class="wwb-pro-step__label">Étape 1 / 2 — Informations entreprise</span>
			<div class="wwb-pro-step__bar">
				<div class="wwb-pro-step__progress" style="width: 50%"></div>
			</div>
		</div>

		<h2 class="wwb-pro-form__title">Créez votre compte Pro</h2>
		<p class="wwb-pro-form__subtitle">Remplissez le formulaire ci-dessous pour accéder à vos tarifs négociés, paiement 30 jours et à votre conseiller dédié. Activation sous 24h.</p>

		<form method="post" class="wwb-pro-form" id="wwb-pro-form">
			<?php wp_nonce_field( 'wwb_inscription_pro', 'wwb_pro_nonce' ); ?>

			<!-- Section: Informations entreprise -->
			<fieldset class="wwb-pro-form__section">
				<legend class="wwb-pro-form__legend">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
					Informations entreprise
				</legend>

				<div class="wwb-pro-form__field">
					<label for="pro_raison_sociale" class="wwb-pro-form__label">Raison sociale <span>*</span></label>
					<input type="text" id="pro_raison_sociale" name="raison_sociale" class="wwb-pro-form__input" placeholder="Ex : SARL Dubois Rénovation" required />
				</div>

				<div class="wwb-pro-form__field">
					<label for="pro_siret" class="wwb-pro-form__label">Numéro SIRET <span>*</span> — <small>14 chiffres</small></label>
					<input type="text" id="pro_siret" name="siret" class="wwb-pro-form__input" placeholder="Ex : 12345678901234" maxlength="14" pattern="\d{14}" required />
				</div>

				<div class="wwb-pro-form__field">
					<label for="pro_secteur" class="wwb-pro-form__label">Secteur d'activité <span>*</span></label>
					<select id="pro_secteur" name="secteur" class="wwb-pro-form__select" required>
						<option value="" disabled selected></option>
						<option value="renovation">Rénovation</option>
						<option value="construction">Construction neuve</option>
						<option value="architecture">Architecture / Maîtrise d'oeuvre</option>
						<option value="amenagement">Aménagement intérieur</option>
						<option value="plomberie">Plomberie / Chauffage</option>
						<option value="couverture">Couverture / Charpente</option>
						<option value="carrelage">Carrelage / Revêtement</option>
						<option value="menuiserie">Menuiserie</option>
						<option value="autre">Autre</option>
					</select>
				</div>

				<div class="wwb-pro-form__field">
					<label for="pro_adresse" class="wwb-pro-form__label">Adresse complète <span>*</span></label>
					<input type="text" id="pro_adresse" name="adresse" class="wwb-pro-form__input" placeholder="Ex : 14 rue des Artisans" required />
				</div>

				<div class="wwb-pro-form__row">
					<div class="wwb-pro-form__field">
						<label for="pro_cp" class="wwb-pro-form__label">Code postal <span>*</span></label>
						<input type="text" id="pro_cp" name="code_postal" class="wwb-pro-form__input" placeholder="75001" maxlength="5" pattern="\d{5}" required />
					</div>
					<div class="wwb-pro-form__field">
						<label for="pro_ville" class="wwb-pro-form__label">Ville <span>*</span></label>
						<input type="text" id="pro_ville" name="ville" class="wwb-pro-form__input" placeholder="Ex : Paris" required />
					</div>
				</div>
			</fieldset>

			<!-- Section: Contact principal -->
			<fieldset class="wwb-pro-form__section">
				<legend class="wwb-pro-form__legend">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
					Contact principal
				</legend>

				<div class="wwb-pro-form__row">
					<div class="wwb-pro-form__field">
						<label for="pro_prenom" class="wwb-pro-form__label">Prénom <span>*</span></label>
						<input type="text" id="pro_prenom" name="prenom" class="wwb-pro-form__input" placeholder="Ex : Marc" required />
					</div>
					<div class="wwb-pro-form__field">
						<label for="pro_nom" class="wwb-pro-form__label">Nom <span>*</span></label>
						<input type="text" id="pro_nom" name="nom" class="wwb-pro-form__input" placeholder="Ex : Dubois" required />
					</div>
				</div>

				<div class="wwb-pro-form__field">
					<label for="pro_email" class="wwb-pro-form__label">Email professionnel <span>*</span></label>
					<input type="email" id="pro_email" name="email" class="wwb-pro-form__input" placeholder="marc@entreprise.fr" required />
				</div>

				<div class="wwb-pro-form__field">
					<label for="pro_telephone" class="wwb-pro-form__label">Téléphone <span>*</span></label>
					<input type="tel" id="pro_telephone" name="telephone" class="wwb-pro-form__input" placeholder="06 12 34 56 78" required />
				</div>

				<div class="wwb-pro-form__field">
					<label for="pro_fonction" class="wwb-pro-form__label">Fonction</label>
					<input type="text" id="pro_fonction" name="fonction" class="wwb-pro-form__input" />
				</div>
			</fieldset>

			<!-- Section: Vos besoins -->
			<fieldset class="wwb-pro-form__section">
				<legend class="wwb-pro-form__legend">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
					Vos besoins
				</legend>

				<div class="wwb-pro-form__field">
					<label for="pro_volume" class="wwb-pro-form__label">Volume mensuel estimé <span>*</span></label>
					<select id="pro_volume" name="volume" class="wwb-pro-form__select" required>
						<option value="" disabled selected></option>
						<option value="moins-1000">Moins de 1 000 €</option>
						<option value="1000-5000">1 000 € – 5 000 €</option>
						<option value="5000-15000">5 000 € – 15 000 €</option>
						<option value="15000-50000">15 000 € – 50 000 €</option>
						<option value="plus-50000">Plus de 50 000 €</option>
					</select>
				</div>

				<div class="wwb-pro-form__field">
					<label class="wwb-pro-form__label">Catégories intéressées <span>*</span></label>
					<div class="wwb-pro-form__checkboxes">
						<label class="wwb-pro-form__checkbox">
							<input type="checkbox" name="categories[]" value="menuiseries" />
							<span>Menuiseries (fenêtres, portes, volets)</span>
						</label>
						<label class="wwb-pro-form__checkbox">
							<input type="checkbox" name="categories[]" value="carrelages" />
							<span>Carrelages & revêtements de sol</span>
						</label>
						<label class="wwb-pro-form__checkbox">
							<input type="checkbox" name="categories[]" value="couvertures" />
							<span>Couvertures & matériaux de toiture</span>
						</label>
					</div>
				</div>

				<div class="wwb-pro-form__field">
					<label for="pro_source" class="wwb-pro-form__label">Comment nous avez-vous connu ?</label>
					<input type="text" id="pro_source" name="source" class="wwb-pro-form__input" />
				</div>
			</fieldset>

			<!-- Submit -->
			<button type="submit" name="wwb_pro_submit" class="wwb-pro-form__submit">
				Demander mes tarifs Pro
				<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
			</button>

			<p class="wwb-pro-form__footnote">
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
				Activation sous 24h
				<span class="wwb-pro-form__footnote-sep">·</span>
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
				Sans engagement
			</p>

		</form>
	</div>

	<!-- ─── Right: Sidebar ─── -->
	<aside class="wwb-pro-sidebar">

		<div class="wwb-pro-sidebar__card">
			<span class="wwb-pro-sidebar__eyebrow">Pourquoi nous rejoindre</span>
			<h3 class="wwb-pro-sidebar__title">Pourquoi rejoindre Wiwibild Pro ?</h3>

			<div class="wwb-pro-sidebar__advantages">
				<div class="wwb-pro-sidebar__advantage">
					<div class="wwb-pro-sidebar__advantage-icon">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
					</div>
					<div>
						<strong>Tarifs Pro négociés jusqu'à −35%</strong>
						<p>Grille tarifaire dédiée, remises volume progressives sur toutes les références</p>
					</div>
				</div>
				<div class="wwb-pro-sidebar__advantage">
					<div class="wwb-pro-sidebar__advantage-icon">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
					</div>
					<div>
						<strong>Paiement à 30 jours sans frais</strong>
						<p>Gérez votre trésorerie : commandez aujourd'hui, payez après livraison</p>
					</div>
				</div>
				<div class="wwb-pro-sidebar__advantage">
					<div class="wwb-pro-sidebar__advantage-icon">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
					</div>
					<div>
						<strong>Devis chiffré en moins de 2h</strong>
						<p>Envoyez vos métrés, on calcule quantités, disponibilité et délais de livraison</p>
					</div>
				</div>
				<div class="wwb-pro-sidebar__advantage">
					<div class="wwb-pro-sidebar__advantage-icon">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
					</div>
					<div>
						<strong>Conseiller dédié, joignable directement</strong>
						<p>Un seul interlocuteur pour tous vos chantiers — par téléphone, email ou WhatsApp</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Stats -->
		<div class="wwb-pro-sidebar__stats">
			<div class="wwb-pro-sidebar__stat">
				<span class="wwb-pro-sidebar__stat-value">400<span>+</span></span>
				<span class="wwb-pro-sidebar__stat-label">Pros actifs</span>
			</div>
			<div class="wwb-pro-sidebar__stat">
				<span class="wwb-pro-sidebar__stat-value">−32<span>%</span></span>
				<span class="wwb-pro-sidebar__stat-label">Économie moyenne</span>
			</div>
			<div class="wwb-pro-sidebar__stat">
				<span class="wwb-pro-sidebar__stat-value">24<span>h</span></span>
				<span class="wwb-pro-sidebar__stat-label">Activation compte maximum</span>
			</div>
		</div>

		<!-- Testimonial -->
		<div class="wwb-pro-sidebar__testimonial">
			<div class="wwb-pro-sidebar__testimonial-stars">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="#FF99DA" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="#FF99DA" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="#FF99DA" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="#FF99DA" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="#FF99DA" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
			</div>
			<blockquote class="wwb-pro-sidebar__testimonial-quote">
				"J'ai économisé <strong>18% sur mes fenêtres</strong> cette année. Devis en 2h, livraison calée direct sur le chantier — et mon conseiller répond le samedi matin. Franchement, je reviens à chaque commande."
			</blockquote>
			<div class="wwb-pro-sidebar__testimonial-tag">
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
				−18% sur les fenêtres en 2025
			</div>
			<div class="wwb-pro-sidebar__testimonial-author">
				<img src="<?php echo esc_url( $theme_uri . '/assets/images/testimonial-marc.jpg' ); ?>" alt="Marc D." width="44" height="44" />
				<div>
					<strong>Marc D.</strong>
					<span>Artisan menuisier — Île-de-France<br>Client depuis 2023</span>
				</div>
			</div>
		</div>

		<!-- Security badges -->
		<div class="wwb-pro-sidebar__badges">
			<span class="wwb-pro-sidebar__badge">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
				Données sécurisées
			</span>
			<span class="wwb-pro-sidebar__badge">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
				Conforme RGPD
			</span>
			<span class="wwb-pro-sidebar__badge">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><polyline points="17 11 19 13 23 9"/></svg>
				Validation manuelle par nos équipes
			</span>
		</div>

	</aside>

</section>
