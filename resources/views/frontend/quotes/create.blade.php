@extends('frontend.layouts.app')

@section('title', 'Request Wholesale Quote - Apex IT')

@section('content')
<div class="container-fluid px-4 px-lg-5">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb fs-7">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Request Wholesale Quote</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="bg-white border rounded-4 shadow-sm p-4 p-md-5 mb-5">
                <div class="text-center mb-5">
                    <span class="badge bg-emerald-subtle text-emerald border border-emerald font-weight-700 px-3 py-2 rounded-pill mb-2">
                        <i class="fas fa-file-invoice-dollar me-1"></i> B2B Request for Quotation (RFQ)
                    </span>
                    <h1 class="display-6 font-weight-800 text-dark">Request Custom Bulk Quote</h1>
                    <p class="text-secondary leading-relaxed max-w-600 mx-auto fs-6">
                        Need specialized pricing for large-scale enterprise deployments, educational rollouts, or government tenders? Submit your required specifications and target price below.
                    </p>
                </div>

                <form action="{{ route('frontend.quotes.store') }}" method="POST">
                    @csrf

                    <!-- Contact & Company Information -->
                    <h5 class="font-weight-800 text-dark mb-4 border-bottom pb-2">
                        <i class="fas fa-building text-primary me-2"></i> 1. Organization & Contact Info
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Company / Organization Name *</label>
                            <input type="text" name="company" class="form-control" value="{{ old('company', $customer['company'] ?? '') }}" placeholder="e.g. Acme Tech Solutions LLC" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Procurement Officer Name *</label>
                            <input type="text" name="contact_name" class="form-control" value="{{ old('contact_name', $customer['name'] ?? '') }}" placeholder="John Doe" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Business Email Address *</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $customer['email'] ?? '') }}" placeholder="procurement@acme.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Phone Number *</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer['phone'] ?? '') }}" placeholder="+1 (555) 019-2831" required>
                        </div>
                    </div>

                    <!-- Hardware & Quote Requirements -->
                    <h5 class="font-weight-800 text-dark mb-4 border-bottom pb-2">
                        <i class="fas fa-boxes text-emerald me-2"></i> 2. Product & Target Pricing Requirements
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label font-weight-700 fs-7">Product Name / Model *</label>
                            @if(isset($selectedProduct))
                                <input type="text" name="product_name" class="form-control font-weight-700 bg-light" value="{{ $selectedProduct['name'] }} (SKU: {{ $selectedProduct['sku'] }})" readonly>
                            @else
                                <select name="product_name" class="form-select" required>
                                    <option value="">-- Select Product from Inventory or Type Custom --</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p['name'] }} (SKU: {{ $p['sku'] }})">
                                            {{ $p['brand'] }} - {{ $p['name'] }} (Base: ${{ $p['price'] }})
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <label class="form-label font-weight-700 fs-7">Required Unit Quantity *</label>
                            <input type="number" name="quantity" class="form-control" value="{{ old('quantity', isset($selectedProduct) ? $selectedProduct['moq'] : 50) }}" min="1" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label font-weight-700 fs-7">Target Unit Price ($ USD)</label>
                            <input type="number" step="0.01" name="target_price" class="form-control" placeholder="e.g. 580.00" value="{{ old('target_price') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label font-weight-700 fs-7">Required Delivery Date *</label>
                            <input type="date" name="required_date" class="form-control" value="{{ old('required_date', date('Y-m-d', strtotime('+14 days'))) }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label font-weight-700 fs-7">Project Notes & Specifications</label>
                            <textarea name="message" class="form-control" rows="4" placeholder="Detail any custom OS provisioning, RAM upgrades, custom packaging, or tax-exempt status requirements."></textarea>
                        </div>
                    </div>

                    <div class="text-center pt-3 border-top">
                        <button type="submit" class="btn b2b-btn-accent btn-lg font-weight-800 px-5 rounded-pill shadow">
                            <i class="fas fa-paper-plane me-2"></i> Submit RFQ Quote Request
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>

</div>
@endsection
