/**
 * B2B Wholesale Portal - Global Core JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {
    // Enable Bootstrap Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-hide alert toasts after 4 seconds
    const toastElements = document.querySelectorAll('.toast');
    toastElements.forEach(function (toastEl) {
        const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
        toast.show();
    });
});

/**
 * Global Toast Helper
 */
function showB2BToast(message, type = 'success') {
    let container = document.getElementById('b2b-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'b2b-toast-container';
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '1100';
        document.body.appendChild(container);
    }

    const bgClass = type === 'success' ? 'bg-success' : (type === 'danger' || type === 'error' ? 'bg-danger' : 'bg-primary');

    const toastHtml = `
        <div class="toast align-items-center text-white ${bgClass} border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body font-weight-bold">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

    const wrapper = document.createElement('div');
    wrapper.innerHTML = toastHtml.trim();
    const toastNode = wrapper.firstChild;

    container.appendChild(toastNode);
    const bsToast = new bootstrap.Toast(toastNode, { delay: 3500 });
    bsToast.show();

    toastNode.addEventListener('hidden.bs.toast', function () {
        toastNode.remove();
    });
}
