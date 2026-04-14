/**
 * WWB — Quantity stepper (panier + fiche produit)
 * Injecte des boutons +/- autour de chaque input qty et gère les triggers WC.
 */
(function () {
    function enhance() {
        const inputs = document.querySelectorAll('.wwb-cart-item__qty-input, .wwb-single .variations_form .quantity input.qty, .wwb-single .variations_form .quantity input[type="number"]');
        inputs.forEach(function (input) {
            const parent = input.parentElement;
            if (!parent || parent.dataset.wwbEnhanced) return;
            parent.dataset.wwbEnhanced = '1';
            const isCart = input.classList.contains('wwb-cart-item__qty-input');
            const btnCls = isCart ? 'wwb-cart-item__qty-btn' : 'wwb-single__qty-btn';
            parent.classList.add(isCart ? 'wwb-cart-item__qty-stepper' : 'wwb-single__qty-stepper');

            const min = parseInt(input.getAttribute('min')) || (isCart ? 0 : 1);
            const max = parseInt(input.getAttribute('max')) || 999;

            const minus = document.createElement('button');
            minus.type = 'button';
            minus.className = btnCls + ' ' + btnCls + '--minus';
            minus.setAttribute('aria-label', 'Diminuer la quantité');
            minus.textContent = '−';

            const plus = document.createElement('button');
            plus.type = 'button';
            plus.className = btnCls + ' ' + btnCls + '--plus';
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
                input.dispatchEvent(new Event('input', { bubbles: true }));
                // Trigger WC update button if present (cart only)
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
