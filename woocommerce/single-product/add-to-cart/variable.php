<?php
/**
 * WWB V2 — Variable product add to cart
 *
 * Swatches couleur + attributs + calculateur surface (carrelage).
 * BEM classes for V2 design system.
 *
 * @package WWB_V2
 * @version 9.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?></p>
	<?php else : ?>

		<table class="variations" cellspacing="0" role="presentation">
			<tbody>
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>
					<tr>
						<th class="label">
							<label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>">
								<?php echo wc_attribute_label( $attribute_name ); ?>
							</label>
						</th>
						<td class="value">

							<?php if ( strpos( $attribute_name, 'couleurs' ) !== false ) : ?>
								<div class="wwb-color-swatches">
									<?php foreach ( $options as $option ) :
										$term = get_term_by( 'slug', $option, 'pa_couleurs' );
										$color = $term && function_exists( 'get_field' )
											? get_field( 'product_color_pick', 'pa_couleurs_' . $term->term_id )
											: '#ccc';
									?>
										<button type="button"
											class="wwb-color-swatches__swatch"
											data-value="<?php echo esc_attr( $option ); ?>"
											style="background-color: <?php echo esc_attr( $color ); ?>;"
											title="<?php echo esc_attr( $term ? $term->name : $option ); ?>"
											aria-label="<?php echo esc_attr( $term ? $term->name : $option ); ?>"></button>
									<?php endforeach; ?>
								</div>
							<?php else : ?>
								<div class="wwb-attribute-swatches">
									<?php foreach ( $options as $option ) :
										$term = get_term_by( 'slug', $option, $attribute_name );
										$label = $term ? $term->name : $option;
										if ( strpos( $attribute_name, 'dimension' ) !== false ) {
											if ( strpos( $label, 'x' ) === false && is_numeric( trim( str_replace( array( 'cm', ' ' ), '', $label ) ) ) ) {
												$dim = trim( str_replace( array( 'cm', ' ' ), '', $label ) );
												$label = $dim . 'x' . $dim . ' cm';
											} elseif ( strpos( $label, 'cm' ) === false && strpos( $label, 'x' ) !== false ) {
												$label .= ' cm';
											}
										}
									?>
										<button type="button"
											class="wwb-attribute-swatches__swatch"
											data-value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $label ); ?></button>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

							<select id="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"
								name="attribute_<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"
								class="wwb-hidden-select" aria-hidden="true">
								<option value=""><?php echo esc_html__( 'Choose an option', 'woocommerce' ); ?></option>
								<?php foreach ( $options as $option ) :
									$term = get_term_by( 'slug', $option, $attribute_name );
								?>
									<option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $term ? $term->name : $option ); ?></option>
								<?php endforeach; ?>
							</select>

							<?php
							if ( end( $attribute_keys ) === $attribute_name ) {
								echo wp_kses_post( apply_filters( 'woocommerce_reset_variations_link',
									'<a class="reset_variations" href="#" aria-label="' . esc_attr__( 'Clear options', 'woocommerce' ) . '">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>'
								) );
							}
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<div class="reset_variations_alert screen-reader-text" role="alert" aria-live="polite" aria-relevant="all"></div>
		<?php do_action( 'woocommerce_after_variations_table' ); ?>

		<?php
		// Surface calculator — carrelage only
		$categories_cibles = array( 'carrelage' );
		if ( has_term( $categories_cibles, 'product_cat', $product->get_id() ) ) :
			$length = 0;
			$width = 0;
			$surface_unitaire = 0;

			if ( $product->is_type( 'variable' ) ) {
				$variations = $product->get_available_variations();
				if ( ! empty( $variations ) ) {
					$length = (float) ( $variations[0]['dimensions']['length'] ?? 0 );
					$width  = (float) ( $variations[0]['dimensions']['width'] ?? 0 );
				}
			} else {
				$length = (float) $product->get_length();
				$width  = (float) $product->get_width();
			}

			if ( $length && $width ) {
				$surface_unitaire = ( $length / 100 ) * ( $width / 100 );
			}
		?>
		<div id="surface-calculator" class="wwb-surface-calc" data-surface-unitaire="<?php echo esc_attr( $surface_unitaire ); ?>">
			<h4 class="wwb-surface-calc__title">Calculez votre surface</h4>

			<div class="wwb-surface-calc__input-group">
				<label for="surface-input">Surface souhaitée</label>
				<div style="display:flex;align-items:center;gap:8px;">
					<input type="number" id="surface-input" class="wwb-surface-calc__input" min="0" step="0.1" placeholder="m²" value="">
					<span>m²</span>
				</div>
			</div>

			<label style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--wp--preset--color--body);margin:8px 0 16px;">
				<input type="checkbox" id="add-margin"> Ajouter 10% de marge (coupes)
			</label>

			<div class="wwb-surface-calc__input-group">
				<label for="box-quantity">Quantité boîte(s)</label>
				<div class="wwb-qty">
					<button type="button" class="wwb-qty__btn wwb-qty__btn--minus">−</button>
					<input type="number" id="box-quantity" class="wwb-qty__input" min="1" value="1" readonly>
					<button type="button" class="wwb-qty__btn wwb-qty__btn--plus">+</button>
				</div>
			</div>

			<div class="wwb-surface-calc__result">
				<p><strong><span id="surface-result">0</span> m²</strong> couverts avec <strong><span id="box-count">1</span></strong> boîte(s)</p>
				<p style="font-size:12px;color:var(--wp--preset--color--muted);">Surface par boîte : <span id="surface-per-box"><?php echo number_format( $surface_unitaire, 2, ',', '' ); ?></span> m²</p>
			</div>
		</div>
		<?php endif; ?>

		<div class="single_variation_wrap">
			<?php
			do_action( 'woocommerce_before_single_variation' );
			do_action( 'woocommerce_single_variation' );
			do_action( 'woocommerce_after_single_variation' );
			?>
		</div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' );
