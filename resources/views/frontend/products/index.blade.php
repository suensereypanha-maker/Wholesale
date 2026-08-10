@extends('frontend.layouts.app')

@section('title', 'Wholesale Computer & Hardware Catalog')

@section('content')
<div class="container">
    
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb fs-7">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}"><i class="fas fa-home"></i> Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Products Catalog</li>
        </ol>
    </nav>

    <div class="row g-4">

        <!-- Sidebar Filter Panel -->
        <div class="col-lg-3">
            <div class="bg-white border rounded-3 p-4 sticky-top" style="top: 90px; z-index: 100;">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h5 class="font-weight-800 mb-0"><i class="fas fa-filter text-emerald me-2"></i> Filter Products</h5>
                    @if(request()->anyFilled(['search', 'category', 'brand', 'min_price', 'max_price', 'in_stock']))
                        <a href="{{ route('frontend.products.index') }}" class="btn btn-link btn-sm text-danger p-0 text-decoration-none font-weight-600">
                            Reset Filters
                        </a>
                    @endif
                </div>

                <form action="{{ route('frontend.products.index') }}" method="GET">

                    <!-- Search Filter -->
                    <div class="mb-4">
                        <label class="form-label font-weight-700 fs-7">Keyword Search</label>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="SKU or product title..." value="{{ request('search') }}">
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-4">
                        <label class="form-label font-weight-700 fs-7">Category</label>
                        <select name="category" class="form-select form-select-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat['slug'] }}" {{ request('category') === $cat['slug'] ? 'selected' : '' }}>
                                    {{ $cat['name'] }} ({{ $cat['count'] }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Brand Filter -->
                    <div class="mb-4">
                        <label class="form-label font-weight-700 fs-7">Brand Manufacturer</label>
                        <select name="brand" class="form-select form-select-sm">
                            <option value="">All Brands</option>
                            @foreach($brands as $b)
                                <option value="{{ $b['slug'] }}" {{ request('brand') === $b['slug'] ? 'selected' : '' }}>
                                    {{ $b['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Filter -->
                    <div class="mb-4">
                        <label class="form-label font-weight-700 fs-7">Base Price ($)</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="{{ request('min_price') }}">
                            </div>
                            <div class="col-6">
                                <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Stock Availability Filter -->
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="in_stock" value="1" id="inStockCheck" {{ request('in_stock') ? 'checked' : '' }}>
                            <label class="form-check-input-label font-weight-600 fs-7" for="inStockCheck">
                                In Stock Only
                            </label>
                        </div>
                    </div>

                    <!-- Submit Filter Button -->
                    <button type="submit" class="btn b2b-btn-primary btn-sm w-100 font-weight-700 py-2">
                        <i class="fas fa-search me-1"></i> Apply Filters
                    </button>
                </form>
            </div>
        </div>

        <!-- Product Listing Content Area -->
        <div class="col-lg-9">

            <!-- Header Toolbar: Count & Sorting -->
            <div class="bg-white border rounded-3 p-3 mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="font-weight-600 text-secondary fs-7">
                    Showing <span class="text-dark font-weight-800">{{ $products->firstItem() ?? 0 }}</span> to <span class="text-dark font-weight-800">{{ $products->lastItem() ?? 0 }}</span> of <span class="text-dark font-weight-800">{{ $products->total() }}</span> wholesale products
                </div>

                <!-- Sorting Dropdown -->
                <form action="{{ route('frontend.products.index') }}" method="GET" class="d-flex align-items-center gap-2">
                    @foreach(request()->except('sort', 'page') as $key => $val)
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach
                    <label class="font-weight-700 fs-7 text-nowrap">Sort By:</label>
                    <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest Arrivals</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Product Name (A-Z)</option>
                    </select>
                </form>
            </div>

            <!-- Product Grid -->
            @if($products->count() > 0)
                <div class="row g-4 mb-4">
                    @foreach($products as $product)
                        <div class="col-12 col-sm-6 col-md-4">
                            <x-frontend.product-card :product="$product" />
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    <x-frontend.pagination :paginator="$products" />
                </div>
            @else
                <div class="bg-white border rounded-4 p-5 text-center my-4">
                    <i class="fas fa-search-minus text-secondary fs-1 mb-3"></i>
                    <h4 class="font-weight-800">No Products Match Your Filter</h4>
                    <p class="text-secondary">Try adjusting your category, price range, or search keywords.</p>
                    <a href="{{ route('frontend.products.index') }}" class="btn b2b-btn-accent font-weight-700">
                        View All Products
                    </a>
                </div>
            @endif

        </div>

    </div>
</div>
@endsection
