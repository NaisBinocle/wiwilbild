<?php
/**
 * WWB V2 — Cart page (Figma node 27:2)
 *
 * @see https://woocommerce.github.io/code-reference/files/woocommerce-templates-cart-cart.html
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );
$cart = WC()->cart;
?>

<div class="wwb-cart">

	<!-- Breadcrumb -->
	<nav class="wwb-cart__breadcrumb">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Accueil</a>
		<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
		<span>Panier</span>
	</nav>

	<!-- Title -->
	<div class="wwb-cart__header">
		<h1 class="wwb-cart__title">Votre panier <span class="wwb-cart__count"><?php echo $cart->get_cart_contents_count(); ?> article<?php echo $cart->get_cart_contents_count() > 1 ? 's' : ''; ?></span></h1>
	</div>

	<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

		<div class="wwb-cart__layout">

			<!-- ─── Left: Cart Items ─── -->
			<div class="wwb-cart__items-col">

				<div class="wwb-cart__items-header">
					<span class="wwb-cart__items-label">Articles sélectionnés</span>
					<a href="#" class="wwb-cart__clear-link" onclick="if(confirm('Vider le panier ?')){document.querySelectorAll('.wwb-cart-item__remove').forEach(function(a){a.click()});}return false;">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
						Vider le panier
					</a>
				</div>

				<?php do_action( 'woocommerce_before_cart_contents' ); ?>

				<?php foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) :
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 || ! apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						continue;
					}

					$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
					$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_thumbnail' ), $cart_item, $cart_item_key );
					$product_price     = apply_filters( 'woocommerce_cart_item_price', $cart->get_product_price( $_product ), $cart_item, $cart_item_key );
					$product_subtotal  = apply_filters( 'woocommerce_cart_item_subtotal', $cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

					// Get variation attributes
					$item_data = '';
					if ( $_product->is_type( 'variation' ) ) {
						$attributes = $_product->get_variation_attributes();
						$parts = array();
						foreach ( $attributes as $attr_name => $attr_value ) {
							$taxonomy = str_replace( 'attribute_', '', $attr_name );
							$term = get_term_by( 'slug', $attr_value, $taxonomy );
							$label = wc_attribute_label( $taxonomy );
							$value = $term ? $term->name : $attr_value;
							$parts[] = $label . ' : ' . $value;
						}
						$item_data = implode( ' · ', $parts );
					}

					// Récap configurateur sur-mesure (pour fenêtres configurables)
					$is_configurator = ! empty( $cart_item['wwb_configurator'] );
					$config_meta = $is_configurator
						? apply_filters( 'woocommerce_get_item_data', array(), $cart_item )
						: array();

					// Get product categories
					$cats = get_the_terms( $product_id, 'product_cat' );
					$cat_name = $cats && ! is_wp_error( $cats ) ? $cats[0]->name : '';
				?>

				<div class="wwb-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

					<!-- Image -->
					<div class="wwb-cart-item__image">
						<?php if ( $cat_name ) : ?>
							<span class="wwb-cart-item__badge"><?php echo esc_html( $cat_name ); ?></span>
						<?php endif; ?>
						<?php if ( $product_permalink ) : ?>
							<a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo $thumbnail; ?></a>
						<?php else : ?>
							<?php echo $thumbnail; ?>
						<?php endif; ?>
					</div>

					<!-- Details -->
					<div class="wwb-cart-item__details">
						<div class="wwb-cart-item__info">
							<h3 class="wwb-cart-item__name">
								<?php if ( $product_permalink ) : ?>
									<a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo wp_kses_post( $product_name ); ?></a>
								<?php else : ?>
									<?php echo wp_kses_post( $product_name ); ?>
								<?php endif; ?>
							</h3>
							<?php if ( $item_data ) : ?>
								<p class="wwb-cart-item__meta"><?php echo esc_html( $item_data ); ?></p>
							<?php endif; ?>

							<?php if ( $is_configurator && ! empty( $config_meta ) ) :
								// Ordre d'affichage (flat) — on fusionne dimensions en une seule ligne
								$meta_by_name = array();
								foreach ( $config_meta as $m ) {
									if ( ! empty( $m['name'] ) && $m['value'] !== '' ) $meta_by_name[ $m['name'] ] = $m['value'];
								}

								// Ligne "Dimensions" combinée
								$dim_parts = array();
								if ( isset( $meta_by_name['Largeur'] ) ) $dim_parts[] = rtrim( $meta_by_name['Largeur'], ' m' );
								if ( isset( $meta_by_name['Hauteur'] ) ) $dim_parts[] = rtrim( $meta_by_name['Hauteur'], ' m' );
								$dim_combined = ! empty( $dim_parts ) ? implode( ' × ', $dim_parts ) . ' mm' : '';
								unset( $meta_by_name['Largeur'], $meta_by_name['Hauteur'] );

								$ordered = array();
								if ( isset( $meta_by_name['Type'] ) )            $ordered['Type']            = $meta_by_name['Type'];
								if ( $dim_combined )                             $ordered['Dimensions']      = $dim_combined;
								if ( isset( $meta_by_name['Ouverture'] ) )       $ordered['Ouverture']       = $meta_by_name['Ouverture'];
								if ( isset( $meta_by_name['Vitrage'] ) )         $ordered['Vitrage']         = $meta_by_name['Vitrage'];
								if ( isset( $meta_by_name['Coloris'] ) )         $ordered['Coloris']         = $meta_by_name['Coloris'];
								if ( isset( $meta_by_name['Dormant'] ) )         $ordered['Dormant']         = $meta_by_name['Dormant'];
								if ( isset( $meta_by_name['Ferrage'] ) )         $ordered['Ferrage']         = $meta_by_name['Ferrage'];
								if ( isset( $meta_by_name['Grille aération'] ) ) $ordered['Grille']          = $meta_by_name['Grille aération'];
								if ( isset( $meta_by_name['Volet roulant'] ) )   $ordered['Volet roulant']   = $meta_by_name['Volet roulant'];
							?>
								<div class="wwb-cart-item__config">
									<div class="wwb-cart-item__config-label">
										<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
										Votre configuration sur mesure
									</div>
									<ul class="wwb-cart-item__config-list">
										<?php foreach ( $ordered as $key => $val ) : ?>
											<li class="wwb-cart-item__config-row">
												<span class="wwb-cart-item__config-key"><?php echo esc_html( $key ); ?></span>
												<span class="wwb-cart-item__config-val"><?php echo esc_html( $val ); ?></span>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>

							<div class="wwb-cart-item__actions">
								<?php
								echo apply_filters(
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="wwb-cart-item__remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg> Supprimer</a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										esc_attr__( 'Remove this item', 'woocommerce' ),
										esc_attr( $product_id ),
										esc_attr( $_product->get_sku() )
									),
									$cart_item_key
								);
								?>
							</div>

							<div class="wwb-cart-item__delivery">
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
								Livraison estimée : 5-7 jours ouvrés
							</div>
						</div>

						<!-- Price + Qty -->
						<div class="wwb-cart-item__pricing">
							<span class="wwb-cart-item__unit-price"><?php echo $product_price; ?></span>
							<div class="wwb-cart-item__quantity">
								<?php
								if ( $_product->is_sold_individually() ) {
									$min_quantity = 1;
									$max_quantity = 1;
								} else {
									$min_quantity = 0;
									$max_quantity = $_product->get_max_purchase_quantity();
								}

								$product_quantity = woocommerce_quantity_input(
									array(
										'input_name'   => "cart[{$cart_item_key}][qty]",
										'input_value'  => $cart_item['quantity'],
										'max_value'    => $max_quantity,
										'min_value'    => $min_quantity,
										'product_name' => $product_name,
										'classes'      => array( 'wwb-cart-item__qty-input' ),
									),
									$_product,
									false
								);
								echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
								?>
							</div>
							<span class="wwb-cart-item__subtotal"><?php echo $product_subtotal; ?></span>
						</div>
					</div>

				</div>

				<?php endforeach; ?>

				<?php do_action( 'woocommerce_cart_contents' ); ?>

				<!-- Update cart (hidden, triggered by qty change) -->
				<button type="submit" class="wwb-cart__update-btn" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>">
					<?php esc_html_e( 'Update cart', 'woocommerce' ); ?>
				</button>

				<?php do_action( 'woocommerce_after_cart_contents' ); ?>
				<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>

				<!-- Continue shopping -->
				<a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="wwb-cart__continue-link">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
					Continuer mes achats
				</a>

			</div>

			<!-- ─── Right: Order Summary ─── -->
			<div class="wwb-cart__summary-col">
				<div class="wwb-cart__summary">

					<h2 class="wwb-cart__summary-title">Récapitulatif de commande</h2>

					<div class="wwb-cart__summary-rows">
						<div class="wwb-cart__summary-row">
							<span>Sous-total (<?php echo $cart->get_cart_contents_count(); ?> article<?php echo $cart->get_cart_contents_count() > 1 ? 's' : ''; ?>)</span>
							<span><?php echo $cart->get_cart_subtotal(); ?></span>
						</div>
						<div class="wwb-cart__summary-row">
							<span>Livraison</span>
							<?php
							$shipping_total = $cart->get_shipping_total();
							if ( $cart->get_subtotal() >= 500 || floatval( $shipping_total ) == 0 ) : ?>
								<span class="wwb-cart__summary-free">
									Offerte
									<small>À partir de 89 €</small>
								</span>
							<?php else : ?>
								<span><?php echo wc_price( $shipping_total ); ?></span>
							<?php endif; ?>
						</div>
					</div>

					<?php if ( $cart->get_subtotal() >= 500 ) : ?>
					<div class="wwb-cart__summary-savings">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
						Vous économisez 89 € de frais de livraison
					</div>
					<?php endif; ?>

					<!-- Coupon -->
					<div class="wwb-cart__coupon">
						<button type="button" class="wwb-cart__coupon-toggle" onclick="this.nextElementSibling.classList.toggle('wwb-cart__coupon-form--open')">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
							Vous avez un code promo ?
						</button>
						<div class="wwb-cart__coupon-form">
							<input type="text" name="coupon_code" class="wwb-cart__coupon-input" placeholder="Code de réduction" id="coupon_code" value="" />
							<button type="submit" name="apply_coupon" class="wwb-cart__coupon-apply" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>">Appliquer</button>
						</div>
					</div>

					<!-- Total -->
					<div class="wwb-cart__summary-total">
						<div class="wwb-cart__summary-row wwb-cart__summary-row--total">
							<span>Total TTC</span>
							<span class="wwb-cart__total-price"><?php echo $cart->get_total(); ?></span>
						</div>
						<?php
						$subtotal_ex_tax = floatval( $cart->get_subtotal() );
						$total           = floatval( $cart->get_total( 'edit' ) );
						$tax_total       = $total - $subtotal_ex_tax;
						if ( $tax_total > 0 ) : ?>
						<div class="wwb-cart__summary-row wwb-cart__summary-row--small">
							<span>dont TVA (20%)</span>
							<span><?php echo wc_price( $tax_total ); ?></span>
						</div>
						<div class="wwb-cart__summary-row wwb-cart__summary-row--small">
							<span>Total HT</span>
							<span><?php echo wc_price( $subtotal_ex_tax ); ?></span>
						</div>
						<?php endif; ?>
					</div>

					<!-- CTA -->
					<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="wwb-cart__checkout-btn">
						Passer commande
						<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
					</a>

					<!-- Trust badges -->
					<div class="wwb-cart__trust">
						<div class="wwb-cart__trust-item">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
							Paiement 100% sécurisé — SSL + 3DS
						</div>
						<div class="wwb-cart__trust-item">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
							Livraison garantie sans casse
						</div>
						<div class="wwb-cart__trust-item">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
							Retour accepté sous 30 jours
						</div>
					</div>

					<!-- Payment methods -->
					<div class="wwb-cart__payments">
						<span class="wwb-cart__payments-label">Paiement accepté</span>
						<div class="wwb-cart__payments-methods">
							<span class="wwb-cart__payment-badge">VISA</span>
							<span class="wwb-cart__payment-badge">MC</span>
							<span class="wwb-cart__payment-badge">Virement</span>
							<span class="wwb-cart__payment-badge">Chèque</span>
						</div>
					</div>

				</div>
			</div>

		</div>

	</form>

	<?php do_action( 'woocommerce_after_cart' ); ?>

	<!-- Cross-sell -->
	<?php
	$cross_sells = array_filter( array_map( 'wc_get_product', $cart->get_cross_sells() ), 'wc_products_array_filter_visible' );
	if ( $cross_sells ) :
	?>
	<section class="wwb-cart__cross-sell">
		<div class="wwb-cart__cross-sell-header">
			<div>
				<span class="wwb-cart__cross-sell-eyebrow">Complétez votre projet</span>
				<h2 class="wwb-cart__cross-sell-title">Vous pourriez aussi aimer</h2>
			</div>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="wwb-cart__cross-sell-link">
				Voir tout le catalogue
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
			</a>
		</div>
		<div class="wwb-cart__cross-sell-grid">
			<?php foreach ( $cross_sells as $cross_sell ) :
				$post_object = get_post( $cross_sell->get_id() );
				setup_postdata( $GLOBALS['post'] = &$post_object );
				wc_get_template_part( 'content', 'product' );
			endforeach;
			wp_reset_postdata();
			?>
		</div>
	</section>
	<?php endif; ?>

</div>
