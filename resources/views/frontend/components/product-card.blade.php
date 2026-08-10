@props(['product'])

@php
    $tiers = $product['wholesalePrices'] ?? [];
    $lowestPrice = count($tiers) > 0 ? end($tiers)['price'] : $product['price'];
@endphp

<div class="b2b-product-card">
    
    <!-- Image Wrapper with MOQ Badge -->
    <div class="b2b-product-img-wrapper">
        <a href="{{ route('frontend.products.show', $product['id']) }}">
            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="b2b-product-img" loading="lazy">
        </a>
        <span class="b2b-moq-badge"><i class="fas fa-boxes me-1"></i> MOQ: {{ $product['moq'] }} units</span>
        @if($lowestPrice < $product['price'])
            <span class="b2b-tier-badge"><i class="fas fa-tags me-1"></i> Bulk Discounts</span>
        @endif
    </div>

    <!-- Body -->
    <div class="b2b-product-body">
        <div class="b2b-product-brand">{{ $product['brand'] }}</div>
        
        <h3 class="b2b-product-title">
            <a href="{{ route('frontend.products.show', $product['id']) }}" class="text-dark hover-emerald">
                {{ $product['name'] }}
            </a>
        </h3>
        
        <div class="b2b-product-sku">SKU: <span class="font-monospace text-dark">{{ $product['sku'] }}</span></div>

        <!-- Rating & Stock -->
        <div class="d-flex align-items-center justify-content-between mb-3 fs-7">
            <div class="text-warning">
                <i class="fas fa-star"></i>
                <span class="font-weight-700 text-dark ms-1">{{ $product['rating'] }}</span>
                <span class="text-muted">({{ $product['reviews'] }})</span>
            </div>
            <div>
                @if($product['stock'] > 0)
                    <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="fas fa-check-circle me-1"></i> {{ $product['stock'] }} in stock</span>
                @else
                    <span class="badge bg-danger-subtle text-danger"><i class="fas fa-times-circle me-1"></i> Out of stock</span>
                @endif
            </div>
        </div>

        <!-- Tier Price Summary -->
        <div class="b2b-price-box">
            <div>
                <div class="b2b-price-from">From</div>
                <div class="b2b-price-val">${{ number_format($lowestPrice, 2) }}</div>
            </div>
            <div class="text-end">
                <div class="b2b-price-from">Base Price</div>
                <div class="text-secondary text-decoration-line-through font-weight-500">${{ number_format($product['price'], 2) }}</div>
            </div>
        </div>

        <!-- Actions -->
        <div class="d-grid gap-2 mt-3">
            <div class="row g-2">
                <div class="col-5">
                    <a href="{{ route('frontend.products.show', $product['id']) }}" class="btn btn-outline-dark btn-sm w-100 font-weight-600">
                        Details
                    </a>
                </div>
                <div class="col-7">
                    <form action="{{ route('frontend.cart.add') }}" method="POST" class="b2b-add-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                        <input type="hidden" name="quantity" value="{{ $product['moq'] }}">
                        <button type="submit" class="btn b2b-btn-accent btn-sm w-100 font-weight-600">
                            <i class="fas fa-cart-plus me-1"></i> Add {{ $product['moq'] }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
