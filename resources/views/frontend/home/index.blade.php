@extends('frontend.layouts.app')

@section('title', 'B2B Wholesale Computers & IT Hardware Equipment Distributor')

@section('content')

<!-- 1. Hero Section -->
<section class="b2b-hero mb-5">
    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="b2b-hero-badge">
                    <i class="fas fa-building me-1"></i> Authorized Corporate Commercial Distributor
                </span>
                <h1 class="display-4 font-weight-800 text-white mb-3">
                    Wholesale Computers & IT Equipment
                </h1>
                <p class="lead text-light mb-4 opacity-90">
                    Reliable technology products, workstation hardware, servers, and components for businesses, resellers, schools, and enterprise organizations.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="{{ route('frontend.products.index') }}" class="btn b2b-btn-accent btn-lg font-weight-700 shadow-lg px-4">
                        <i class="fas fa-boxes me-2"></i> Browse All Products
                    </a>
                    <a href="{{ route('frontend.quotes.create') }}" class="btn btn-outline-light btn-lg font-weight-700 px-4">
                        <i class="fas fa-file-signature me-2"></i> Request Wholesale Quote
                    </a>
                </div>
                <div class="d-flex align-items-center gap-4 text-light fs-7 opacity-80 pt-2 border-top border-secondary">
                    <span><i class="fas fa-check-circle text-success me-1"></i> Tiered Volume Pricing</span>
                    <span><i class="fas fa-check-circle text-success me-1"></i> Official Manufacturer Warranty</span>
                    <span><i class="fas fa-check-circle text-success me-1"></i> Net 30/60 Days Credit</span>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center">
                <img src="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=800&auto=format&fit=crop&q=80" alt="B2B Hardware Warehouse" class="img-fluid rounded-4 shadow-lg border border-secondary" style="max-height: 420px; object-fit: cover;">
            </div>
        </div>
    </div>
</section>

<div class="container">

    <!-- 2. Product Categories -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="text-uppercase text-emerald font-weight-800 fs-7 tracking-wider">Product Categories</span>
                <h2 class="h3 font-weight-800 mb-0">Browse Hardware by Category</h2>
            </div>
            <a href="{{ route('frontend.categories.index') }}" class="btn btn-link text-emerald font-weight-700 text-decoration-none">
                View All Categories <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-3">
            @foreach(array_slice($categories, 0, 12) as $cat)
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <x-frontend.category-card :category="$cat" />
                </div>
            @endforeach
        </div>
    </section>

    <!-- 3. All Products Catalog -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="text-uppercase text-cyan font-weight-800 fs-7 tracking-wider">Product Catalog</span>
                <h2 class="h3 font-weight-800 mb-0">All Product Catalog</h2>
            </div>
            <a href="{{ route('frontend.products.index') }}" class="btn btn-link text-primary font-weight-700 text-decoration-none">
                See Full Inventory <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-4">
            @foreach($allProducts as $product)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <x-frontend.product-card :product="$product" />
                </div>
            @endforeach
        </div>
    </section>

    <!-- 4. Wholesale Benefits Banner -->
    <section class="bg-white border rounded-4 p-4 p-md-5 mb-5 shadow-sm">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <span class="badge bg-emerald-subtle text-emerald border border-emerald font-weight-700 px-3 py-2 rounded-pill mb-3">
                    <i class="fas fa-building me-1"></i> Designed for B2B Purchasing
                </span>
                <h2 class="h2 font-weight-800 mb-3">Direct OEM Distribution & Volume Discounts</h2>
                <p class="text-secondary leading-relaxed mb-4">
                    Apex IT Wholesale eliminates middleman markups. We provide certified tier 1 hardware directly to system integrators, corporate IT departments, universities, and resellers with flexible payment terms.
                </p>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle text-emerald fs-5"></i>
                            <span class="font-weight-700 text-dark fs-7">Volume Tiered Savings</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle text-emerald fs-5"></i>
                            <span class="font-weight-700 text-dark fs-7">Dedicated Account Executive</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle text-emerald fs-5"></i>
                            <span class="font-weight-700 text-dark fs-7">Net 30/60 Invoicing</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle text-emerald fs-5"></i>
                            <span class="font-weight-700 text-dark fs-7">Palletized Freight Logistics</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-4 bg-light rounded-3 text-center border">
                            <h3 class="display-6 font-weight-800 text-emerald mb-1">30+</h3>
                            <p class="text-secondary font-weight-600 mb-0 fs-7">Commercial Product Lines</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-4 bg-light rounded-3 text-center border">
                            <h3 class="display-6 font-weight-800 text-primary mb-1">100%</h3>
                            <p class="text-secondary font-weight-600 mb-0 fs-7">Genuine Factory Stock</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-4 bg-light rounded-3 text-center border">
                            <h3 class="display-6 font-weight-800 text-info mb-1">14+</h3>
                            <p class="text-secondary font-weight-600 mb-0 fs-7">Global Brand Partners</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-4 bg-light rounded-3 text-center border">
                            <h3 class="display-6 font-weight-800 text-warning mb-1">24hr</h3>
                            <p class="text-secondary font-weight-600 mb-0 fs-7">Quote Turnaround</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Best Sellers -->
    <section class="mb-5 b2b-slider-container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="text-uppercase text-warning font-weight-800 fs-7 tracking-wider">Top Procurement Choice</span>
                <h2 class="h3 font-weight-800 mb-0">Best Selling Business Equipment</h2>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('frontend.products.index') }}" class="btn btn-link text-emerald font-weight-700 text-decoration-none d-none d-md-inline-block">
                    Browse All Best Sellers <i class="fas fa-arrow-right ms-1"></i>
                </a>
                <div class="b2b-slider-controls">
                    <button type="button" class="b2b-slider-btn b2b-slider-prev" aria-label="Previous Best Sellers" title="Previous">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button type="button" class="b2b-slider-btn b2b-slider-next" aria-label="Next Best Sellers" title="Next">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="b2b-slider-track">
            @foreach($bestSellers as $product)
                <div class="b2b-slider-item">
                    <x-frontend.product-card :product="$product" />
                </div>
            @endforeach
        </div>
    </section>

    <!-- 6. Popular Brands -->
    <section class="mb-5">
        <div class="text-center mb-4">
            <span class="text-uppercase text-emerald font-weight-800 fs-7 tracking-wider" style="color: var(--b2b-accent);">Official Direct Manufacturers</span>
            <h2 class="h3 font-weight-800 mb-1">Authorized Brand Partners</h2>
            <p class="text-muted fs-7 mb-0">Direct procurement & enterprise warranty fulfilled through official brand channels</p>
        </div>
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-7 g-3">
            @foreach($brands as $b)
                <div class="col">
                    <x-frontend.brand-card :brand="$b" />
                </div>
            @endforeach
        </div>
    </section>

    <!-- 7. New Arrivals -->
    <section class="mb-5 b2b-slider-container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="text-uppercase text-primary font-weight-800 fs-7 tracking-wider">Latest Releases</span>
                <h2 class="h3 font-weight-800 mb-0">New Hardware Arrivals</h2>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('frontend.products.index') }}" class="btn btn-link text-primary font-weight-700 text-decoration-none d-none d-md-inline-block">
                    Explore New Arrivals <i class="fas fa-arrow-right ms-1"></i>
                </a>
                <div class="b2b-slider-controls">
                    <button type="button" class="b2b-slider-btn b2b-slider-prev" aria-label="Previous New Arrivals" title="Previous">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button type="button" class="b2b-slider-btn b2b-slider-next" aria-label="Next New Arrivals" title="Next">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="b2b-slider-track">
            @foreach($newArrivals as $product)
                <div class="b2b-slider-item">
                    <x-frontend.product-card :product="$product" />
                </div>
            @endforeach
        </div>
    </section>

    <!-- 8. Request Quote CTA Banner -->
    <section class="bg-dark text-white rounded-4 p-5 text-center position-relative overflow-hidden mb-4">
        <div class="position-relative" style="z-index: 2;">
            <h2 class="display-6 font-weight-800 mb-3 text-white">Need Custom Quantities or Special Pricing?</h2>
            <p class="lead text-light opacity-90 mx-auto mb-4" style="max-width: 680px;">
                Submitting a bulk request takes less than 2 minutes. Our corporate procurement specialists will review your requirements and respond within 2 business hours.
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('frontend.quotes.create') }}" class="btn b2b-btn-accent btn-lg font-weight-700 px-5 rounded-pill shadow">
                    <i class="fas fa-paper-plane me-2"></i> Submit RFQ / Request Quote
                </a>
                <a href="{{ route('frontend.contact') }}" class="btn btn-outline-light btn-lg font-weight-700 px-4 rounded-pill">
                    <i class="fas fa-phone-alt me-2"></i> Speak with Specialist
                </a>
            </div>
        </div>
    </section>

</div>

@endsection
