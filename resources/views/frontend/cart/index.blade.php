@extends('frontend.layouts.app')

@section('title', 'Wholesale Shopping Cart - Apex IT')

@section('content')
<div class="container">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb fs-7">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Wholesale Cart</li>
        </ol>
    </nav>

    <h1 class="h2 font-weight-800 text-dark mb-4">
        <i class="fas fa-shopping-cart text-emerald me-2"></i> B2B Wholesale Cart
    </h1>

    @if(count($cartItems) > 0)
        <div class="row g-4">
            
            <!-- Cart Items Table -->
            <div class="col-lg-8">
                <div class="bg-white border rounded-4 shadow-sm p-4 mb-4">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="fs-7 text-uppercase font-weight-700 text-secondary">
                                    <th>Product Information</th>
                                    <th>Quantity</th>
                                    <th>Wholesale Unit Price</th>
                                    <th>Subtotal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="rounded border p-1" style="width: 65px; height: 65px; object-fit: contain;">
                                                <div>
                                                    <h6 class="font-weight-700 text-dark mb-1 fs-6">
                                                        <a href="{{ route('frontend.products.show', $item['product_id']) }}" class="text-dark hover-emerald">
                                                            {{ $item['name'] }}
                                                        </a>
                                                    </h6>
                                                    <div class="fs-7 text-secondary">SKU: <span class="font-monospace text-dark">{{ $item['sku'] }}</span></div>
                                                    <div class="fs-7 text-success"><i class="fas fa-boxes me-1"></i> MOQ: {{ $item['moq'] }} units</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="width: 140px;">
                                            <form action="{{ route('frontend.cart.update') }}" method="POST" class="d-flex flex-column gap-1">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                                <div class="input-group input-group-sm">
                                                    <input type="number" name="quantity" class="form-control text-center font-weight-700" value="{{ $item['quantity'] }}" min="{{ $item['moq'] }}" max="{{ $item['stock'] }}">
                                                    <button type="submit" class="btn btn-outline-secondary" title="Update Quantity">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="font-weight-800 text-dark">${{ number_format($item['unit_price'], 2) }}</div>
                                            @if($item['unit_price'] < $item['base_price'])
                                                <div class="fs-7 text-decoration-line-through text-muted">${{ number_format($item['base_price'], 2) }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="font-weight-800 text-emerald fs-6">${{ number_format($item['subtotal'], 2) }}</div>
                                        </td>
                                        <td>
                                            <form action="{{ route('frontend.cart.remove', $item['product_id']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0" title="Remove item">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-4 border-top mt-3">
                        <a href="{{ route('frontend.products.index') }}" class="btn btn-outline-dark font-weight-600">
                            <i class="fas fa-arrow-left me-1"></i> Continue Shopping
                        </a>
                        <form action="{{ route('frontend.cart.clear') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger font-weight-600" onclick="return confirm('Clear entire cart?')">
                                <i class="fas fa-trash me-1"></i> Clear Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="col-lg-4">
                <div class="bg-white border rounded-4 shadow-sm p-4 sticky-top" style="top: 90px;">
                    <h5 class="font-weight-800 text-dark mb-4 border-bottom pb-3">Wholesale Summary</h5>

                    @if($totalSavings > 0)
                        <div class="alert alert-success border-success-subtle d-flex align-items-center gap-2 mb-4">
                            <i class="fas fa-tags fs-4"></i>
                            <div>
                                <strong class="d-block font-weight-800">Volume Tier Discount Applied!</strong>
                                <span class="fs-7">You are saving ${{ number_format($totalSavings, 2) }} on bulk pricing.</span>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mb-3 fs-6">
                        <span class="text-secondary">Subtotal</span>
                        <span class="font-weight-700 text-dark">${{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 fs-6">
                        <span class="text-secondary">Estimated Tax (10%)</span>
                        <span class="font-weight-700 text-dark">${{ number_format($estimatedTax, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 fs-6">
                        <span class="text-secondary">Freight Shipping</span>
                        <span class="font-weight-700 text-dark">
                            @if($estimatedShipping == 0)
                                <span class="text-success font-weight-800">FREE Freight</span>
                            @else
                                ${{ number_format($estimatedShipping, 2) }}
                            @endif
                        </span>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between mb-4">
                        <span class="font-weight-800 h5 mb-0">Grand Total</span>
                        <span class="font-weight-800 h4 text-emerald mb-0">${{ number_format($grandTotal, 2) }}</span>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('frontend.checkout.index') }}" class="btn b2b-btn-accent btn-lg font-weight-800 shadow-sm py-3">
                            Proceed to Checkout <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>

                    <div class="mt-4 pt-3 border-top text-center fs-7 text-secondary">
                        <div><i class="fas fa-lock text-success me-1"></i> Secure Business Transaction</div>
                        <div>Official Purchase Order Invoice Included</div>
                    </div>
                </div>
            </div>

        </div>
    @else
        <div class="bg-white border rounded-4 p-5 text-center my-4">
            <i class="fas fa-shopping-cart text-secondary fs-1 mb-3"></i>
            <h3 class="font-weight-800">Your Wholesale Cart is Empty</h3>
            <p class="text-secondary mb-4">You have no items in your cart. Browse our commercial inventory to select hardware.</p>
            <a href="{{ route('frontend.products.index') }}" class="btn b2b-btn-accent btn-lg font-weight-700 px-5 rounded-pill">
                Browse Products Catalog
            </a>
        </div>
    @endif

</div>
@endsection
