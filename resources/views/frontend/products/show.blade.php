@extends('frontend.layouts.app')

@section('title', $product['name'] . ' - B2B Wholesale')

@section('content')
<div class="container">
    
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb fs-7">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('frontend.products.index') }}">Products</a></li>
            <li class="breadcrumb-item"><a href="{{ route('frontend.products.index', ['category' => $product['category_slug']]) }}">{{ $product['category'] }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product['sku'] }}</li>
        </ol>
    </nav>

    <div class="bg-white border rounded-4 p-4 p-md-5 mb-5 shadow-sm">
        <div class="row g-4 lg-g-5">
            
            <!-- Left Column: Product Gallery / Image -->
            <div class="col-lg-5">
                <div class="position-relative bg-light rounded-4 overflow-hidden border p-3 text-center mb-3">
                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" id="mainProductImg" class="img-fluid rounded-3" style="max-height: 420px; width: 100%; object-fit: contain;">
                    <span class="b2b-moq-badge"><i class="fas fa-boxes me-1"></i> MOQ: {{ $product['moq'] }} Units</span>
                </div>
                <div class="row g-2">
                    <div class="col-3">
                        <img src="{{ $product['image'] }}" class="img-thumbnail cursor-pointer border-emerald" alt="Thumb 1">
                    </div>
                </div>
            </div>

            <!-- Right Column: Product Detail & Wholesale Pricing Matrix -->
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-dark text-uppercase font-weight-700">{{ $product['brand'] }}</span>
                    <span class="badge bg-secondary font-weight-600">{{ $product['category'] }}</span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle ms-auto">
                        <i class="fas fa-shield-alt me-1"></i> {{ $product['warranty'] }}
                    </span>
                </div>

                <h1 class="h2 font-weight-800 text-dark mb-2">{{ $product['name'] }}</h1>
                
                <div class="d-flex align-items-center gap-3 fs-7 text-secondary mb-3">
                    <div>SKU: <span class="font-monospace text-dark font-weight-700">{{ $product['sku'] }}</span></div>
                    <span>|</span>
                    <div class="text-warning">
                        <i class="fas fa-star"></i>
                        <span class="font-weight-700 text-dark">{{ $product['rating'] }}</span>
                        <span>({{ $product['reviews'] }} reviews)</span>
                    </div>
                    <span>|</span>
                    <div>Stock: <strong class="text-success">{{ $product['stock'] }} units available</strong></div>
                </div>

                <p class="text-secondary leading-relaxed mb-4 fs-6">
                    {{ $product['description'] }}
                </p>

                <!-- Wholesale Price Matrix Table -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="font-weight-800 text-uppercase text-emerald mb-0 fs-7">
                            <i class="fas fa-tags me-1"></i> Volume Wholesale Pricing Tier
                        </h6>
                        <span class="fs-7 text-muted">Select quantity to see real-time unit price</span>
                    </div>

                    <div class="b2b-pricing-table">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>Quantity Tier</th>
                                    <th>Unit Price</th>
                                    <th>Discount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product['wholesalePrices'] as $index => $tier)
                                    @php
                                        $range = $tier['maxQty'] ? "{$tier['minQty']} – {$tier['maxQty']} units" : "{$tier['minQty']}+ units";
                                        $discountPercent = round((($product['price'] - $tier['price']) / $product['price']) * 100);
                                    @endphp
                                    <tr class="b2b-tier-row" data-min="{{ $tier['minQty'] }}" data-max="{{ $tier['maxQty'] ?? 999999 }}">
                                        <td class="font-weight-600">{{ $range }}</td>
                                        <td class="font-weight-800 text-emerald">${{ number_format($tier['price'], 2) }}</td>
                                        <td>
                                            @if($discountPercent > 0)
                                                <span class="badge bg-success font-weight-700">Save {{ $discountPercent }}%</span>
                                            @else
                                                <span class="text-muted fs-7">Base Price</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Live Subtotal Calculation & Quantity Controls -->
                <form action="{{ route('frontend.cart.add') }}" method="POST" class="b2b-add-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product['id'] }}">

                    <div class="p-3 bg-light border rounded-3 mb-4">
                        <div class="row align-items-center g-3">
                            <div class="col-12 col-md-5">
                                <label class="form-label font-weight-700 fs-7 mb-1">Select Order Quantity (MOQ: {{ $product['moq'] }})</label>
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary font-weight-700" type="button" id="btn-qty-minus">-</button>
                                    <input type="number" name="quantity" id="b2b-qty-input" class="form-control text-center font-weight-800 fs-5" 
                                           value="{{ $product['moq'] }}" 
                                           min="{{ $product['moq'] }}" 
                                           max="{{ $product['stock'] }}"
                                           data-moq="{{ $product['moq'] }}"
                                           data-base-price="{{ $product['price'] }}">
                                    <button class="btn btn-outline-secondary font-weight-700" type="button" id="btn-qty-plus">+</button>
                                </div>
                            </div>
                            <div class="col-12 col-md-7 text-md-end">
                                <div class="text-secondary fs-7">Calculated Unit Price: <strong id="b2b-unit-price" class="text-dark fs-6">${{ number_format($product['price'], 2) }}</strong></div>
                                <div class="h3 font-weight-800 text-emerald mb-0" id="b2b-subtotal">$0.00</div>
                                <div class="fs-7 text-success font-weight-700 d-none" id="b2b-savings">Tier Savings: $0.00</div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-wrap gap-3">
                        <button type="submit" class="btn b2b-btn-accent btn-lg font-weight-700 px-4 flex-grow-1">
                            <i class="fas fa-cart-plus me-2"></i> Add to Wholesale Cart
                        </button>
                        
                        <a href="{{ route('frontend.quotes.create', ['product_id' => $product['id']]) }}" class="btn btn-outline-primary btn-lg font-weight-700 px-4">
                            <i class="fas fa-file-invoice-dollar me-2"></i> Request Quote
                        </a>

                        <button type="button" data-product-id="{{ $product['id'] }}" data-url="{{ route('frontend.wishlist.add') }}" class="btn btn-outline-danger btn-lg b2b-wishlist-btn px-3" title="Save to Wishlist">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>

    <!-- Detailed Technical Specifications -->
    <div class="bg-white border rounded-4 p-4 p-md-5 mb-5 shadow-sm">
        <h4 class="font-weight-800 mb-4 text-dark border-bottom pb-3"><i class="fas fa-microchip text-emerald me-2"></i> Technical Specifications</h4>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <tbody>
                    @foreach($product['specifications'] as $key => $val)
                        <tr>
                            <th style="width: 250px;" class="bg-light font-weight-700 text-secondary fs-7">{{ $key }}</th>
                            <td class="font-weight-600 text-dark">{{ $val }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Related Products Slider -->
    @if(count($relatedProducts) > 0)
        <div class="mb-5 b2b-slider-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <span class="text-uppercase text-primary font-weight-800 fs-7 tracking-wider">Compatible Hardware</span>
                    <h4 class="font-weight-800 text-dark mb-0"><i class="fas fa-layer-group text-primary me-2"></i> Similar Hardware in {{ $product['category'] }}</h4>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-secondary font-weight-500 fs-7 d-none d-md-inline-block">
                        <i class="fas fa-hand-pointer text-primary opacity-75 me-1"></i> Scroll for more
                    </span>
                    <div class="b2b-slider-controls">
                        <button type="button" class="b2b-slider-btn b2b-slider-prev" aria-label="Previous Products" title="Previous">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button type="button" class="b2b-slider-btn b2b-slider-next" aria-label="Next Products" title="Next">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="b2b-slider-track">
                @foreach($relatedProducts as $rel)
                    <div class="b2b-slider-item">
                        <x-frontend.product-card :product="$rel" />
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    window.productWholesaleTiers = @json($product['wholesalePrices']);
</script>
<script src="{{ asset('frontend/js/products.js') }}"></script>
@endpush
