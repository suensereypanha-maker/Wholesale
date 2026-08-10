<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenuOffcanvas" aria-labelledby="mobileMenuLabel">
    <div class="offcanvas-header bg-dark text-white">
        <h5 class="offcanvas-title font-weight-800" id="mobileMenuLabel">
            <i class="fas fa-microchip text-success me-2"></i> Apex IT Wholesale
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="p-3 bg-light border-bottom">
            <form action="{{ route('frontend.products.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search SKU or name...">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>

        <div class="list-group list-group-flush font-weight-600">
            <a href="{{ route('frontend.home') }}" class="list-group-item list-group-item-action py-3">
                <i class="fas fa-home me-2 text-primary"></i> Home
            </a>
            <a href="{{ route('frontend.products.index') }}" class="list-group-item list-group-item-action py-3">
                <i class="fas fa-boxes me-2 text-info"></i> All Products Catalog
            </a>
            <a href="{{ route('frontend.categories.index') }}" class="list-group-item list-group-item-action py-3">
                <i class="fas fa-layer-group me-2 text-success"></i> Categories
            </a>
            <a href="{{ route('frontend.quotes.create') }}" class="list-group-item list-group-item-action py-3">
                <i class="fas fa-file-invoice-dollar me-2 text-warning"></i> Request Wholesale Quote
            </a>
            <a href="{{ route('frontend.cart.index') }}" class="list-group-item list-group-item-action py-3">
                <i class="fas fa-shopping-cart me-2 text-indigo"></i> Wholesale Cart
            </a>
            <a href="{{ route('frontend.orders.index') }}" class="list-group-item list-group-item-action py-3">
                <i class="fas fa-box-open me-2 text-teal"></i> Order History
            </a>
            <a href="{{ route('frontend.account') }}" class="list-group-item list-group-item-action py-3">
                <i class="fas fa-user-circle me-2 text-danger"></i> Customer Account
            </a>
            <a href="{{ route('frontend.about') }}" class="list-group-item list-group-item-action py-3">
                <i class="fas fa-building me-2 text-secondary"></i> About Us
            </a>
            <a href="{{ route('frontend.contact') }}" class="list-group-item list-group-item-action py-3">
                <i class="fas fa-headset me-2 text-dark"></i> Contact Support
            </a>
        </div>
    </div>
</div>
