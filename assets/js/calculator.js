/**
 * WWB V2 — Surface Calculator (Carrelage)
 * Vanilla JS — No jQuery dependency
 */
document.addEventListener('DOMContentLoaded', function () {
	var calculator = document.getElementById('surface-calculator');
	if (!calculator) return;

	var surfaceUnitaire = parseFloat(calculator.dataset.surfaceUnitaire) || 0;

	var surfaceInput = document.getElementById('surface-input');
	var addMargin = document.getElementById('add-margin');
	var boxQuantity = document.getElementById('box-quantity');
	var surfacePerBox = document.getElementById('surface-per-box');
	var surfaceResult = document.getElementById('surface-result');
	var boxCount = document.getElementById('box-count');
	var btnPlus = calculator.querySelector('.wwb-qty__btn--plus');
	var btnMinus = calculator.querySelector('.wwb-qty__btn--minus');

	function updateSurfaceUnitaire(variation) {
		var length = parseFloat(variation?.dimensions?.length || 0);
		var width = parseFloat(variation?.dimensions?.width || 0);
		if (length > 0 && width > 0) {
			surfaceUnitaire = (length / 100) * (width / 100);
			calculator.dataset.surfaceUnitaire = surfaceUnitaire;
			if (surfacePerBox) {
				surfacePerBox.textContent = surfaceUnitaire.toFixed(2).replace('.', ',');
			}
		}
	}

	function calculateBoxesFromSurface() {
		var surface = parseFloat(surfaceInput.value) || 0;
		if (addMargin && addMargin.checked) {
			surface = surface * 1.10;
		}
		if (surface > 0 && surfaceUnitaire > 0) {
			var boxes = Math.ceil(surface / surfaceUnitaire);
			boxQuantity.value = boxes;
			updateResults();
		}
	}

	function updateResults() {
		var boxes = parseInt(boxQuantity.value) || 1;
		var totalSurface = (boxes * surfaceUnitaire).toFixed(2);

		if (surfaceResult) surfaceResult.textContent = totalSurface.replace('.', ',');
		if (boxCount) boxCount.textContent = boxes;

		// Sync WooCommerce quantity
		var wcQty = document.querySelector('.input-text.qty');
		if (wcQty) {
			wcQty.value = boxes;
			wcQty.dispatchEvent(new Event('change', { bubbles: true }));
		}
	}

	// Events
	if (surfaceInput) {
		surfaceInput.addEventListener('input', calculateBoxesFromSurface);
	}

	if (addMargin) {
		addMargin.addEventListener('change', calculateBoxesFromSurface);
	}

	if (btnPlus) {
		btnPlus.addEventListener('click', function () {
			var current = parseInt(boxQuantity.value) || 1;
			boxQuantity.value = current + 1;
			if (surfaceInput) surfaceInput.value = '';
			updateResults();
		});
	}

	if (btnMinus) {
		btnMinus.addEventListener('click', function () {
			var current = parseInt(boxQuantity.value) || 1;
			if (current > 1) {
				boxQuantity.value = current - 1;
				if (surfaceInput) surfaceInput.value = '';
				updateResults();
			}
		});
	}

	// WooCommerce variation change
	var variationsForm = document.querySelector('form.variations_form');
	if (variationsForm) {
		variationsForm.addEventListener('found_variation', function (e) {
			updateSurfaceUnitaire(e.detail || e.originalEvent?.detail);
			updateResults();
		});
	}

	// jQuery bridge for WooCommerce events (WC uses jQuery internally)
	if (window.jQuery) {
		jQuery('form.variations_form').on('found_variation', function (event, variation) {
			updateSurfaceUnitaire(variation);
			updateResults();
		});
	}

	updateResults();
});
