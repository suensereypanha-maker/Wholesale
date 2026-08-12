@php
    $cart = session('frontend_cart', []);
    $cartCount = array_sum(array_column($cart, 'quantity'));
    $wishlistCount = count(session('frontend_wishlist', []));
    $authUser = auth()->user();
    $customer = $authUser ? $authUser->toArray() : session('frontend_customer');
    $categories = \App\Data\FrontendData::categories();
@endphp

<!-- Top Announcement & B2B Info Bar -->
<div class="b2b-topbar">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-none d-md-flex align-items-center gap-4">
            <span><i class="fas fa-shield-check me-1 text-success"></i> Authorized IT & Hardware Distributor</span>
            <span><i class="fas fa-truck-fast me-1 text-info"></i> Bulk Freight & Next-Day Business Shipping</span>
        </div>
        <div class="d-flex align-items-center gap-3 ms-auto font-weight-500">
            <a href="tel:+18005550199"><i class="fas fa-phone me-1"></i> +1 (800) 555-0199</a>
            <span class="text-secondary">|</span>
            <a href="{{ route('frontend.quotes.create') }}" class="text-warning font-weight-bold"><i class="fas fa-file-signature me-1"></i> Request Quote</a>
            <span class="text-secondary">|</span>
            <span class="badge bg-success font-weight-600">Net 30/60 Available</span>
        </div>
    </div>
</div>

<!-- Main Header Navbar -->
<header class="b2b-navbar">
    <div class="container">
        <div class="row align-items-center">
            
            <!-- Logo -->
            <div class="col-6 col-lg-3 d-flex align-items-center">
                <button class="btn d-lg-none me-2 text-dark fs-4 p-0 border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenuOffcanvas" aria-controls="mobileMenuOffcanvas">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="{{ route('frontend.home') }}" class="b2b-logo">
                    <i class="fas fa-microchip"></i>
                    <span>APEX<span class="text-emerald font-weight-800" style="color: var(--b2b-accent);">IT</span></span>
                </a>
            </div>

            <!-- Global Product Search Bar -->
            <div class="col-12 col-lg-5 my-2 my-lg-0 order-3 order-lg-2 position-relative">
                <form action="{{ route('frontend.products.index') }}" method="GET" class="b2b-search-form" id="b2bHeaderSearchForm">
                    <div class="input-group b2b-search-group">
                        <span class="input-group-text b2b-search-icon">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" id="b2bHeaderSearchInput" class="form-control b2b-search-input" placeholder="Search product name, SKU (e.g. DELL-L5440), brand..." value="{{ request('search') }}" autocomplete="off">
                        <button type="submit" class="btn b2b-search-btn">
                            Search
                        </button>
                    </div>
                </form>

                <!-- Live Search Auto-complete Suggestions Dropdown -->
                <div id="b2bSearchSuggestions" class="b2b-search-suggestions-dropdown d-none"></div>
            </div>

            <!-- Header Action Icons -->
            <div class="col-6 col-lg-4 text-end order-2 order-lg-3 d-flex align-items-center justify-content-end gap-3">
                
                <!-- Wishlist Badge -->
                <a href="{{ route('frontend.account.wishlist') }}" class="text-dark position-relative p-2" title="Wishlist">
                    <i class="far fa-heart fs-4"></i>
                    <span id="b2b-header-wishlist-badge" class="b2b-nav-badge">{{ $wishlistCount }}</span>
                </a>

                <!-- Cart Badge -->
                <a href="{{ route('frontend.cart.index') }}" class="text-dark position-relative p-2 me-2" title="Wholesale Cart">
                    <i class="fas fa-shopping-cart fs-4"></i>
                    <span id="b2b-header-cart-badge" class="b2b-nav-badge">{{ $cartCount }}</span>
                </a>

                <!-- Customer Account Button -->
                @if($customer)
                    <div class="dropdown">
                        <button class="btn btn-outline-dark dropdown-toggle btn-sm font-weight-600 rounded-pill px-3 py-2 d-inline-flex align-items-center gap-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1 text-emerald" style="color: var(--b2b-accent);"></i> {{ Str::limit($customer['company'] ?? $customer['name'] ?? 'Account', 18) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end b2b-account-dropdown">
                            <li>
                                <div class="dropdown-header-custom">
                                    <div class="fw-bold text-dark fs-7 text-uppercase" style="letter-spacing: 0.5px;">{{ $customer['name'] ?? 'Account' }}</div>
                                    @if(!empty($customer['email']))
                                        <div class="text-muted fs-8 text-truncate">{{ $customer['email'] }}</div>
                                    @endif
                                </div>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('frontend.account') }}"><i class="fas fa-tachometer-alt me-2 text-primary" style="width: 18px; text-align: center;"></i> B2B Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('frontend.orders.index') }}"><i class="fas fa-box-open me-2 text-info" style="width: 18px; text-align: center;"></i> Order History</a></li>
                            <li><a class="dropdown-item" href="{{ route('frontend.account.profile') }}"><i class="fas fa-id-card me-2 text-success" style="width: 18px; text-align: center;"></i> Company Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('frontend.account.wishlist') }}"><i class="fas fa-heart me-2 text-danger" style="width: 18px; text-align: center;"></i> Wishlist</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('frontend.logout') }}" method="POST" class="m-0 p-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item dropdown-item-danger w-100 border-0 bg-transparent text-start">
                                        <i class="fas fa-sign-out-alt me-2 text-danger" style="width: 18px; text-align: center;"></i> Log Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('frontend.login') }}" class="btn b2b-btn-primary btn-sm rounded-pill px-3 py-2 font-weight-600">
                        <i class="fas fa-user-lock me-1"></i> B2B Login
                    </a>
                @endif
            </div>

        </div>
    </div>
</header>

<!-- Secondary Category Nav Menu -->
<nav class="bg-white border-bottom d-none d-lg-block b2b-secondary-nav">
    <div class="container">
        <ul class="nav nav-pills align-items-center font-weight-600 fs-7">
            <li class="nav-item">
                <a class="nav-link text-dark py-3 ps-0 {{ request()->routeIs('frontend.home') ? 'text-emerald font-weight-800' : '' }}" href="{{ route('frontend.home') }}">
                    <i class="fas fa-home me-1"></i> Home
                </a>
            </li>
            
            <!-- Category Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link text-dark py-3 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-th-large me-1"></i> All Categories
                </a>
                <ul class="dropdown-menu shadow border-0 p-2 b2b-category-dropdown" style="min-width: 260px;">
                    @foreach($categories as $cat)
                        <li>
                            <a class="dropdown-item d-flex align-items-center justify-content-between py-2 rounded" href="{{ route('frontend.products.index', ['category' => $cat['slug']]) }}">
                                <span><i class="{{ $cat['icon'] }} me-2 text-secondary" style="width: 20px;"></i> {{ $cat['name'] }}</span>
                                <span class="badge bg-light text-dark rounded-pill">{{ $cat['count'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link text-dark py-3 {{ request()->routeIs('frontend.products.*') ? 'text-emerald font-weight-800' : '' }}" href="{{ route('frontend.products.index') }}">
                    <i class="fas fa-boxes me-1"></i> Products Catalog
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark py-3 {{ request()->routeIs('frontend.categories.*') ? 'text-emerald font-weight-800' : '' }}" href="{{ route('frontend.categories.index') }}">
                    <i class="fas fa-layer-group me-1"></i> Categories
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark py-3 {{ request()->routeIs('frontend.quotes.*') ? 'text-emerald font-weight-800' : '' }}" href="{{ route('frontend.quotes.create') }}">
                    <i class="fas fa-file-invoice-dollar me-1"></i> Bulk Quotes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark py-3 {{ request()->routeIs('frontend.about') ? 'text-emerald font-weight-800' : '' }}" href="{{ route('frontend.about') }}">
                    <i class="fas fa-building me-1"></i> About Us
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark py-3 {{ request()->routeIs('frontend.contact') ? 'text-emerald font-weight-800' : '' }}" href="{{ route('frontend.contact') }}">
                    <i class="fas fa-headset me-1"></i> Support & Contact
                </a>
            </li>
        </ul>
    </div>
</nav>
