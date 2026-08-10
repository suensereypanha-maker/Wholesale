@extends('frontend.layouts.app')

@section('title', 'Hardware Categories - Apex IT Wholesale')

@section('content')
<div class="container">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb fs-7">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Hardware Categories</li>
        </ol>
    </nav>

    <div class="text-center max-w-700 mx-auto mb-5">
        <span class="badge bg-emerald-subtle text-emerald border border-emerald font-weight-700 px-3 py-2 rounded-pill mb-2">Commercial IT Lines</span>
        <h1 class="display-6 font-weight-800 text-dark">Explore Product Categories</h1>
        <p class="text-secondary leading-relaxed fs-6">Browse our complete wholesale portfolio of enterprise laptops, rack servers, workstation CPUs, graphics processing units, and networking hardware.</p>
    </div>

    <div class="row g-4 mb-5">
        @foreach($categories as $cat)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 border rounded-4 shadow-sm hover-lift transition">
                    <div class="card-body p-4 text-center d-flex flex-column">
                        <div class="b2b-cat-icon mb-3" style="width: 65px; height: 65px; font-size: 1.8rem;">
                            <i class="{{ $cat['icon'] }}"></i>
                        </div>
                        <h4 class="h5 font-weight-800 text-dark mb-2">{{ $cat['name'] }}</h4>
                        <p class="text-secondary fs-7 mb-4 flex-grow-1">{{ $cat['description'] }}</p>
                        <div class="mt-auto">
                            <span class="badge bg-light text-dark border me-2 mb-2 font-weight-600">{{ $cat['count'] }} Products</span>
                            <a href="{{ route('frontend.products.index', ['category' => $cat['slug']]) }}" class="btn b2b-btn-primary btn-sm w-100 font-weight-700 rounded-pill">
                                Browse Category <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
