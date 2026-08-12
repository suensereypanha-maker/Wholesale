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

    // Initialize Product Sliders
    initProductSliders();

    // Initialize Header Live Auto-complete Search
    initHeaderLiveSearch();
});

/**
 * Global Product Carousel / Slider Controller
 */
function initProductSliders() {
    const sliderContainers = document.querySelectorAll('.b2b-slider-container');

    sliderContainers.forEach(container => {
        const track = container.querySelector('.b2b-slider-track');
        const prevBtn = container.querySelector('.b2b-slider-prev');
        const nextBtn = container.querySelector('.b2b-slider-next');

        if (!track) return;

        const updateControls = () => {
            const scrollLeft = track.scrollLeft;
            const maxScrollLeft = track.scrollWidth - track.clientWidth;

            if (prevBtn) prevBtn.disabled = scrollLeft <= 4;
            if (nextBtn) nextBtn.disabled = scrollLeft >= maxScrollLeft - 6;

            if (scrollLeft >= maxScrollLeft - 8 || maxScrollLeft <= 0) {
                container.classList.add('at-end');
            } else {
                container.classList.remove('at-end');
            }
        };

        const getScrollDistance = () => {
            const firstItem = track.querySelector('.b2b-slider-item');
            if (firstItem) {
                const style = window.getComputedStyle(track);
                const gap = parseFloat(style.gap) || 20;
                return (firstItem.offsetWidth + gap) * 2;
            }
            return track.clientWidth * 0.75;
        };

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                track.scrollBy({ left: -getScrollDistance(), behavior: 'smooth' });
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                track.scrollBy({ left: getScrollDistance(), behavior: 'smooth' });
            });
        }

        // Mouse Drag to Scroll
        let isMouseDown = false;
        let startX, scrollLeftPos;

        track.addEventListener('mousedown', (e) => {
            isMouseDown = true;
            track.classList.add('active');
            startX = e.pageX - track.offsetLeft;
            scrollLeftPos = track.scrollLeft;
        });

        track.addEventListener('mouseleave', () => {
            isMouseDown = false;
            track.classList.remove('active');
        });

        track.addEventListener('mouseup', () => {
            isMouseDown = false;
            track.classList.remove('active');
        });

        track.addEventListener('mousemove', (e) => {
            if (!isMouseDown) return;
            e.preventDefault();
            const x = e.pageX - track.offsetLeft;
            const walk = (x - startX) * 1.5;
            track.scrollLeft = scrollLeftPos - walk;
        });

        track.addEventListener('scroll', updateControls);
        window.addEventListener('resize', updateControls);
        updateControls();
    });
}

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

/**
 * Global Header Live Search Suggestions Handler
 */
function initHeaderLiveSearch() {
    const input = document.getElementById('b2bHeaderSearchInput');
    const container = document.getElementById('b2bSearchSuggestions');

    if (!input || !container) return;

    let debounceTimer;

    input.addEventListener('input', function () {
        const query = this.value.trim();

        clearTimeout(debounceTimer);

        if (query.length < 2) {
            container.classList.add('d-none');
            container.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`/products/api/search?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    if (!data || data.length === 0) {
                        container.innerHTML = `
                            <div class="p-3 text-center text-muted fs-7">
                                <i class="fas fa-search-minus me-1"></i> No matching products found for "<strong>${escapeHtml(query)}</strong>"
                            </div>
                        `;
                        container.classList.remove('d-none');
                        return;
                    }

                    let html = '';
                    data.forEach(item => {
                        html += `
                            <a href="${item.url}" class="b2b-search-item">
                                <img src="${item.image}" alt="" class="b2b-search-img">
                                <div class="flex-grow-1 min-w-0">
                                    <div class="font-weight-700 text-dark fs-7 text-truncate">${escapeHtml(item.name)}</div>
                                    <div class="fs-8 text-muted">SKU: <span class="font-monospace">${escapeHtml(item.sku)}</span> | ${escapeHtml(item.brand)}</div>
                                </div>
                                <div class="font-weight-800 text-emerald text-nowrap fs-7">${item.price}</div>
                            </a>
                        `;
                    });

                    html += `
                        <a href="/products?search=${encodeURIComponent(query)}" class="d-block p-2 text-center bg-light text-primary font-weight-700 fs-7 text-decoration-none border-top">
                            View all search results for "${escapeHtml(query)}" <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    `;

                    container.innerHTML = html;
                    container.classList.remove('d-none');
                })
                .catch(() => {
                    container.classList.add('d-none');
                });
        }, 220);
    });

    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !container.contains(e.target)) {
            container.classList.add('d-none');
        }
    });

    input.addEventListener('focus', function () {
        if (container.children.length > 0 && this.value.trim().length >= 2) {
            container.classList.remove('d-none');
        }
    });
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>'"]/g, 
        tag => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            "'": '&#39;',
            '"': '&quot;'
        }[tag] || tag)
    );
}
