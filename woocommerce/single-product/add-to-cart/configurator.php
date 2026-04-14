<?php
/**
 * WWB V2 — Configurateur Fenêtre Sur Mesure
 *
 * Multi-step configurator with SVG preview and dynamic pricing.
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div id="wwb-configurator" class="wwb-cfg" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">

	<!-- Layout 2 colonnes -->
	<div class="wwb-cfg__layout">

		<!-- Colonne gauche : Preview + Prix -->
		<div class="wwb-cfg__preview-col">
			<div class="wwb-cfg__preview-header">
				<span>Votre configuration</span>
				<span class="wwb-cfg__view-toggle">Vue intérieure</span>
			</div>

			<div class="wwb-cfg__preview">
				<svg id="wwb-cfg-svg" viewBox="0 0 400 500" xmlns="http://www.w3.org/2000/svg">
					<!-- Cadre extérieur -->
					<rect id="cfg-frame" x="40" y="40" width="320" height="420" rx="3"
						fill="none" stroke="#888" stroke-width="8"/>
					<!-- Vitrage -->
					<rect id="cfg-glass" x="52" y="52" width="296" height="396" rx="2"
						fill="#d4e8f0" opacity="0.6"/>
					<!-- Croisillon central (2 vantaux) -->
					<line id="cfg-mullion-v" x1="200" y1="52" x2="200" y2="448"
						stroke="#888" stroke-width="6" style="display:none"/>
					<!-- Croisillon horizontal (3 vantaux) -->
					<line id="cfg-mullion-v2" x1="160" y1="52" x2="160" y2="448"
						stroke="#888" stroke-width="6" style="display:none"/>
					<line id="cfg-mullion-v3" x1="266" y1="52" x2="266" y2="448"
						stroke="#888" stroke-width="6" style="display:none"/>
					<!-- Poignée OB -->
					<rect id="cfg-handle" x="190" y="260" width="20" height="6" rx="2"
						fill="#666" style="display:none"/>
					<!-- Dimensions labels -->
					<text id="cfg-dim-width" x="200" y="480" text-anchor="middle"
						font-size="14" fill="#666">800 mm</text>
					<text id="cfg-dim-height" x="20" y="260" text-anchor="middle"
						font-size="14" fill="#666" transform="rotate(-90, 20, 260)">1000 mm</text>
				</svg>
			</div>

			<div class="wwb-cfg__price-block">
				<div class="wwb-cfg__price">
					<span id="wwb-cfg-price">256,00</span> €
				</div>
				<div class="wwb-cfg__price-info">Prix TTC · Délai estimé 3-5 semaines</div>
			</div>

			<!-- Récap accordion -->
			<div class="wwb-cfg__recap">
				<div class="wwb-cfg__recap-title">Récapitulatif de votre configuration</div>
				<div id="wwb-cfg-recap-list" class="wwb-cfg__recap-list">
					<!-- Rempli par JS -->
				</div>
			</div>
		</div>

		<!-- Colonne droite : Étapes -->
		<div class="wwb-cfg__steps-col">

			<div class="wwb-cfg__steps-nav">
				<span class="wwb-cfg__step-indicator active" data-step="1">Étape 1</span>
				<span class="wwb-cfg__step-connector"></span>
				<span class="wwb-cfg__step-indicator" data-step="2">Étape 2</span>
				<span class="wwb-cfg__step-connector"></span>
				<span class="wwb-cfg__step-indicator" data-step="3">Étape 3</span>
			</div>

			<!-- STEP 1: Type + Dimensions -->
			<div class="wwb-cfg__step active" data-step="1">
				<div class="wwb-cfg__section">
					<h3 class="wwb-cfg__section-title">TYPE DE FENÊTRE</h3>
					<div class="wwb-cfg__cards" data-field="type">
						<?php foreach ( $config['types'] as $slug => $data ) : ?>
							<button type="button" class="wwb-cfg__card" data-value="<?php echo esc_attr( $slug ); ?>">
								<div class="wwb-cfg__card-icon"><?php echo esc_html( $data['icon'] ); ?></div>
								<div class="wwb-cfg__card-label"><?php echo esc_html( $data['label'] ); ?></div>
								<div class="wwb-cfg__card-price">dès <?php echo esc_html( $config['base_prices'][ $slug ] ?? '---' ); ?> €/m²</div>
							</button>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="wwb-cfg__section">
					<h3 class="wwb-cfg__section-title">DIMENSIONS</h3>
					<div class="wwb-cfg__dims">
						<div class="wwb-cfg__dim-input">
							<label for="cfg-width">Largeur</label>
							<div class="wwb-cfg__dim-field">
								<input type="number" id="cfg-width" min="<?php echo $config['dimensions']['min_width']; ?>"
									max="<?php echo $config['dimensions']['max_width']; ?>" value="800" step="10"/>
								<span>mm</span>
							</div>
							<input type="range" id="cfg-width-range"
								min="<?php echo $config['dimensions']['min_width']; ?>"
								max="<?php echo $config['dimensions']['max_width']; ?>" value="800" step="10"/>
						</div>
						<div class="wwb-cfg__dim-input">
							<label for="cfg-height">Hauteur</label>
							<div class="wwb-cfg__dim-field">
								<input type="number" id="cfg-height" min="<?php echo $config['dimensions']['min_height']; ?>"
									max="<?php echo $config['dimensions']['max_height']; ?>" value="1000" step="10"/>
								<span>mm</span>
							</div>
							<input type="range" id="cfg-height-range"
								min="<?php echo $config['dimensions']['min_height']; ?>"
								max="<?php echo $config['dimensions']['max_height']; ?>" value="1000" step="10"/>
						</div>
					</div>
				</div>

				<div class="wwb-cfg__section">
					<h3 class="wwb-cfg__section-title">TYPE D'OUVERTURE</h3>
					<div class="wwb-cfg__cards wwb-cfg__cards--small" data-field="ouverture">
						<?php foreach ( $config['ouvertures'] as $slug => $label ) : ?>
							<button type="button" class="wwb-cfg__card" data-value="<?php echo esc_attr( $slug ); ?>">
								<div class="wwb-cfg__card-label"><?php echo esc_html( $label ); ?></div>
							</button>
						<?php endforeach; ?>
					</div>
				</div>

				<button type="button" class="wwb-cfg__next-btn" data-goto="2">
					Étape suivante : Vitrage & Finitions →
				</button>
			</div>

			<!-- STEP 2: Dormant + Vitrage + Coloris -->
			<div class="wwb-cfg__step" data-step="2">
				<div class="wwb-cfg__section">
					<h3 class="wwb-cfg__section-title">DORMANT</h3>
					<div class="wwb-cfg__cards wwb-cfg__cards--small" data-field="dormant">
						<?php foreach ( $config['dormants'] as $slug => $label ) : ?>
							<button type="button" class="wwb-cfg__card" data-value="<?php echo esc_attr( $slug ); ?>">
								<div class="wwb-cfg__card-label"><?php echo esc_html( $label ); ?></div>
								<?php if ( ( $config['supplements'][ $slug ] ?? 0 ) > 0 ) : ?>
									<div class="wwb-cfg__card-price">+<?php echo $config['supplements'][ $slug ]; ?> €</div>
								<?php endif; ?>
							</button>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="wwb-cfg__section">
					<h3 class="wwb-cfg__section-title">VITRAGE</h3>
					<div class="wwb-cfg__cards wwb-cfg__cards--small" data-field="vitrage">
						<?php foreach ( $config['vitrages'] as $slug => $label ) : ?>
							<button type="button" class="wwb-cfg__card" data-value="<?php echo esc_attr( $slug ); ?>">
								<div class="wwb-cfg__card-label"><?php echo esc_html( $label ); ?></div>
								<?php if ( ( $config['supplements'][ $slug ] ?? 0 ) > 0 ) : ?>
									<div class="wwb-cfg__card-price">+<?php echo $config['supplements'][ $slug ]; ?> €</div>
								<?php endif; ?>
							</button>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="wwb-cfg__section">
					<h3 class="wwb-cfg__section-title">COLORIS</h3>
					<div class="wwb-cfg__colors" data-field="coloris">
						<?php foreach ( $config['coloris'] as $slug => $data ) : ?>
							<button type="button" class="wwb-cfg__color-swatch"
								data-value="<?php echo esc_attr( $slug ); ?>"
								title="<?php echo esc_attr( $data['label'] ); ?>"
								style="background: <?php echo esc_attr( $data['hex'] ); ?>;">
							</button>
						<?php endforeach; ?>
					</div>
					<div id="wwb-cfg-color-label" class="wwb-cfg__color-label">Blanc</div>
				</div>

				<div class="wwb-cfg__nav-btns">
					<button type="button" class="wwb-cfg__prev-btn" data-goto="1">← Retour</button>
					<button type="button" class="wwb-cfg__next-btn" data-goto="3">Étape suivante : Options →</button>
				</div>
			</div>

			<!-- STEP 3: Options + Ajout panier -->
			<div class="wwb-cfg__step" data-step="3">
				<div class="wwb-cfg__section">
					<h3 class="wwb-cfg__section-title">FERRAGE</h3>
					<div class="wwb-cfg__cards wwb-cfg__cards--small" data-field="ferrage">
						<?php foreach ( $config['ferrages'] as $slug => $label ) : ?>
							<button type="button" class="wwb-cfg__card" data-value="<?php echo esc_attr( $slug ); ?>">
								<div class="wwb-cfg__card-label"><?php echo esc_html( $label ); ?></div>
								<?php if ( ( $config['supplements'][ $slug ] ?? 0 ) > 0 ) : ?>
									<div class="wwb-cfg__card-price">+<?php echo $config['supplements'][ $slug ]; ?> €</div>
								<?php endif; ?>
							</button>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="wwb-cfg__section">
					<h3 class="wwb-cfg__section-title">GRILLE D'AÉRATION</h3>
					<div class="wwb-cfg__cards wwb-cfg__cards--small" data-field="grille">
						<?php foreach ( $config['grilles'] as $slug => $label ) : ?>
							<button type="button" class="wwb-cfg__card" data-value="<?php echo esc_attr( $slug ); ?>">
								<div class="wwb-cfg__card-label"><?php echo esc_html( $label ); ?></div>
								<?php if ( ( $config['supplements'][ $slug ] ?? 0 ) > 0 ) : ?>
									<div class="wwb-cfg__card-price">+<?php echo $config['supplements'][ $slug ]; ?> €</div>
								<?php endif; ?>
							</button>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="wwb-cfg__section">
					<h3 class="wwb-cfg__section-title">VOLET ROULANT</h3>
					<div class="wwb-cfg__cards wwb-cfg__cards--small" data-field="volet">
						<?php foreach ( $config['volets'] as $slug => $label ) : ?>
							<button type="button" class="wwb-cfg__card" data-value="<?php echo esc_attr( $slug ); ?>">
								<div class="wwb-cfg__card-label"><?php echo esc_html( $label ); ?></div>
								<?php if ( ( $config['supplements'][ $slug ] ?? 0 ) > 0 ) : ?>
									<div class="wwb-cfg__card-price">+<?php echo $config['supplements'][ $slug ]; ?> €</div>
								<?php endif; ?>
							</button>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="wwb-cfg__nav-btns">
					<button type="button" class="wwb-cfg__prev-btn" data-goto="2">← Retour</button>
					<button type="button" id="wwb-cfg-add-to-cart" class="wwb-cfg__add-btn">
						Ajouter au panier
					</button>
				</div>
			</div>

		</div>
	</div>
</div>
