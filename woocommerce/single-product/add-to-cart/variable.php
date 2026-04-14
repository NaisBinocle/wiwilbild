<?php
/**
 * WWB V2 — Variable product add to cart
 *
 * Menuiserie: predefined size buttons + custom dimensions fallback + vitrage pills + color swatches.
 * Carrelage: attribute swatches + surface calculator (preserved).
 *
 * @package WWB_V2
 * @version 10.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

// Detect product type
$is_carrelage  = has_term( 'carrelage', 'product_cat', $product->get_id() );
$is_menuiserie = ! $is_carrelage;

// Separate attributes by role (menuiserie only)
$attr_taille  = null;
$attr_vitrage = null;
$attr_couleur = null;
$attr_other   = array();

if ( $is_menuiserie ) {
	foreach ( $attributes as $attr_name => $options ) {
		$slug = strtolower( $attr_name );
		if ( strpos( $slug, 'taille' ) !== false || strpos( $slug, 'dimension' ) !== false ) {
			$attr_taille = array( 'name' => $attr_name, 'options' => $options );
		} elseif ( strpos( $slug, 'vitrage' ) !== false ) {
			$attr_vitrage = array( 'name' => $attr_name, 'options' => $options );
		} elseif ( strpos( $slug, 'couleur' ) !== false ) {
			$attr_couleur = array( 'name' => $attr_name, 'options' => $options );
		} else {
			$attr_other[ $attr_name ] = $options;
		}
	}
}

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?></p>

	<?php elseif ( $is_menuiserie ) : ?>

		<?php // ── MENUISERIE LAYOUT ── ?>

		<?php if ( $attr_taille ) : ?>
			<!-- Section: Tailles standard -->
			<div class="wwb-single__config-section">
				<div class="wwb-single__config-title">
					<span class="wwb-single__config-step">1</span>
					<strong>LES MESURES STANDARD / LES + VENDUES</strong>
				</div>
				<p class="wwb-single__config-hint">Les dimensions sont exprimées en largeur × hauteur. Ces cotes correspondent aux dimensions tableau (côtes d'ouverture dans le mur).</p>

				<div class="wwb-single__sizes" data-config-group="taille">
					<?php
					$first = true;
					foreach ( $attr_taille['options'] as $option ) :
						$term  = get_term_by( 'slug', $option, $attr_taille['name'] );
						$label = $term ? $term->name : $option;
						// Normalize label: add "cm" if missing
						if ( strpos( $label, 'cm' ) === false && strpos( $label, 'x' ) !== false ) {
							$label .= ' cm';
						}
					?>
						<button type="button"
							class="wwb-single__size-btn<?php echo $first ? ' wwb-single__size-btn--active' : ''; ?>"
							data-value="<?php echo esc_attr( $option ); ?>"
							aria-pressed="<?php echo $first ? 'true' : 'false'; ?>">
							<?php echo esc_html( $label ); ?>
						</button>
					<?php $first = false; endforeach; ?>
				</div>

				<!-- Hidden select for WC -->
				<select id="<?php echo esc_attr( sanitize_title( $attr_taille['name'] ) ); ?>"
					name="attribute_<?php echo esc_attr( sanitize_title( $attr_taille['name'] ) ); ?>"
					class="wwb-hidden-select" aria-hidden="true">
					<option value=""><?php esc_html_e( 'Choose an option', 'woocommerce' ); ?></option>
					<?php foreach ( $attr_taille['options'] as $option ) :
						$term = get_term_by( 'slug', $option, $attr_taille['name'] );
					?>
						<option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $term ? $term->name : $option ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		<?php endif; ?>

		<!-- Hors dimension standard → lien sur-mesure -->
		<div class="wwb-single__custom-link">
			<span class="wwb-single__custom-link-label">DIMENSIONS HORS STANDARD ?</span>
			<a href="<?php echo esc_url( home_url( '/sur-mesure/' ) ); ?>" class="wwb-single__custom-link-cta">
				<span class="wwb-single__custom-link-inner">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.4 2.4 0 0 1 0-3.4l2.6-2.6a2.4 2.4 0 0 1 3.4 0Z"/><path d="m14.5 12.5 2-2"/><path d="m11.5 9.5 2-2"/><path d="m8.5 6.5 2-2"/><path d="m17.5 15.5 2-2"/></svg>
					Découvrir nos fenêtres sur mesure
				</span>
				<svg class="wwb-single__custom-link-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
			</a>
		</div>

		<?php if ( $attr_vitrage ) : ?>
			<!-- Section: Vitrage -->
			<div class="wwb-single__config-section">
				<div class="wwb-single__config-title">
					<span class="wwb-single__config-step">2</span>
					<strong>VITRAGE</strong>
				</div>
				<div class="wwb-single__vitrage" data-config-group="vitrage">
					<?php
					$vitrage_details = array(
						'double-vitrage' => '4/16/4 argon',
						'triple-vitrage' => '4/12/4/12/4',
					);
					$first = true;
					foreach ( $attr_vitrage['options'] as $option ) :
						$term    = get_term_by( 'slug', $option, $attr_vitrage['name'] );
						$label   = $term ? $term->name : $option;
						$sub     = isset( $vitrage_details[ $option ] ) ? $vitrage_details[ $option ] : '';
					?>
						<button type="button"
							class="wwb-single__vitrage-btn<?php echo $first ? ' wwb-single__vitrage-btn--active' : ''; ?>"
							data-value="<?php echo esc_attr( $option ); ?>"
							aria-pressed="<?php echo $first ? 'true' : 'false'; ?>">
							<span class="wwb-single__vitrage-label"><?php echo esc_html( $label ); ?></span>
							<?php if ( $sub ) : ?>
								<small class="wwb-single__vitrage-sub"><?php echo esc_html( $sub ); ?></small>
							<?php endif; ?>
						</button>
					<?php $first = false; endforeach; ?>
				</div>

				<!-- Hidden select for WC -->
				<select id="<?php echo esc_attr( sanitize_title( $attr_vitrage['name'] ) ); ?>"
					name="attribute_<?php echo esc_attr( sanitize_title( $attr_vitrage['name'] ) ); ?>"
					class="wwb-hidden-select" aria-hidden="true">
					<option value=""><?php esc_html_e( 'Choose an option', 'woocommerce' ); ?></option>
					<?php foreach ( $attr_vitrage['options'] as $option ) :
						$term = get_term_by( 'slug', $option, $attr_vitrage['name'] );
					?>
						<option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $term ? $term->name : $option ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		<?php endif; ?>

		<?php
		// Coloris "libres" (non-variation) pour menuiserie : affiché en plus de $attr_couleur
		// Sauvegardé en cart item meta via hooks dans inc/woocommerce.php
		if ( $is_menuiserie && ! $attr_couleur && class_exists( 'WWB_Configurator' ) ) :
			$coloris_options = WWB_Configurator::get_config_options()['coloris'] ?? [];
			if ( ! empty( $coloris_options ) ) :
		?>
			<div class="wwb-single__config-section">
				<div class="wwb-single__config-title">
					<span class="wwb-single__config-step">3</span>
					<strong>COLORIS</strong>
					<span class="wwb-single__color-label" id="wwb-color-label">Blanc</span>
				</div>
				<div class="wwb-color-swatches" data-config-group="wwb_coloris">
					<?php $first = true; foreach ( $coloris_options as $slug => $col ) :
						$is_gradient = strpos( $col['hex'], 'linear-gradient' ) !== false;
						$style = $is_gradient ? 'background: ' . esc_attr( $col['hex'] ) . ';' : 'background-color: ' . esc_attr( $col['hex'] ) . ';';
					?>
						<button type="button"
							class="wwb-color-swatches__swatch<?php echo $first ? ' is-active' : ''; ?>"
							data-value="<?php echo esc_attr( $slug ); ?>"
							data-label="<?php echo esc_attr( $col['label'] ); ?>"
							style="<?php echo $style; ?>"
							title="<?php echo esc_attr( $col['label'] ); ?>"
							aria-label="<?php echo esc_attr( $col['label'] ); ?>"
							aria-pressed="<?php echo $first ? 'true' : 'false'; ?>"></button>
					<?php $first = false; endforeach; ?>
				</div>
				<input type="hidden" name="wwb_coloris" id="wwb_coloris_value" value="<?php echo esc_attr( array_key_first( $coloris_options ) ); ?>">
				<input type="hidden" name="wwb_coloris_label" id="wwb_coloris_label" value="<?php echo esc_attr( reset( $coloris_options )['label'] ); ?>">
			</div>
		<?php endif; endif; ?>

		<?php if ( $attr_couleur ) : ?>
			<!-- Section: Couleur -->
			<div class="wwb-single__config-section">
				<div class="wwb-single__config-title">
					<span class="wwb-single__config-step">3</span>
					<strong>COLORIS</strong>
				</div>
				<div class="wwb-color-swatches" data-config-group="couleur">
					<?php foreach ( $attr_couleur['options'] as $option ) :
						$term  = get_term_by( 'slug', $option, $attr_couleur['name'] );
						$color = $term && function_exists( 'get_field' )
							? get_field( 'product_color_pick', $attr_couleur['name'] . '_' . $term->term_id )
							: '#ccc';
						if ( empty( $color ) ) $color = '#ccc';
					?>
						<button type="button"
							class="wwb-color-swatches__swatch"
							data-value="<?php echo esc_attr( $option ); ?>"
							style="background-color: <?php echo esc_attr( $color ); ?>;"
							title="<?php echo esc_attr( $term ? $term->name : $option ); ?>"
							aria-label="<?php echo esc_attr( $term ? $term->name : $option ); ?>"></button>
					<?php endforeach; ?>
				</div>
				<span class="wwb-single__color-label" id="wwb-color-label"></span>

				<!-- Hidden select for WC -->
				<select id="<?php echo esc_attr( sanitize_title( $attr_couleur['name'] ) ); ?>"
					name="attribute_<?php echo esc_attr( sanitize_title( $attr_couleur['name'] ) ); ?>"
					class="wwb-hidden-select" aria-hidden="true">
					<option value=""><?php esc_html_e( 'Choose an option', 'woocommerce' ); ?></option>
					<?php foreach ( $attr_couleur['options'] as $option ) :
						$term = get_term_by( 'slug', $option, $attr_couleur['name'] );
					?>
						<option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $term ? $term->name : $option ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		<?php endif; ?>

		<?php // Other attributes (if any) ?>
		<?php if ( ! empty( $attr_other ) ) : ?>
			<table class="variations" cellspacing="0" role="presentation">
				<tbody>
					<?php foreach ( $attr_other as $attribute_name => $options ) : ?>
						<tr>
							<th class="label"><label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); ?></label></th>
							<td class="value">
								<div class="wwb-attribute-swatches">
									<?php foreach ( $options as $option ) :
										$term  = get_term_by( 'slug', $option, $attribute_name );
										$label = $term ? $term->name : $option;
									?>
										<button type="button" class="wwb-attribute-swatches__swatch" data-value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $label ); ?></button>
									<?php endforeach; ?>
								</div>
								<select id="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"
									name="attribute_<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"
									class="wwb-hidden-select" aria-hidden="true">
									<option value=""><?php esc_html_e( 'Choose an option', 'woocommerce' ); ?></option>
									<?php foreach ( $options as $option ) :
										$term = get_term_by( 'slug', $option, $attribute_name );
									?>
										<option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $term ? $term->name : $option ); ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>

		<div class="reset_variations_alert screen-reader-text" role="alert" aria-live="polite" aria-relevant="all"></div>
		<?php do_action( 'woocommerce_after_variations_table' ); ?>

		<!-- Price + Add to cart -->
		<div class="single_variation_wrap">
			<?php
			do_action( 'woocommerce_before_single_variation' );
			do_action( 'woocommerce_single_variation' );
			do_action( 'woocommerce_after_single_variation' );
			?>
		</div>

	<?php else : ?>

		<?php // ── CARRELAGE LAYOUT (preserved) ── ?>

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
								<option value=""><?php esc_html_e( 'Choose an option', 'woocommerce' ); ?></option>
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
		$length = 0; $width = 0; $surface_unitaire = 0;
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
