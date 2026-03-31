/**
 * WWB V2 — Product Image Hotspots
 * Vanilla JS — No jQuery dependency
 */
document.addEventListener('DOMContentLoaded', function () {
	// Wait for hotspots to be injected by the mu-plugin
	setTimeout(function () {
		var hotspots = document.querySelectorAll('.wwb-hotspots__point');
		if (!hotspots.length) return;

		hotspots.forEach(function (hotspot) {
			hotspot.addEventListener('click', function (e) {
				e.stopPropagation();

				var isActive = this.classList.contains('active');

				// Close all other hotspots
				hotspots.forEach(function (h) {
					h.classList.remove('active');
				});

				// Toggle clicked one
				if (!isActive) {
					this.classList.add('active');
				}
			});
		});

		// Close tooltips on click outside
		document.addEventListener('click', function (e) {
			if (!e.target.closest('.wwb-hotspots__point')) {
				hotspots.forEach(function (h) {
					h.classList.remove('active');
				});
			}
		});

		// Keyboard accessibility
		hotspots.forEach(function (hotspot) {
			hotspot.setAttribute('tabindex', '0');
			hotspot.setAttribute('role', 'button');

			hotspot.addEventListener('keydown', function (e) {
				if (e.key === 'Enter' || e.key === ' ') {
					e.preventDefault();
					this.click();
				}
				if (e.key === 'Escape') {
					this.classList.remove('active');
				}
			});
		});
	}, 500);
});
