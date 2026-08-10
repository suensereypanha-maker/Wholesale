/**
 * Wishlist JavaScript - AJAX Toggle & Count Badge Update
 */

document.addEventListener('DOMContentLoaded', function () {
    const wishlistBtns = document.querySelectorAll('.b2b-wishlist-btn');

    wishlistBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const productId = btn.getAttribute('data-product-id');
            const actionUrl = btn.getAttribute('data-url');

            fetch(actionUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showB2BToast(data.message, 'success');
                    const badge = document.getElementById('b2b-header-wishlist-badge');
                    if (badge && data.count !== undefined) {
                        badge.textContent = data.count;
                    }
                    btn.classList.add('text-danger');
                } else {
                    showB2BToast(data.message || 'Could not update wishlist.', 'danger');
                }
            })
            .catch(err => {
                console.error(err);
                showB2BToast('Error updating wishlist.', 'danger');
            });
        });
    });
});
