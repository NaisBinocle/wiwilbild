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
			<a href="<?php echo esc_url( home_url( '/produit/fenetre-pvc-sur-mesure/' ) ); ?>" class="wwb-single__custom-link-cta">
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
		// ── Fiche technique carrelage (meta + dimensions) ──
		$length = (float) $product->get_length();
		$width  = (float) $product->get_width();
		if ( ! $length || ! $width ) {
			$vs = $product->is_type( 'variable' ) ? $product->get_available_variations() : array();
			if ( ! empty( $vs ) ) {
				$length = (float) ( $vs[0]['dimensions']['length'] ?? 0 );
				$width  = (float) ( $vs[0]['dimensions']['width']  ?? 0 );
			}
		}
		$format_str = ( $length && $width )
			? number_format( $length, 2, ',', '' ) . ' × ' . number_format( $width, 2, ',', '' ) . ' cm'
			: '';
		$matiere    = $product->get_meta( '_wwb_matiere' );
		$epaisseur  = $product->get_meta( '_wwb_epaisseur' );
		$finition   = $product->get_meta( '_wwb_finition' );
		$resistance = $product->get_meta( '_wwb_resistance' );
		$usages     = $product->get_meta( '_wwb_usages' );
		$specs_rows = array_filter( array(
			$format_str ? array( 'Format',     $format_str ) : null,
			( $matiere || $epaisseur ) ? array( 'Matière', trim( $matiere . ( $epaisseur ? ' · ' . $epaisseur : '' ) ) ) : null,
			$finition   ? array( 'Finition',   $finition )   : null,
			$resistance ? array( 'Résistance', $resistance ) : null,
			$usages     ? array( 'Usages',     $usages )     : null,
		) );
		?>
		<?php if ( ! empty( $specs_rows ) ) : ?>
		<div class="wwb-carrelage-specs">
			<?php foreach ( $specs_rows as $row ) : ?>
				<div class="wwb-carrelage-specs__row">
					<span class="wwb-carrelage-specs__label"><?php echo esc_html( $row[0] ); ?></span>
					<span class="wwb-carrelage-specs__value"><?php echo esc_html( $row[1] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<?php
		// ── Calculateur surface carrelage ──
		$surface_par_boite = (float) $product->get_meta( '_wwb_surface_par_boite' );
		$pieces_par_boite  = (int) $product->get_meta( '_wwb_pieces_par_boite' );

		if ( ! $surface_par_boite ) {
			$length = (float) $product->get_length();
			$width  = (float) $product->get_width();
			if ( ( ! $length || ! $width ) && $product->is_type( 'variable' ) ) {
				$variations = $product->get_available_variations();
				if ( ! empty( $variations ) ) {
					$length = (float) ( $variations[0]['dimensions']['length'] ?? 0 );
					$width  = (float) ( $variations[0]['dimensions']['width'] ?? 0 );
				}
			}
			if ( $length && $width ) {
				$surface_unitaire  = ( $length / 100 ) * ( $width / 100 );
				$surface_par_boite = $surface_unitaire * max( 1, $pieces_par_boite ?: 12 );
			}
		}

		$price_per_m2 = (float) $product->get_price();
		?>
		<div class="wwb-calc"
			data-surface-par-boite="<?php echo esc_attr( number_format( $surface_par_boite, 4, '.', '' ) ); ?>"
			data-pieces-par-boite="<?php echo esc_attr( $pieces_par_boite ); ?>"
			data-price-m2="<?php echo esc_attr( $price_per_m2 ); ?>">
			<div class="wwb-calc__head">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="2" width="16" height="20" rx="2"/><line x1="8" y1="6" x2="16" y2="6"/><line x1="16" y1="14" x2="16" y2="18"/><line x1="8" y1="10" x2="8" y2="10.01"/><line x1="12" y1="10" x2="12" y2="10.01"/><line x1="8" y1="14" x2="8" y2="14.01"/><line x1="12" y1="14" x2="12" y2="14.01"/><line x1="8" y1="18" x2="8" y2="18.01"/><line x1="12" y1="18" x2="12" y2="18.01"/></svg>
				<span>Calculez votre surface</span>
			</div>

			<div class="wwb-calc__inputs">
				<label class="wwb-calc__field">
					<span class="wwb-calc__field-label">Surface</span>
					<span class="wwb-calc__field-row">
						<input type="number" min="0" step="0.1" value="25" class="wwb-calc__input" data-wwb-calc="m2" inputmode="decimal">
						<span class="wwb-calc__unit">m²</span>
					</span>
				</label>
				<label class="wwb-calc__field">
					<span class="wwb-calc__field-label">Quantité</span>
					<span class="wwb-calc__field-row">
						<input type="number" min="1" step="1" value="1" class="wwb-calc__input" data-wwb-calc="boxes" inputmode="numeric">
						<span class="wwb-calc__unit">boîtes</span>
					</span>
				</label>
			</div>

			<label class="wwb-calc__margin">
				<input type="checkbox" data-wwb-calc="margin" checked>
				<span class="wwb-calc__margin-box" aria-hidden="true">
					<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
				</span>
				<span>Majorer de 10 % pour les chutes de découpe</span>
			</label>

			<div class="wwb-calc__total">
				<span class="wwb-calc__total-label">Total estimé</span>
				<span class="wwb-calc__total-value" data-wwb-calc="total">—</span>
			</div>
			<p class="wwb-calc__hint" data-wwb-calc="hint"></p>
		</div>

		<div class="single_variation_wrap">
			<?php
			do_action( 'woocommerce_before_single_variation' );
			do_action( 'woocommerce_single_variation' );
			do_action( 'woocommerce_after_single_variation' );
			?>
		</div>

		<!-- Encart "Visualisez grâce à l'IA" → toujours vers création de compte -->
		<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="wwb-ai-teaser">
			<svg class="wwb-ai-teaser__icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l1.5 4.5L18 9l-4.5 1.5L12 15l-1.5-4.5L6 9l4.5-1.5L12 3z"/><path d="M19 14l.7 2.1 2.1.7-2.1.7-.7 2.1-.7-2.1-2.1-.7 2.1-.7.7-2.1z"/></svg>
			<span class="wwb-ai-teaser__text">Visualisez ce carrelage chez vous grâce à l'IA</span>
			<span class="wwb-ai-teaser__cta">Essayer →</span>
		</a>

	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' );
