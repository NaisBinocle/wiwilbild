<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?></p>
	<?php else : ?>
        <table class="variations" cellspacing="0" role="presentation">
            <tbody>
                <?php foreach ( $attributes as $attribute_name => $options ) : ?>
                    <tr class="<?php echo $attribute_name; ?>">
                        <th class="label">
                            <label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>">
                                <?php echo wc_attribute_label( $attribute_name ); ?>
                            </label>
                        </th>
                        <td class="value <?php echo $attribute_name; ?>">
                            <?php if ( strpos( $attribute_name, 'couleurs' ) !== false ) : ?>
                                <!-- Swatches couleurs -->
                                <div class="color-swatches">
                                    <?php foreach ( $options as $option ) :
                                        $term = get_term_by('slug', $option, 'pa_couleurs');
                                        $color = $term ? get_field('product_color_pick', 'pa_couleurs_' . $term->term_id) : '#ccc';
                                    ?>
                                        <span class="swatch" data-value="<?php echo esc_attr( $option ); ?>" style="background-color: <?php echo esc_attr( $color ); ?>;" title="<?php echo esc_attr( $term ? $term->name : $option ); ?>"></span>
                                    <?php endforeach; ?>
                                </div>
                                <!-- Select caché pour WooCommerce -->
                                <select id="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>" name="attribute_<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>" style="display:none;">
                                    <option value=""><?php echo esc_html__( 'Choose an option', 'woocommerce' ); ?></option>
                                    <?php foreach ( $options as $option ) :
                                        $term = get_term_by('slug', $option, 'pa_couleurs');
                                    ?>
                                        <option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $term ? $term->name : $option ); ?></option>
                                    <?php endforeach; ?>
                                </select>

                            <?php else : ?>
                                <!-- Étiquettes cliquables pour autres attributs (dimensions, etc.) -->
                                <div class="attribute-swatches" data-attribute="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>">
                                    <?php foreach ( $options as $option ) :
                                        $term = get_term_by('slug', $option, $attribute_name);
                                        $label = $term ? $term->name : $option;

                                        // Si c'est un attribut dimension et que c'est juste un nombre, afficher format carré
                                        if ( strpos( $attribute_name, 'dimension' ) !== false ) {
                                            // Si le label contient déjà un "x", on le garde tel quel
                                            if ( strpos( $label, 'x' ) === false && is_numeric( trim( str_replace(['cm', ' '], '', $label) ) ) ) {
                                                $dimension = trim( str_replace(['cm', ' '], '', $label) );
                                                $label = $dimension . 'x' . $dimension . ' cm';
                                            } elseif ( strpos( $label, 'cm' ) === false && strpos( $label, 'x' ) !== false ) {
                                                $label = $label . ' cm';
                                            }
                                        }
                                    ?>
                                        <span class="attribute-swatch" data-value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $label ); ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <!-- Select caché pour WooCommerce -->
                                <select id="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>" name="attribute_<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>" style="display:none;">
                                    <option value=""><?php echo esc_html__( 'Choose an option', 'woocommerce' ); ?></option>
                                    <?php foreach ( $options as $option ) :
                                        $term = get_term_by('slug', $option, $attribute_name);
                                    ?>
                                        <option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $term ? $term->name : $option ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#" aria-label="' . esc_attr__( 'Clear options', 'woocommerce' ) . '">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>' ) ) : ''; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

		<div class="reset_variations_alert screen-reader-text" role="alert" aria-live="polite" aria-relevant="all"></div>
		<?php do_action( 'woocommerce_after_variations_table' ); ?>

		<?php
		// Calculateur de surface - uniquement pour carrelage
		$categories_cibles = ['carrelage'];
		if ( has_term( $categories_cibles, 'product_cat', $product->get_id() ) ) :
			// Récupérer la surface unitaire
			$length = 0;
			$width = 0;
			$surface_unitaire = 0;

			if ( $product->is_type('variable') ) {
				$variations = $product->get_available_variations();
				if ( !empty($variations) ) {
					$first_variation = $variations[0];
					$length = (float) ($first_variation['dimensions']['length'] ?? 0);
					$width = (float) ($first_variation['dimensions']['width'] ?? 0);
				}
			} else {
				$length = (float) $product->get_length();
				$width = (float) $product->get_width();
			}

			if ( $length && $width ) {
				$surface_unitaire = ($length / 100) * ($width / 100);
			}
		?>
		<div id="surface-calculator" class="surface-calculator-wrapper" data-surface-unitaire="<?php echo esc_attr($surface_unitaire); ?>">
			<div class="surface-calculator-grid">
				<div class="surface-input-group">
					<label for="surface-input">Surface</label>
					<div class="input-wrapper">
						<input type="number" id="surface-input" min="0" step="0.1" placeholder="Saisir les m²" value="">
						<span class="input-suffix">m²</span>
					</div>
					<label class="margin-checkbox">
						<input type="checkbox" id="add-margin"> Ajouter 10% de marge
						<span class="tooltip" title="Recommandé pour les coupes et pertes">&#9432;</span>
					</label>
				</div>
				<div class="quantity-input-group">
					<label for="box-quantity">Quantité boîte(s)</label>
					<div class="quantity-wrapper">
						<button type="button" class="qty-btn minus">−</button>
						<input type="number" id="box-quantity" min="1" value="1" readonly>
						<button type="button" class="qty-btn plus">+</button>
					</div>
				</div>
			</div>
			<div class="surface-result-info">
				<p><span id="surface-result">0</span> m² couverts avec <span id="box-count">1</span> boîte(s)</p>
				<p class="surface-unit-info">Surface par boîte : <strong><span id="surface-per-box"><?php echo number_format($surface_unitaire, 2, ',', ''); ?></span> m²</strong></p>
			</div>
		</div>
		<?php endif; ?>

		<div class="single_variation_wrap">
			<?php
				/**
				 * Hook: woocommerce_before_single_variation.
				 */
				do_action( 'woocommerce_before_single_variation' );

				/**
				 * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
				 *
				 * @since 2.4.0
				 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
				 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
				 */
				do_action( 'woocommerce_single_variation' );

				/**
				 * Hook: woocommerce_after_single_variation.
				 */
				do_action( 'woocommerce_after_single_variation' );
			?>
		</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
