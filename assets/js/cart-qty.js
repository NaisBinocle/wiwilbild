/**
 * WWB — Cart quantity stepper
 * Injecte des boutons +/- autour de chaque input qty du panier + trigger update WC.
 */
(function () {
    function enhance() {
        const inputs = document.querySelectorAll('.wwb-cart-item__qty-input');
        inputs.forEach(function (input) {
            const parent = input.parentElement;
            if (!parent || parent.dataset.wwbEnhanced) return;
            parent.dataset.wwbEnhanced = '1';
            parent.classList.add('wwb-cart-item__qty-stepper');

            const min = parseInt(input.getAttribute('min')) || 0;
            const max = parseInt(input.getAttribute('max')) || 999;

            const minus = document.createElement('button');
            minus.type = 'button';
            minus.className = 'wwb-cart-item__qty-btn wwb-cart-item__qty-btn--minus';
            minus.setAttribute('aria-label', 'Diminuer la quantité');
            minus.textContent = '−';

            const plus = document.createElement('button');
            plus.type = 'button';
            plus.className = 'wwb-cart-item__qty-btn wwb-cart-item__qty-btn--plus';
            plus.setAttribute('aria-label', 'Augmenter la quantité');
            plus.textContent = '+';

            parent.insertBefore(minus, input);
            parent.appendChild(plus);

            function updateDisabled() {
                const v = parseInt(input.value) || 0;
                minus.disabled = v <= min;
                plus.disabled = v >= max;
            }

            function fire() {
                input.dispatchEvent(new Event('change', { bubbles: true }));
                // Trigger WC update button if present
                const form = input.closest('form.woocommerce-cart-form');
                if (form) {
                    const updateBtn = form.querySelector('[name="update_cart"], .wwb-cart__update-btn');
                    if (updateBtn) {
                        updateBtn.disabled = false;
                        // Debounce submit
                        clearTimeout(window.__wwbCartTO);
                        window.__wwbCartTO = setTimeout(function () {
                            if (typeof jQuery !== 'undefined') {
                                jQuery(document.body).trigger('wc_update_cart');
                            } else {
                                form.requestSubmit(updateBtn);
                            }
                        }, 600);
                    }
                }
                updateDisabled();
            }

            minus.addEventListener('click', function () {
                const v = parseInt(input.value) || 0;
                if (v > min) {
                    input.value = v - 1;
                    fire();
                }
            });
            plus.addEventListener('click', function () {
                const v = parseInt(input.value) || 0;
                if (v < max) {
                    input.value = v + 1;
                    fire();
                }
            });
            input.addEventListener('input', updateDisabled);
            updateDisabled();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', enhance);
    } else {
        enhance();
    }
    // Re-enhance after WC AJAX cart refresh
    if (typeof jQuery !== 'undefined') {
        jQuery(document.body).on('updated_cart_totals updated_wc_div', enhance);
    }
})();
