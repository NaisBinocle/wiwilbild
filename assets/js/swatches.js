/**
 * WWB V2 — Color & Attribute Swatches
 * Uses jQuery trigger for WooCommerce compatibility (WC listens via jQuery events)
 */
document.addEventListener('DOMContentLoaded', function () {

	// Hide native selects (WC needs them in DOM but not visible)
	document.querySelectorAll('select.wwb-hidden-select').forEach(function(s) {
		s.style.cssText = 'position:absolute!important;width:1px!important;height:1px!important;overflow:hidden!important;clip:rect(0,0,0,0)!important;opacity:0!important;pointer-events:none!important;';
	});

	// Helper: trigger change on select (jQuery for WC, vanilla fallback)
	function triggerChange(select) {
		if (window.jQuery) {
			jQuery(select).trigger('change');
		} else {
			select.dispatchEvent(new Event('change', { bubbles: true }));
		}
	}

	// Color swatches → sync hidden <select>
	document.querySelectorAll('.wwb-color-swatches__swatch').forEach(function (swatch) {
		swatch.addEventListener('click', function () {
			var value = this.getAttribute('data-value');
			var container = this.closest('.wwb-variations__group') || this.closest('td') || this.closest('.value');
			var select = container ? container.querySelector('select') : null;

			if (select) {
				select.value = value;
				triggerChange(select);
			}

			// Update selection state
			this.closest('.wwb-color-swatches')
				.querySelectorAll('.wwb-color-swatches__swatch')
				.forEach(function (s) { s.classList.remove('selected'); });
			this.classList.add('selected');
		});
	});

	// Attribute swatches (dimensions, vitrage, etc.) → sync hidden <select>
	document.querySelectorAll('.wwb-attribute-swatches__swatch').forEach(function (swatch) {
		swatch.addEventListener('click', function () {
			var value = this.getAttribute('data-value');
			var container = this.closest('.wwb-variations__group') || this.closest('td') || this.closest('.value');
			var select = container ? container.querySelector('select') : null;

			if (select) {
				select.value = value;
				triggerChange(select);
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
	if (window.jQuery) {
		jQuery('form.variations_form').on('found_variation', function () {
			ajouterSuffixeM2();
		});
	}
});
