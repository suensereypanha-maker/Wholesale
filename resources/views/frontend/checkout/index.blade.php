@extends('frontend.layouts.app')

@section('title', 'B2B Wholesale Checkout')

@section('content')
<div class="container">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb fs-7">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('frontend.cart.index') }}">Cart</a></li>
            <li class="breadcrumb-item active" aria-current="page">Checkout</li>
        </ol>
    </nav>

    <h1 class="h2 font-weight-800 text-dark mb-4">
        <i class="fas fa-file-invoice-dollar text-emerald me-2"></i> B2B Wholesale Checkout
    </h1>

    <form action="{{ route('frontend.checkout.store') }}" method="POST">
        @csrf
        
        <div class="row g-4">
            
            <!-- Form Details -->
            <div class="col-lg-8">
                
                <!-- Company & Billing Info -->
                <div class="bg-white border rounded-4 shadow-sm p-4 mb-4">
                    <h5 class="font-weight-800 text-dark mb-4 border-bottom pb-3">
                        <i class="fas fa-building text-primary me-2"></i> Company & Billing Details
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Company Registered Name *</label>
                            <input type="text" name="company" class="form-control" value="{{ old('company', $customer['company'] ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Business Tax Registration Number (VAT/EIN)</label>
                            <input type="text" name="tax_number" class="form-control" value="{{ old('tax_number', $customer['tax_number'] ?? '') }}" placeholder="e.g. VAT-987654321">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Contact Person Name *</label>
                            <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $customer['name'] ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Corporate Email Address *</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $customer['email'] ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Phone Number *</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer['phone'] ?? '') }}" required>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white border rounded-4 shadow-sm p-4 mb-4">
                    <h5 class="font-weight-800 text-dark mb-4 border-bottom pb-3">
                        <i class="fas fa-truck-loading text-success me-2"></i> Delivery Facility & Warehouse Address
                    </h5>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label font-weight-700 fs-7">Street Address / Facility Dock *</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $customer['address'] ?? '') }}" placeholder="742 Enterprise Blvd, Suite 400" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">City *</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', $customer['city'] ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">State / Province *</label>
                            <input type="text" name="province" class="form-control" value="{{ old('province', $customer['province'] ?? '') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label font-weight-700 fs-7">Delivery Instructions / Loading Dock Notes</label>
                            <textarea name="delivery_note" class="form-control" rows="2" placeholder="e.g. Liftgate required, receiving dock open 8am-4pm."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="bg-white border rounded-4 shadow-sm p-4 mb-4">
                    <h5 class="font-weight-800 text-dark mb-4 border-bottom pb-3">
                        <i class="fas fa-credit-card text-info me-2"></i> Payment Terms & Options
                    </h5>

                    <div class="d-grid gap-3">
                        <div class="form-check p-3 border rounded-3">
                            <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="payBank" value="Bank Transfer" checked>
                            <label class="form-check-label font-weight-700 text-dark" for="payBank">
                                Bank Wire Transfer / ACH (Invoice Provided)
                            </label>
                            <div class="fs-7 text-secondary mt-1">Official Proforma Invoice sent via email with wire instructions. Order dispatched upon wire confirmation.</div>
                            
                            <div id="b2b-bank-details" class="mt-3 p-3 bg-light rounded border fs-7">
                                <strong>Bank Account Details:</strong><br>
                                Bank Name: Enterprise Commercial Bank<br>
                                Account Name: Apex IT Wholesale Corp<br>
                                Account Number: 9876-5432-1098<br>
                                SWIFT / BIC: ECMBUS66
                            </div>
                        </div>

                        <div class="form-check p-3 border rounded-3">
                            <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="payTerms" value="Credit Terms">
                            <label class="form-check-label font-weight-700 text-dark" for="payTerms">
                                Net 30 / Net 60 Credit Terms
                            </label>
                            <div class="fs-7 text-secondary mt-1">For approved commercial accounts with active credit limit.</div>
                            
                            <div id="b2b-creditterms-details" class="mt-3 p-3 bg-light rounded border fs-7 d-none">
                                <i class="fas fa-check-circle text-success me-1"></i> Net 30 days invoice will be issued upon shipment dispatch.
                            </div>
                        </div>

                        <div class="form-check p-3 border rounded-3">
                            <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="payCod" value="Cash on Delivery">
                            <label class="form-check-label font-weight-700 text-dark" for="payCod">
                                Cash / Certified Check on Freight Delivery
                            </label>
                            <div class="fs-7 text-secondary mt-1">Pay driver upon pallet arrival at destination warehouse.</div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Order Summary Sidebar -->
            <div class="col-lg-4">
                <div class="bg-white border rounded-4 shadow-sm p-4 sticky-top" style="top: 90px;">
                    <h5 class="font-weight-800 text-dark mb-4 border-bottom pb-3">Order Summary</h5>

                    <!-- Item list -->
                    <div class="mb-4" style="max-height: 250px; overflow-y: auto;">
                        @foreach($cartItems as $item)
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $item['product']['image'] }}" alt="" class="rounded border p-1" style="width: 45px; height: 45px; object-fit: contain;">
                                    <div>
                                        <div class="font-weight-700 fs-7 text-dark line-clamp-1">{{ $item['product']['name'] }}</div>
                                        <div class="fs-8 text-secondary">{{ $item['quantity'] }}x @ ${{ number_format($item['unit_price'], 2) }}</div>
                                    </div>
                                </div>
                                <div class="font-weight-800 text-emerald fs-7">${{ number_format($item['subtotal'], 2) }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between mb-2 fs-7 text-secondary">
                        <span>Subtotal</span>
                        <span class="font-weight-700 text-dark">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 fs-7 text-secondary">
                        <span>Estimated Tax (10%)</span>
                        <span class="font-weight-700 text-dark">${{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 fs-7 text-secondary">
                        <span>Freight Shipping</span>
                        <span class="font-weight-700 text-dark">{{ $shipping == 0 ? 'FREE' : '$' . number_format($shipping, 2) }}</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between mb-4">
                        <span class="font-weight-800 h5 mb-0">Grand Total</span>
                        <span class="font-weight-800 h4 text-emerald mb-0">${{ number_format($grandTotal, 2) }}</span>
                    </div>

                    <button type="submit" class="btn b2b-btn-accent btn-lg w-100 font-weight-800 shadow-sm py-3 mb-3">
                        <i class="fas fa-check-circle me-2"></i> Place Wholesale Order
                    </button>

                    <div class="text-center fs-8 text-secondary">
                        By placing order, you agree to Apex IT Wholesale commercial Terms of Sale and Business Warranty conditions.
                    </div>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection

@push('scripts')
<script src="{{ asset('frontend/js/checkout.js') }}"></script>
@endpush
