/**
 * Cart JavaScript - AJAX Cart Actions & Header Count Sync
 */

document.addEventListener('DOMContentLoaded', function () {
    // Add to Cart AJAX forms
    const addCartForms = document.querySelectorAll('.b2b-add-cart-form');

    addCartForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(form);
            const actionUrl = form.getAttribute('action');

            fetch(actionUrl, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showB2BToast(data.message, 'success');
                    updateCartBadge(data.cart_count);
                } else {
                    showB2BToast(data.message || 'Error adding to cart.', 'danger');
                }
            })
            .catch(err => {
                console.error(err);
                showB2BToast('Could not process request.', 'danger');
            });
        });
    });
});

function updateCartBadge(count) {
    const badge = document.getElementById('b2b-header-cart-badge');
    if (badge && count !== undefined) {
        badge.textContent = count;
    }
}
