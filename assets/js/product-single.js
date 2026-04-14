/**
 * WWB V2 — Product Single Page (Menuiserie)
 *
 * Handles: size button selection, vitrage pills, custom dimensions toggle,
 * color label update, and WooCommerce variation sync.
 */
document.addEventListener('DOMContentLoaded', function () {

	// ── WC variation form requires a .variations element containing the
	//    attribute selects to initialize (see add-to-cart-variation.js).
	//    Our custom template renders the hidden selects inside pills sections,
	//    so we wrap them in a .variations container BEFORE WC init runs.
	(function ensureVariationsWrapper() {
		var form = document.querySelector('form.variations_form');
		if (!form) return;
		if (form.querySelector('.variations')) return; // already present
		var selects = form.querySelectorAll('select.wwb-hidden-select');
		if (!selects.length) return;
		var wrap = document.createElement('div');
		wrap.className = 'variations';
		wrap.style.display = 'none';
		// Move clones of the selects into the wrapper (keep originals for pills sync)
		selects.forEach(function (s) {
			var clone = s.cloneNode(true);
			clone.removeAttribute('id');
			clone.classList.remove('wwb-hidden-select');
			clone.setAttribute('data-wwb-mirror', '1');
			wrap.appendChild(clone);
			// Mirror: when original changes (native OR jQuery event), update clone + fire change
			var sync = function () {
				clone.value = s.value;
				if (window.jQuery) {
					jQuery(clone).trigger('change');
				} else {
					clone.dispatchEvent(new Event('change', { bubbles: true }));
				}
			};
			s.addEventListener('change', sync);
			if (window.jQuery) {
				jQuery(s).on('change', sync);
			}
		});
		form.insertBefore(wrap, form.firstChild);
		// Re-init WC variation form now that .variations exists
		if (window.jQuery && typeof jQuery.fn.wc_variation_form === 'function') {
			jQuery(form).wc_variation_form();
			// WC init uses a 100ms setTimeout internally; wait for it then trigger change
			setTimeout(function () {
				jQuery(wrap).find('select').each(function () {
					if (this.value) jQuery(this).trigger('change');
				});
			}, 150);
		}
	})();

	// ── Helpers ──

	function triggerChange(select) {
		if (window.jQuery) {
			jQuery(select).trigger('change');
		} else {
			select.dispatchEvent(new Event('change', { bubbles: true }));
		}
	}

	function findSelect(container) {
		if (!container) return null;
		// Walk up to find the config section, then find hidden select
		var section = container.closest('.wwb-single__config-section');
		if (section) return section.querySelector('select.wwb-hidden-select');
		// Fallback
		var td = container.closest('td');
		return td ? td.querySelector('select') : null;
	}

	// ── Size buttons ──

	var sizesContainer = document.querySelector('.wwb-single__sizes');
	if (sizesContainer) {
		// Auto-select first size on load
		var firstSize = sizesContainer.querySelector('.wwb-single__size-btn');
		if (firstSize) {
			var select = findSelect(sizesContainer);
			if (select) {
				select.value = firstSize.getAttribute('data-value');
				triggerChange(select);
			}
		}

		sizesContainer.addEventListener('click', function (e) {
			var btn = e.target.closest('.wwb-single__size-btn');
			if (!btn) return;

			// Update active state
			sizesContainer.querySelectorAll('.wwb-single__size-btn').forEach(function (b) {
				b.classList.remove('wwb-single__size-btn--active');
				b.setAttribute('aria-pressed', 'false');
			});
			btn.classList.add('wwb-single__size-btn--active');
			btn.setAttribute('aria-pressed', 'true');

			// Sync hidden select
			var select = findSelect(sizesContainer);
			if (select) {
				select.value = btn.getAttribute('data-value');
				triggerChange(select);
			}

			// Close custom dims if open
			var customBody = document.querySelector('.wwb-single__custom-dims-body');
			var customToggle = document.querySelector('.wwb-single__custom-dims-toggle');
			if (customBody && !customBody.hidden) {
				customBody.hidden = true;
				if (customToggle) customToggle.setAttribute('aria-expanded', 'false');
			}
		});
	}

	// ── Custom dimensions toggle ──

	var customToggle = document.querySelector('.wwb-single__custom-dims-toggle');
	if (customToggle) {
		customToggle.addEventListener('click', function () {
			var body = document.querySelector('.wwb-single__custom-dims-body');
			if (!body) return;
			var isOpen = !body.hidden;
			body.hidden = isOpen;
			this.setAttribute('aria-expanded', !isOpen ? 'true' : 'false');

			// If opening custom dims, deselect size buttons
			if (!isOpen && sizesContainer) {
				sizesContainer.querySelectorAll('.wwb-single__size-btn').forEach(function (b) {
					b.classList.remove('wwb-single__size-btn--active');
					b.setAttribute('aria-pressed', 'false');
				});
			}
		});

		// Keyboard support
		customToggle.addEventListener('keydown', function (e) {
			if (e.key === 'Enter' || e.key === ' ') {
				e.preventDefault();
				this.click();
			}
		});
	}

	// ── Vitrage pills ──

	var vitrageContainer = document.querySelector('.wwb-single__vitrage');
	if (vitrageContainer) {
		// Auto-select first vitrage on load
		var firstVitrage = vitrageContainer.querySelector('.wwb-single__vitrage-btn');
		if (firstVitrage) {
			var select = findSelect(vitrageContainer);
			if (select) {
				select.value = firstVitrage.getAttribute('data-value');
				triggerChange(select);
			}
		}

		vitrageContainer.addEventListener('click', function (e) {
			var btn = e.target.closest('.wwb-single__vitrage-btn');
			if (!btn) return;

			vitrageContainer.querySelectorAll('.wwb-single__vitrage-btn').forEach(function (b) {
				b.classList.remove('wwb-single__vitrage-btn--active');
				b.setAttribute('aria-pressed', 'false');
			});
			btn.classList.add('wwb-single__vitrage-btn--active');
			btn.setAttribute('aria-pressed', 'true');

			var select = findSelect(vitrageContainer);
			if (select) {
				select.value = btn.getAttribute('data-value');
				triggerChange(select);
			}
		});
	}

	// ── Color swatches (menuiserie context) ──

	document.querySelectorAll('.wwb-single__config-section .wwb-color-swatches__swatch').forEach(function (swatch) {
		swatch.addEventListener('click', function () {
			var value = this.getAttribute('data-value');
			var section = this.closest('.wwb-single__config-section');
			var swatchesContainer = this.closest('.wwb-color-swatches');
			var group = swatchesContainer ? swatchesContainer.getAttribute('data-config-group') : null;

			// Coloris libres (non-variation) : update hidden inputs
			if (group === 'wwb_coloris') {
				var valueInput = document.getElementById('wwb_coloris_value');
				var labelInput = document.getElementById('wwb_coloris_label');
				var labelText = this.getAttribute('data-label') || this.getAttribute('title') || value;
				if (valueInput) valueInput.value = value;
				if (labelInput) labelInput.value = labelText;
			} else {
				// Coloris en variation WC : sync hidden select
				var select = section ? section.querySelector('select.wwb-hidden-select') : null;
				if (select) {
					select.value = value;
					triggerChange(select);
				}
			}

			swatchesContainer
				.querySelectorAll('.wwb-color-swatches__swatch')
				.forEach(function (s) { s.classList.remove('selected'); s.classList.remove('is-active'); s.setAttribute('aria-pressed', 'false'); });
			this.classList.add('selected');
			this.classList.add('is-active');
			this.setAttribute('aria-pressed', 'true');

			// Update visible label (inline)
			var label = document.getElementById('wwb-color-label');
			if (label) {
				label.textContent = this.getAttribute('data-label') || this.getAttribute('title') || value;
			}
		});
	});

	// ── Price info update on variation found ──

	if (window.jQuery) {
		jQuery('form.variations_form').on('found_variation', function (event, variation) {
			// Add primary CTA styling to the add to cart button
			var addBtn = document.querySelector('.single_add_to_cart_button');
			if (addBtn) {
				addBtn.classList.add('wwb-single__cta', 'wwb-single__cta--primary');
			}
		});
	}
});
