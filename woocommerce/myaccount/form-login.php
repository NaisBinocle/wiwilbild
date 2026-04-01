<?php
/**
 * WWB V2 — Login / Register Form
 *
 * Split layout: image left + form right (Figma Connexion — wwb!)
 *
 * @see https://woocommerce.github.io/code-reference/files/woocommerce-templates-myaccount-form-login.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

do_action( 'woocommerce_before_customer_login_form' );

$registration_enabled = get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes';
?>

<div class="wwb-auth">

	<!-- ─── Left: Image Panel ─── -->
	<div class="wwb-auth__visual">
		<div class="wwb-auth__overlay">
			<div class="wwb-auth__visual-content">
				<span class="wwb-auth__badge-quality">
					<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
					Matériaux de haute qualité
				</span>
				<h2 class="wwb-auth__visual-title">
					Bâtissez l'espace qui vous <em>ressemble.</em>
				</h2>
				<p class="wwb-auth__visual-desc">
					Plus de 14 000 références sélectionnées pour les professionnels et les particuliers exigeants. Livraison rapide, conseils d'experts.
				</p>
				<div class="wwb-auth__stats">
					<div class="wwb-auth__stat">
						<span class="wwb-auth__stat-value">14<span class="wwb-auth__stat-accent">k+</span></span>
						<span class="wwb-auth__stat-label">Références en stock</span>
					</div>
					<div class="wwb-auth__stat">
						<span class="wwb-auth__stat-value">4.8<span class="wwb-auth__stat-accent">/5</span></span>
						<span class="wwb-auth__stat-label">Satisfaction client</span>
					</div>
					<div class="wwb-auth__stat">
						<span class="wwb-auth__stat-value">48<span class="wwb-auth__stat-accent">h</span></span>
						<span class="wwb-auth__stat-label">Livraison express</span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- ─── Right: Form Panel ─── -->
	<div class="wwb-auth__form-panel">
		<div class="wwb-auth__form-wrapper">

			<!-- Logo + Heading -->
			<div class="wwb-auth__header">
				<p class="wwb-auth__logo">wwb<span>!</span></p>
				<h1 class="wwb-auth__title" id="wwb-auth-title">Bon retour parmi nous</h1>
				<p class="wwb-auth__subtitle">Connectez-vous à votre compte ou créez-en un nouveau.</p>
			</div>

			<!-- Tabs -->
			<?php if ( $registration_enabled ) : ?>
			<div class="wwb-auth__tabs" role="tablist">
				<button class="wwb-auth__tab wwb-auth__tab--active" role="tab" aria-selected="true" aria-controls="wwb-login-form" data-tab="login" type="button">
					Se connecter
				</button>
				<button class="wwb-auth__tab" role="tab" aria-selected="false" aria-controls="wwb-register-form" data-tab="register" type="button">
					Créer un compte
				</button>
			</div>
			<?php endif; ?>

			<?php wc_print_notices(); ?>

			<!-- ─── Login Form ─── -->
			<div class="wwb-auth__pane wwb-auth__pane--active" id="wwb-login-form" role="tabpanel">
				<form method="post" class="wwb-auth__form">

					<?php do_action( 'woocommerce_login_form_start' ); ?>

					<div class="wwb-auth__field">
						<label for="username" class="wwb-auth__label">Adresse e-mail <span>*</span></label>
						<input type="text" name="username" id="username" autocomplete="username"
							   placeholder="vous@exemple.fr"
							   value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"
							   class="wwb-auth__input" required />
					</div>

					<div class="wwb-auth__field">
						<label for="password" class="wwb-auth__label">Mot de passe <span>*</span></label>
						<div class="wwb-auth__input-group">
							<input type="password" name="password" id="password" autocomplete="current-password"
								   placeholder="Votre mot de passe"
								   class="wwb-auth__input" required />
							<button type="button" class="wwb-auth__toggle-pw" aria-label="Afficher le mot de passe" data-toggle-pw>
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
							</button>
						</div>
						<div class="wwb-auth__field-footer">
							<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="wwb-auth__link-muted">Mot de passe oublié ?</a>
						</div>
					</div>

					<?php do_action( 'woocommerce_login_form' ); ?>

					<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>

					<button type="submit" name="login" value="1" class="wwb-auth__btn-primary">
						<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
						Se connecter
					</button>

					<?php do_action( 'woocommerce_login_form_end' ); ?>

				</form>

				<!-- Separator -->
				<div class="wwb-auth__separator">
					<span>ou</span>
				</div>

				<!-- Social login placeholder -->
				<button type="button" class="wwb-auth__btn-social" disabled>
					<svg width="18" height="18" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
					Continuer avec Google
				</button>

				<!-- Switch to register -->
				<?php if ( $registration_enabled ) : ?>
				<p class="wwb-auth__switch">
					Pas encore de compte ? <a href="#" class="wwb-auth__link-strong" data-switch-tab="register">Créer un compte</a>
				</p>
				<?php endif; ?>

			</div>

			<!-- ─── Register Form ─── -->
			<?php if ( $registration_enabled ) : ?>
			<div class="wwb-auth__pane" id="wwb-register-form" role="tabpanel" hidden>
				<form method="post" class="wwb-auth__form" <?php do_action( 'woocommerce_register_form_tag' ); ?>>

					<?php do_action( 'woocommerce_register_form_start' ); ?>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
					<div class="wwb-auth__field">
						<label for="reg_username" class="wwb-auth__label">Nom d'utilisateur <span>*</span></label>
						<input type="text" name="username" id="reg_username" autocomplete="username"
							   placeholder="Votre identifiant"
							   value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"
							   class="wwb-auth__input" required />
					</div>
					<?php endif; ?>

					<div class="wwb-auth__field">
						<label for="reg_email" class="wwb-auth__label">Adresse e-mail <span>*</span></label>
						<input type="email" name="email" id="reg_email" autocomplete="email"
							   placeholder="vous@exemple.fr"
							   value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>"
							   class="wwb-auth__input" required />
					</div>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
					<div class="wwb-auth__field">
						<label for="reg_password" class="wwb-auth__label">Mot de passe <span>*</span></label>
						<div class="wwb-auth__input-group">
							<input type="password" name="password" id="reg_password" autocomplete="new-password"
								   placeholder="Choisissez un mot de passe"
								   class="wwb-auth__input" required />
							<button type="button" class="wwb-auth__toggle-pw" aria-label="Afficher le mot de passe" data-toggle-pw>
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
							</button>
						</div>
					</div>
					<?php endif; ?>

					<?php do_action( 'woocommerce_register_form' ); ?>

					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>

					<button type="submit" name="register" value="1" class="wwb-auth__btn-primary">
						<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
						Créer mon compte
					</button>

					<?php do_action( 'woocommerce_register_form_end' ); ?>

				</form>

				<!-- Separator -->
				<div class="wwb-auth__separator">
					<span>ou</span>
				</div>

				<!-- Social login placeholder -->
				<button type="button" class="wwb-auth__btn-social" disabled>
					<svg width="18" height="18" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
					Continuer avec Google
				</button>

				<!-- Switch to login -->
				<p class="wwb-auth__switch">
					Déjà un compte ? <a href="#" class="wwb-auth__link-strong" data-switch-tab="login">Se connecter</a>
				</p>

			</div>
			<?php endif; ?>

			<!-- Security badges -->
			<div class="wwb-auth__security">
				<span class="wwb-auth__security-item">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
					Connexion sécurisée SSL
				</span>
				<span class="wwb-auth__security-item">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
					Données protégées RGPD
				</span>
			</div>

		</div>
	</div>

</div>

<script>
(function() {
	const tabs = document.querySelectorAll('.wwb-auth__tab');
	const panes = document.querySelectorAll('.wwb-auth__pane');
	const title = document.getElementById('wwb-auth-title');
	const switchLinks = document.querySelectorAll('[data-switch-tab]');

	function switchTo(target) {
		tabs.forEach(function(t) {
			const isActive = t.dataset.tab === target;
			t.classList.toggle('wwb-auth__tab--active', isActive);
			t.setAttribute('aria-selected', isActive);
		});
		panes.forEach(function(p) {
			const show = p.id === 'wwb-' + target + '-form';
			p.classList.toggle('wwb-auth__pane--active', show);
			p.hidden = !show;
		});
		if (title) {
			title.textContent = target === 'login' ? 'Bon retour parmi nous' : 'Créez votre compte';
		}
	}

	tabs.forEach(function(tab) {
		tab.addEventListener('click', function() { switchTo(this.dataset.tab); });
	});

	switchLinks.forEach(function(link) {
		link.addEventListener('click', function(e) { e.preventDefault(); switchTo(this.dataset.switchTab); });
	});

	// Password toggle
	document.querySelectorAll('[data-toggle-pw]').forEach(function(btn) {
		btn.addEventListener('click', function() {
			var input = this.closest('.wwb-auth__input-group').querySelector('input');
			input.type = input.type === 'password' ? 'text' : 'password';
		});
	});
})();
</script>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
