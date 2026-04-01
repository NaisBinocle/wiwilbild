<?php
/**
 * WWB V2 — Checkout Form (Figma node 22:2)
 *
 * @see https://woocommerce.github.io/code-reference/files/woocommerce-templates-checkout-form-checkout.html
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}
?>

<div class="wwb-checkout">

	<!-- ─── Checkout Header ─── -->
	<div class="wwb-checkout__topbar">
		<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="wwb-checkout__back">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
			Retour au panier
		</a>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="wwb-checkout__logo">wwb<span>!</span></a>
		<span class="wwb-checkout__secure">
			<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
			Paiement sécurisé
		</span>
	</div>

	<!-- ─── Steps ─── -->
	<div class="wwb-checkout__steps">
		<div class="wwb-checkout__step wwb-checkout__step--active">
			<span class="wwb-checkout__step-num">1</span>
			<span>Livraison</span>
		</div>
		<div class="wwb-checkout__step-line wwb-checkout__step-line--active"></div>
		<div class="wwb-checkout__step">
			<span class="wwb-checkout__step-num">2</span>
			<span>Paiement</span>
		</div>
		<div class="wwb-checkout__step-line"></div>
		<div class="wwb-checkout__step">
			<span class="wwb-checkout__step-num">3</span>
			<span>Confirmation</span>
		</div>
	</div>

	<form name="checkout" method="post" class="checkout woocommerce-checkout wwb-checkout__form" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

		<div class="wwb-checkout__layout">

			<!-- ─── Left: Form Fields ─── -->
			<div class="wwb-checkout__fields-col">

				<!-- Billing / Shipping -->
				<div class="wwb-checkout__section">
					<h2 class="wwb-checkout__section-title">Adresse de livraison</h2>

					<?php if ( $checkout->get_checkout_fields( 'billing' ) ) : ?>
						<?php do_action( 'woocommerce_checkout_billing' ); ?>
					<?php endif; ?>

					<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
						<div class="wwb-checkout__shipping-note">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
							Pour le livreur en cas de besoin
						</div>
					<?php endif; ?>

					<?php if ( $checkout->get_checkout_fields( 'shipping' ) ) : ?>
						<?php do_action( 'woocommerce_checkout_shipping' ); ?>
					<?php endif; ?>
				</div>

				<!-- Shipping Method -->
				<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
				<div class="wwb-checkout__section">
					<h2 class="wwb-checkout__section-title">Mode de livraison</h2>
					<div class="wwb-checkout__shipping-methods">
						<label class="wwb-checkout__shipping-option wwb-checkout__shipping-option--selected">
							<input type="radio" name="wwb_shipping" value="standard" checked />
							<div class="wwb-checkout__shipping-option-content">
								<div>
									<strong>Livraison standard</strong>
									<span>5-7 jours ouvrés</span>
								</div>
								<span class="wwb-checkout__shipping-price wwb-checkout__shipping-price--free">Gratuit</span>
							</div>
						</label>
						<label class="wwb-checkout__shipping-option">
							<input type="radio" name="wwb_shipping" value="express" />
							<div class="wwb-checkout__shipping-option-content">
								<div>
									<strong>Livraison express</strong>
									<span>2-3 jours ouvrées</span>
								</div>
								<span class="wwb-checkout__shipping-price">49 €</span>
							</div>
						</label>
						<label class="wwb-checkout__shipping-option">
							<input type="radio" name="wwb_shipping" value="chantier" />
							<div class="wwb-checkout__shipping-option-content">
								<div>
									<strong>Livraison sur chantier</strong>
									<span>Avec grue / chariot — nous contacter</span>
								</div>
								<span class="wwb-checkout__shipping-price">Sur devis</span>
							</div>
						</label>
					</div>
				</div>
				<?php endif; ?>

				<!-- Payment -->
				<div class="wwb-checkout__section">
					<h2 class="wwb-checkout__section-title">Paiement</h2>

					<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

					<div id="payment" class="woocommerce-checkout-payment wwb-checkout__payment">
						<?php if ( WC()->cart->needs_payment() ) : ?>
							<ul class="wc_payment_methods payment_methods methods wwb-checkout__payment-methods">
								<?php
								if ( ! empty( $available_gateways ) ) {
									foreach ( $available_gateways as $gateway ) {
										wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
									}
								} else {
									echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>';
								}
								?>
							</ul>
						<?php endif; ?>

						<div class="form-row place-order wwb-checkout__place-order">
							<noscript>
								<?php esc_html_e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the &lt;em&gt;Update Totals&lt;/em&gt; button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ); ?>
								<br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
							</noscript>

							<?php wc_get_template( 'checkout/terms.php' ); ?>

							<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

							<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt wwb-checkout__submit" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr__( 'Place order', 'woocommerce' ) . '" data-value="' . esc_attr__( 'Place order', 'woocommerce' ) . '"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg> Confirmer et payer — ' . WC()->cart->get_total() . '</button>' ); ?>

							<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

							<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
						</div>

						<p class="wwb-checkout__legal">
							En confirmant, j'accepte les <a href="<?php echo esc_url( get_permalink( wc_terms_and_conditions_page_id() ) ); ?>">CGV</a>
							<span class="wwb-checkout__legal-sep">·</span>
							<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
							Paiement 100% sécurisé
						</p>
					</div>
				</div>

				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

			</div>

			<!-- ─── Right: Order Review ─── -->
			<div class="wwb-checkout__summary-col">
				<div class="wwb-checkout__summary">

					<h2 class="wwb-checkout__summary-heading">
						Votre commande <span>(<?php echo WC()->cart->get_cart_contents_count(); ?> article<?php echo WC()->cart->get_cart_contents_count() > 1 ? 's' : ''; ?>)</span>
					</h2>

					<!-- Cart items -->
					<div class="wwb-checkout__summary-items">
						<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
							$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
							if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 ) continue;
							$thumbnail = $_product->get_image( array( 48, 48 ) );
							$name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
							$subtotal  = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
							$qty       = $cart_item['quantity'];
						?>
						<div class="wwb-checkout__summary-item">
							<div class="wwb-checkout__summary-item-img"><?php echo $thumbnail; ?></div>
							<div class="wwb-checkout__summary-item-info">
								<strong><?php echo wp_kses_post( $name ); ?></strong>
								<span>Qté : <?php echo $qty; ?></span>
							</div>
							<span class="wwb-checkout__summary-item-price"><?php echo $subtotal; ?></span>
						</div>
						<?php endforeach; ?>
					</div>

					<!-- Totals -->
					<div class="wwb-checkout__summary-totals">
						<div class="wwb-checkout__summary-row">
							<span>Sous-total</span>
							<span><?php echo WC()->cart->get_cart_subtotal(); ?></span>
						</div>
						<div class="wwb-checkout__summary-row">
							<span>Livraison</span>
							<span class="wwb-checkout__summary-free">Offerte</span>
						</div>
						<div class="wwb-checkout__summary-row wwb-checkout__summary-row--total">
							<span>Total TTC</span>
							<span class="wwb-checkout__summary-total-price"><?php echo WC()->cart->get_total(); ?></span>
						</div>
						<div class="wwb-checkout__summary-row wwb-checkout__summary-row--small">
							<span>TVA incluse (20%)</span>
							<span><?php echo wc_price( WC()->cart->get_total_tax() ); ?></span>
						</div>
					</div>

					<!-- Coupon -->
					<div class="wwb-checkout__summary-coupon">
						<button type="button" class="wwb-checkout__coupon-toggle" onclick="this.nextElementSibling.classList.toggle('wwb-checkout__coupon-form--open')">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
							Appliquer un code promo
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
						</button>
						<div class="wwb-checkout__coupon-form">
							<input type="text" name="coupon_code" class="wwb-checkout__coupon-input" placeholder="Code promo" />
							<button type="submit" name="apply_coupon" class="wwb-checkout__coupon-apply">OK</button>
						</div>
					</div>

					<!-- Trust badges -->
					<div class="wwb-checkout__summary-trust">
						<div class="wwb-checkout__trust-item">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
							Garantie casse — remplacement sous 72h
						</div>
						<div class="wwb-checkout__trust-item">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
							Retour 30 jours — sans question
						</div>
						<div class="wwb-checkout__trust-item">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
							Support expert — 9h-18h du lundi au vendredi
						</div>
					</div>

				</div>
			</div>

		</div>

	</form>

</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
