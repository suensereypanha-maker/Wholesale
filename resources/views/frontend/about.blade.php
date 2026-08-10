@extends('frontend.layouts.app')

@section('title', 'About Apex IT Wholesale Corporation')

@section('content')
<div class="container">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb fs-7">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">About Us</li>
        </ol>
    </nav>

    <!-- Header Banner -->
    <div class="bg-white border rounded-4 p-5 mb-5 shadow-sm">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="badge bg-emerald-subtle text-emerald border border-emerald font-weight-700 px-3 py-2 rounded-pill mb-3">
                    Established 2012
                </span>
                <h1 class="display-5 font-weight-800 text-dark mb-3">Empowering Enterprise Infrastructure Worldwide</h1>
                <p class="lead text-secondary leading-relaxed mb-4">
                    Apex IT Wholesale is a premier international distributor of commercial IT hardware, server workstations, enterprise laptops, and computer components.
                </p>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="border-start border-4 border-emerald ps-3">
                            <h3 class="h2 font-weight-800 text-dark mb-0">$120M+</h3>
                            <p class="text-secondary fs-7 mb-0">Annual Hardware Shipments</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border-start border-4 border-cyan ps-3">
                            <h3 class="h2 font-weight-800 text-dark mb-0">5,000+</h3>
                            <p class="text-secondary fs-7 mb-0">Corporate Resellers & Clients</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <img src="https://images.unsplash.com/photo-1587831990711-23ca6441447b?w=600&auto=format&fit=crop&q=80" alt="Warehouse Facility" class="img-fluid rounded-4 shadow border">
            </div>
        </div>
    </div>

    <!-- Core Value Grid -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="bg-white border rounded-4 p-4 h-100 shadow-sm text-center">
                <div class="b2b-cat-icon mx-auto mb-3 text-emerald bg-emerald-subtle" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="fas fa-certificate"></i>
                </div>
                <h4 class="h5 font-weight-800 text-dark mb-2">100% Genuine Factory Stock</h4>
                <p class="text-secondary fs-7 mb-0">All hardware sourced directly from original manufacturers including Dell, HP, Lenovo, ASUS, Intel, AMD, and NVIDIA with full factory warranties.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-white border rounded-4 p-4 h-100 shadow-sm text-center">
                <div class="b2b-cat-icon mx-auto mb-3 text-cyan bg-cyan-subtle" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="fas fa-truck-fast"></i>
                </div>
                <div class="h5 font-weight-800 text-dark mb-2">Global Freight Logistics</div>
                <p class="text-secondary fs-7 mb-0">State-of-the-art warehousing facilities in California and Rotterdam ensuring rapid palletized sea and air freight delivery.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-white border rounded-4 p-4 h-100 shadow-sm text-center">
                <div class="b2b-cat-icon mx-auto mb-3 text-warning bg-warning-subtle" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <div class="h5 font-weight-800 text-dark mb-2">Flexible Net Credit Terms</div>
                <p class="text-secondary fs-7 mb-0">We support our business partners with customizable Net 30/60 financing and volume tier discounts for recurring deployments.</p>
            </div>
        </div>
    </div>

</div>
@endsection
