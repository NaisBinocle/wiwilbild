/**
 * WWB V2 — Calculateur surface carrelage (vanilla JS).
 * Lie surface (m²) ↔ quantité (boîtes) + majoration 10% + total estimé + sync WC qty.
 */
(function () {
	'use strict';

	var calc = document.querySelector('.wwb-calc');
	if (!calc) return;

	var surfaceParBoite = parseFloat(calc.dataset.surfaceParBoite) || 0;
	var priceM2         = parseFloat(calc.dataset.priceM2) || 0;

	var inputM2    = calc.querySelector('[data-wwb-calc="m2"]');
	var inputBoxes = calc.querySelector('[data-wwb-calc="boxes"]');
	var margin     = calc.querySelector('[data-wwb-calc="margin"]');
	var totalEl    = calc.querySelector('[data-wwb-calc="total"]');
	var hintEl     = calc.querySelector('[data-wwb-calc="hint"]');

	var isSyncing = false;

	function formatEUR(val) {
		return (Math.round(val * 100) / 100)
			.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
	}

	function formatNum(val, dec) {
		return (Math.round(val * Math.pow(10, dec)) / Math.pow(10, dec))
			.toLocaleString('fr-FR', { minimumFractionDigits: dec, maximumFractionDigits: dec });
	}

	function syncWcQty(boxes) {
		var wcQty = document.querySelector('.woocommerce-variation-add-to-cart .input-text.qty, form.cart .input-text.qty');
		if (wcQty) {
			wcQty.value = boxes;
			wcQty.dispatchEvent(new Event('change', { bubbles: true }));
		}
	}

	function render(boxes, surfaceCible) {
		if (!surfaceParBoite) {
			totalEl.textContent = '—';
			hintEl.textContent  = 'Dimensions du produit non renseignées.';
			return;
		}
		var surfaceCouverte = boxes * surfaceParBoite;
		var total = surfaceCouverte * priceM2;
		totalEl.textContent = formatEUR(total);
		hintEl.textContent  =
			boxes + ' boîte' + (boxes > 1 ? 's' : '') +
			' · ' + formatNum(surfaceCouverte, 2) + ' m² couverts' +
			' · ' + formatEUR(priceM2) + ' / m²';
		syncWcQty(boxes);
	}

	function fromSurface() {
		if (isSyncing) return;
		var m2 = parseFloat(inputM2.value) || 0;
		if (margin.checked) m2 *= 1.10;
		var boxes = surfaceParBoite > 0 ? Math.max(1, Math.ceil(m2 / surfaceParBoite)) : 1;
		isSyncing = true;
		inputBoxes.value = boxes;
		isSyncing = false;
		render(boxes);
	}

	function fromBoxes() {
		if (isSyncing) return;
		var boxes = parseInt(inputBoxes.value, 10) || 1;
		if (boxes < 1) boxes = 1;
		isSyncing = true;
		inputBoxes.value = boxes;
		var raw = boxes * surfaceParBoite;
		// Afficher la surface brute sans majoration, mais ne pas écraser si user a saisi avec majoration active
		inputM2.value = formatNum(margin.checked ? raw / 1.10 : raw, 1).replace(/\s/g, '').replace(',', '.');
		isSyncing = false;
		render(boxes);
	}

	inputM2.addEventListener('input', fromSurface);
	inputBoxes.addEventListener('input', fromBoxes);
	margin.addEventListener('change', fromSurface);

	// WooCommerce variation change → recalibrer surface/boîte depuis dimensions variation
	function updateFromVariation(variation) {
		if (!variation || !variation.dimensions) return;
		var length = parseFloat(variation.dimensions.length) || 0;
		var width  = parseFloat(variation.dimensions.width)  || 0;
		if (length > 0 && width > 0) {
			var pieces   = parseInt(calc.dataset.piecesParBoite, 10) || 12;
			var unitaire = (length / 100) * (width / 100);
			surfaceParBoite = unitaire * pieces;
			calc.dataset.surfaceParBoite = surfaceParBoite;
		}
		if (variation.display_price) {
			priceM2 = parseFloat(variation.display_price);
			calc.dataset.priceM2 = priceM2;
		}
		fromSurface();
	}

	var form = document.querySelector('form.variations_form');
	if (form) {
		form.addEventListener('found_variation', function (e) {
			updateFromVariation((e.detail && e.detail.variation) || e.detail);
		});
	}
	if (window.jQuery) {
		jQuery('form.variations_form').on('found_variation', function (event, variation) {
			updateFromVariation(variation);
		});
	}

	// Initial render
	fromSurface();
})();
