/**
 * WWB V2 — Fenêtre Sur Mesure Configurator
 * Multi-step configurator with SVG preview & dynamic pricing.
 */
(function () {
	'use strict';

	var cfg = window.wwbConfigurator;
	if (!cfg) return;

	// Current config state
	var state = {
		type: '1-vantail',
		width: 800,
		height: 1000,
		ouverture: 'oscillo-battant',
		dormant: 'dormant-72mm',
		vitrage: '4-16-4-standard',
		coloris: 'blanc',
		ferrage: 'ferrage-ob',
		grille: 'grille-sans',
		volet: 'vr-sans',
	};

	var currentStep = 1;

	// ─── INIT ───
	document.addEventListener('DOMContentLoaded', function () {
		initCards();
		initDimensions();
		initNavigation();
		initAddToCart();
		selectDefaults();
		updatePrice();
		updatePreview();
	});

	// ─── CARD SELECTION ───
	function initCards() {
		document.querySelectorAll('.wwb-cfg__cards, .wwb-cfg__colors').forEach(function (container) {
			var field = container.dataset.field;
			container.querySelectorAll('.wwb-cfg__card, .wwb-cfg__color-swatch').forEach(function (card) {
				card.addEventListener('click', function () {
					// Deselect siblings
					container.querySelectorAll('.wwb-cfg__card, .wwb-cfg__color-swatch').forEach(function (c) {
						c.classList.remove('selected');
					});
					this.classList.add('selected');
					state[field] = this.dataset.value;

					// Update color label
					if (field === 'coloris') {
						var label = this.getAttribute('title') || this.dataset.value;
						var labelEl = document.getElementById('wwb-cfg-color-label');
						if (labelEl) labelEl.textContent = label;
					}

					updatePrice();
					updatePreview();
					updateRecap();
				});
			});
		});
	}

	function selectDefaults() {
		Object.keys(state).forEach(function (field) {
			var container = document.querySelector('[data-field="' + field + '"]');
			if (!container) return;
			var card = container.querySelector('[data-value="' + state[field] + '"]');
			if (card) card.classList.add('selected');
		});
		updateRecap();
	}

	// ─── DIMENSIONS ───
	function initDimensions() {
		var widthInput = document.getElementById('cfg-width');
		var widthRange = document.getElementById('cfg-width-range');
		var heightInput = document.getElementById('cfg-height');
		var heightRange = document.getElementById('cfg-height-range');

		if (!widthInput) return;

		// Sync inputs ↔ ranges
		widthInput.addEventListener('input', function () {
			state.width = parseInt(this.value) || 800;
			widthRange.value = state.width;
			updatePrice();
			updatePreview();
			updateRecap();
		});
		widthRange.addEventListener('input', function () {
			state.width = parseInt(this.value) || 800;
			widthInput.value = state.width;
			updatePrice();
			updatePreview();
			updateRecap();
		});
		heightInput.addEventListener('input', function () {
			state.height = parseInt(this.value) || 1000;
			heightRange.value = state.height;
			updatePrice();
			updatePreview();
			updateRecap();
		});
		heightRange.addEventListener('input', function () {
			state.height = parseInt(this.value) || 1000;
			heightInput.value = state.height;
			updatePrice();
			updatePreview();
			updateRecap();
		});
	}

	// ─── NAVIGATION ───
	function initNavigation() {
		document.querySelectorAll('.wwb-cfg__next-btn, .wwb-cfg__prev-btn').forEach(function (btn) {
			btn.addEventListener('click', function () {
				goToStep(parseInt(this.dataset.goto));
			});
		});

		document.querySelectorAll('.wwb-cfg__step-indicator').forEach(function (ind) {
			ind.addEventListener('click', function () {
				goToStep(parseInt(this.dataset.step));
			});
		});
	}

	function goToStep(step) {
		currentStep = step;
		document.querySelectorAll('.wwb-cfg__step').forEach(function (s) {
			s.classList.toggle('active', parseInt(s.dataset.step) === step);
		});
		document.querySelectorAll('.wwb-cfg__step-indicator').forEach(function (ind) {
			var s = parseInt(ind.dataset.step);
			ind.classList.toggle('active', s === step);
			ind.classList.toggle('completed', s < step);
		});
		// Scroll to top of configurator
		document.getElementById('wwb-configurator').scrollIntoView({ behavior: 'smooth', block: 'start' });
	}

	// ─── PRICE CALCULATION (client-side mirror) ───
	function calculatePrice() {
		var basePrices = cfg.config.base_prices;
		var supplements = cfg.config.supplements;

		var surface = (state.width / 1000) * (state.height / 1000);
		var basePerM2 = basePrices[state.type] || 300;
		var price = basePerM2 * surface;
		price = Math.max(price, 89);

		// Add supplements
		['dormant', 'vitrage', 'ouverture', 'coloris', 'ferrage', 'grille', 'volet'].forEach(function (key) {
			var val = state[key];
			if (val && supplements[val] !== undefined) {
				price += supplements[val];
			}
		});

		return Math.round(price * 100) / 100;
	}

	function updatePrice() {
		var price = calculatePrice();
		var formatted = price.toFixed(2).replace('.', ',');

		var priceEl = document.getElementById('wwb-cfg-price');
		if (priceEl) priceEl.textContent = formatted;

		var btnPrice = document.getElementById('wwb-cfg-btn-price');
		if (btnPrice) btnPrice.textContent = formatted + ' €';
	}

	// ─── SVG PREVIEW ───
	function updatePreview() {
		var svg = document.getElementById('wwb-cfg-svg');
		if (!svg) return;

		// Get color for frame
		var colorData = cfg.config.coloris[state.coloris];
		var frameColor = '#888';
		if (colorData) {
			var hex = colorData.hex;
			if (hex.startsWith('#')) frameColor = hex;
			else if (hex.startsWith('linear')) frameColor = '#666'; // bicolor fallback
		}
		if (state.coloris === 'blanc') frameColor = '#CCCCCC';

		var frame = document.getElementById('cfg-frame');
		if (frame) frame.setAttribute('stroke', frameColor);

		// Mullions based on type
		var mv = document.getElementById('cfg-mullion-v');
		var mv2 = document.getElementById('cfg-mullion-v2');
		var mv3 = document.getElementById('cfg-mullion-v3');
		var handle = document.getElementById('cfg-handle');

		// Reset
		[mv, mv2, mv3].forEach(function (el) { if (el) el.style.display = 'none'; });

		switch (state.type) {
			case '2-vantaux':
				if (mv) { mv.style.display = 'block'; mv.setAttribute('stroke', frameColor); }
				break;
			case '3-vantaux':
				if (mv2) { mv2.style.display = 'block'; mv2.setAttribute('stroke', frameColor); }
				if (mv3) { mv3.style.display = 'block'; mv3.setAttribute('stroke', frameColor); }
				break;
		}

		// Handle visibility
		if (handle) {
			handle.style.display = (state.ouverture === 'fixe') ? 'none' : 'block';
		}

		// Dimension labels
		var dimW = document.getElementById('cfg-dim-width');
		var dimH = document.getElementById('cfg-dim-height');
		if (dimW) dimW.textContent = state.width + ' mm';
		if (dimH) dimH.textContent = state.height + ' mm';

		// Vitrage tint
		var glass = document.getElementById('cfg-glass');
		if (glass) {
			switch (state.vitrage) {
				case 'depoli-4-16-4':
				case 'securite-depoli':
					glass.setAttribute('fill', '#e8e0d0');
					glass.setAttribute('opacity', '0.8');
					break;
				case 'phonique-6-16-4':
					glass.setAttribute('fill', '#c8dce8');
					glass.setAttribute('opacity', '0.7');
					break;
				default:
					glass.setAttribute('fill', '#d4e8f0');
					glass.setAttribute('opacity', '0.6');
			}
		}
	}

	// ─── RECAP ───
	function updateRecap() {
		var list = document.getElementById('wwb-cfg-recap-list');
		if (!list) return;

		var labels = {
			type: 'Type', width: 'Largeur', height: 'Hauteur',
			ouverture: 'Ouverture', dormant: 'Dormant', vitrage: 'Vitrage',
			coloris: 'Coloris', ferrage: 'Ferrage', grille: 'Grille', volet: 'Volet roulant'
		};

		var html = '';
		Object.keys(labels).forEach(function (key) {
			var val = state[key];
			if (!val) return;
			var display = val;
			if (key === 'width' || key === 'height') display = val + ' mm';
			html += '<div class="wwb-cfg__recap-row"><span>' + labels[key] + '</span><span>' + display + '</span></div>';
		});
		list.innerHTML = html;
	}

	// ─── ADD TO CART ───
	function initAddToCart() {
		var btn = document.getElementById('wwb-cfg-add-to-cart');
		if (!btn) return;

		btn.addEventListener('click', function () {
			btn.disabled = true;
			btn.textContent = 'Ajout en cours...';

			var formData = new FormData();
			formData.append('action', 'wwb_add_configured_product');
			formData.append('product_id', cfg.productId);
			Object.keys(state).forEach(function (key) {
				formData.append('config[' + key + ']', state[key]);
			});

			fetch(cfg.ajaxUrl, { method: 'POST', body: formData })
				.then(function (r) { return r.json(); })
				.then(function (data) {
					if (data.success) {
						btn.textContent = '✓ Ajouté au panier !';
						btn.classList.add('wwb-cfg__add-btn--success');
						// Apply fragments returned by the server (updates header cart count, mini-cart, etc.)
						if (data.data && data.data.fragments) {
							Object.keys(data.data.fragments).forEach(function (selector) {
								document.querySelectorAll(selector).forEach(function (el) {
									el.outerHTML = data.data.fragments[selector];
								});
							});
						}
						// Also trigger WC's own fragment refresh for any other listeners
						if (window.jQuery) {
							jQuery(document.body).trigger('added_to_cart', [
								data.data && data.data.fragments ? data.data.fragments : null,
								null,
								null
							]);
							jQuery(document.body).trigger('wc_fragment_refresh');
						}
						setTimeout(function () {
							btn.disabled = false;
							btn.innerHTML = 'Ajouter au panier';
							btn.classList.remove('wwb-cfg__add-btn--success');
						}, 2000);
					} else {
						btn.textContent = 'Erreur — Réessayer';
						btn.disabled = false;
					}
				})
				.catch(function () {
					btn.textContent = 'Erreur réseau — Réessayer';
					btn.disabled = false;
				});
		});
	}

})();
