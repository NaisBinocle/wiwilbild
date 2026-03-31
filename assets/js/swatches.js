/**
 * WWB V2 — Color & Attribute Swatches
 * Vanilla JS — No jQuery dependency
 */
document.addEventListener('DOMContentLoaded', function () {
	// Color swatches → sync hidden <select>
	document.querySelectorAll('.wwb-color-swatches__swatch').forEach(function (swatch) {
		swatch.addEventListener('click', function () {
			var value = this.getAttribute('data-value');
			var container = this.closest('td') || this.closest('.value');
			var select = container ? container.querySelector('select') : null;

			if (select) {
				select.value = value;
				select.dispatchEvent(new Event('change', { bubbles: true }));
			}

			// Update selection state
			this.closest('.wwb-color-swatches')
				.querySelectorAll('.wwb-color-swatches__swatch')
				.forEach(function (s) { s.classList.remove('selected'); });
			this.classList.add('selected');
		});
	});

	// Attribute swatches (dimensions, etc.) → sync hidden <select>
	document.querySelectorAll('.wwb-attribute-swatches__swatch').forEach(function (swatch) {
		swatch.addEventListener('click', function () {
			var value = this.getAttribute('data-value');
			var container = this.closest('td') || this.closest('.value');
			var select = container ? container.querySelector('select') : null;

			if (select) {
				select.value = value;
				select.dispatchEvent(new Event('change', { bubbles: true }));
			}

			this.closest('.wwb-attribute-swatches')
				.querySelectorAll('.wwb-attribute-swatches__swatch')
				.forEach(function (s) { s.classList.remove('selected'); });
			this.classList.add('selected');
		});
	});

	// Prix /m² pour les variations (catégorie carrelage)
	function ajouterSuffixeM2() {
		var body = document.body.className;
		if (!body.includes('product-cat-carrelage')) return;

		document.querySelectorAll('.woocommerce-variation-price .woocommerce-Price-amount.amount').forEach(function (el) {
			if (!el.innerHTML.includes('/m²')) {
				el.innerHTML += ' /m²';
			}
		});
	}

	ajouterSuffixeM2();

	// Re-apply after variation selection
	var variationsForm = document.querySelector('form.variations_form');
	if (variationsForm) {
		variationsForm.addEventListener('found_variation', function () {
			ajouterSuffixeM2();
		});
	}
});
