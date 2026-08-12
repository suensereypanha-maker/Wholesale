@extends('frontend.layouts.app')

@section('title', 'Saved Wishlist - Apex IT Wholesale')

@section('content')
<div class="container-fluid px-4 px-lg-5">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb fs-7">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('frontend.account') }}">Account</a></li>
            <li class="breadcrumb-item active" aria-current="page">Saved Wishlist</li>
        </ol>
    </nav>

    <h1 class="h2 font-weight-800 text-dark mb-4">
        <i class="fas fa-heart text-danger me-2"></i> Saved Wishlist Items
    </h1>

    @if(count($wishlistProducts) > 0)
        <div class="row g-4">
            @foreach($wishlistProducts as $product)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="position-relative">
                        <x-frontend.product-card :product="$product" />
                        <form action="{{ route('frontend.wishlist.remove', $product['id']) }}" method="POST" class="position-absolute" style="top: 10px; right: 10px; z-index: 10;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-light border text-danger rounded-circle shadow-sm" title="Remove from Wishlist">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white border rounded-4 p-5 text-center my-4">
            <i class="far fa-heart text-secondary fs-1 mb-3"></i>
            <h3 class="font-weight-800">Your Wishlist is Empty</h3>
            <p class="text-secondary">You haven't saved any hardware products yet.</p>
            <a href="{{ route('frontend.products.index') }}" class="btn b2b-btn-accent font-weight-700">
                Browse Products Catalog
            </a>
        </div>
    @endif

</div>
@endsection
