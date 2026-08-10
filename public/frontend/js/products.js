/**
 * Product Detail Page - Real-time Wholesale Tier Price Calculator
 */

document.addEventListener('DOMContentLoaded', function () {
    const qtyInput = document.getElementById('b2b-qty-input');
    const unitPriceEl = document.getElementById('b2b-unit-price');
    const subtotalEl = document.getElementById('b2b-subtotal');
    const savingsEl = document.getElementById('b2b-savings');
    const moq = parseInt(qtyInput ? qtyInput.getAttribute('data-moq') || 1 : 1);

    if (qtyInput && window.productWholesaleTiers) {

        function calculatePrices() {
            let qty = parseInt(qtyInput.value) || 1;

            if (qty < moq) {
                qty = moq;
                qtyInput.value = moq;
            }

            const basePrice = parseFloat(qtyInput.getAttribute('data-base-price')) || 0;
            const tiers = window.productWholesaleTiers;
            let appliedPrice = basePrice;
            let activeTierIndex = -1;

            tiers.forEach((tier, index) => {
                const min = tier.minQty;
                const max = tier.maxQty;

                if (qty >= min && (max === null || max === undefined || qty <= max)) {
                    appliedPrice = parseFloat(tier.price);
                    activeTierIndex = index;
                }
            });

            // Fallback for higher quantities
            if (activeTierIndex === -1 && tiers.length > 0) {
                const lastTier = tiers[tiers.length - 1];
                if (qty >= lastTier.minQty) {
                    appliedPrice = parseFloat(lastTier.price);
                    activeTierIndex = tiers.length - 1;
                }
            }

            const subtotal = appliedPrice * qty;
            const savings = (basePrice - appliedPrice) * qty;

            if (unitPriceEl) unitPriceEl.textContent = '$' + appliedPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            if (subtotalEl) subtotalEl.textContent = '$' + subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            if (savingsEl) {
                if (savings > 0) {
                    savingsEl.textContent = 'Tier Savings: $' + savings.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    savingsEl.classList.remove('d-none');
                } else {
                    savingsEl.classList.add('d-none');
                }
            }

            // Highlight Active Tier in Pricing Matrix
            const tierRows = document.querySelectorAll('.b2b-tier-row');
            tierRows.forEach((row, idx) => {
                if (idx === activeTierIndex) {
                    row.classList.add('active-tier');
                } else {
                    row.classList.remove('active-tier');
                }
            });
        }

        qtyInput.addEventListener('input', calculatePrices);
        qtyInput.addEventListener('change', calculatePrices);

        const btnPlus = document.getElementById('btn-qty-plus');
        const btnMinus = document.getElementById('btn-qty-minus');

        if (btnPlus) {
            btnPlus.addEventListener('click', function () {
                qtyInput.value = (parseInt(qtyInput.value) || 1) + 1;
                calculatePrices();
            });
        }

        if (btnMinus) {
            btnMinus.addEventListener('click', function () {
                const current = parseInt(qtyInput.value) || 1;
                if (current > moq) {
                    qtyInput.value = current - 1;
                    calculatePrices();
                } else {
                    showB2BToast(`Minimum order requirement is ${moq} units.`, 'warning');
                }
            });
        }

        // Run initial calculation
        calculatePrices();
    }
});
