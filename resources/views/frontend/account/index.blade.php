@extends('frontend.layouts.app')

@section('title', 'B2B Customer Dashboard')

@section('content')
<div class="container">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb fs-7">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">B2B Account Dashboard</li>
        </ol>
    </nav>

    <div class="row g-4">
        
        <!-- Account Navigation Sidebar -->
        <div class="col-lg-3">
            <div class="bg-white border rounded-4 shadow-sm p-4 sticky-top" style="top: 90px;">
                <div class="text-center mb-4 pb-3 border-bottom">
                    <div class="b2b-cat-icon mx-auto mb-2 text-primary bg-primary-subtle" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="fas fa-building"></i>
                    </div>
                    <h6 class="font-weight-800 text-dark mb-0">{{ $customer['company'] }}</h6>
                    <span class="fs-7 text-secondary">{{ $customer['name'] }}</span>
                </div>

                <div class="list-group list-group-flush font-weight-600 fs-7">
                    <a href="{{ route('frontend.account') }}" class="list-group-item list-group-item-action border-0 rounded-3 active bg-emerald mb-1">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard Overview
                    </a>
                    <a href="{{ route('frontend.orders.index') }}" class="list-group-item list-group-item-action border-0 rounded-3 mb-1">
                        <i class="fas fa-box-open me-2 text-info"></i> My Orders
                    </a>
                    <a href="{{ route('frontend.quotes.create') }}" class="list-group-item list-group-item-action border-0 rounded-3 mb-1">
                        <i class="fas fa-file-invoice-dollar me-2 text-warning"></i> Request Quote
                    </a>
                    <a href="{{ route('frontend.account.wishlist') }}" class="list-group-item list-group-item-action border-0 rounded-3 mb-1">
                        <i class="fas fa-heart me-2 text-danger"></i> Saved Wishlist
                    </a>
                    <a href="{{ route('frontend.account.profile') }}" class="list-group-item list-group-item-action border-0 rounded-3 mb-1">
                        <i class="fas fa-id-card me-2 text-primary"></i> Company Profile
                    </a>
                    
                    <form action="{{ route('frontend.logout') }}" method="POST" class="mt-3 pt-3 border-top">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 font-weight-700">
                            <i class="fas fa-sign-out-alt me-1"></i> Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="col-lg-9">
            
            <!-- Dashboard Summary Stat Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="bg-white border rounded-4 p-4 text-center shadow-sm">
                        <div class="fs-2 font-weight-800 text-primary mb-1">{{ $totalOrders }}</div>
                        <div class="fs-7 text-secondary font-weight-600">Total Orders</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="bg-white border rounded-4 p-4 text-center shadow-sm">
                        <div class="fs-2 font-weight-800 text-warning mb-1">{{ $pendingOrders }}</div>
                        <div class="fs-7 text-secondary font-weight-600">Active / Pending</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="bg-white border rounded-4 p-4 text-center shadow-sm">
                        <div class="fs-2 font-weight-800 text-success mb-1">{{ $completedOrders }}</div>
                        <div class="fs-7 text-secondary font-weight-600">Completed Orders</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="bg-white border rounded-4 p-4 text-center shadow-sm">
                        <div class="fs-4 font-weight-800 text-emerald mb-1">${{ number_format($totalPurchase, 0) }}</div>
                        <div class="fs-7 text-secondary font-weight-600">Total Spend</div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Section -->
            <div class="bg-white border rounded-4 shadow-sm p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="font-weight-800 text-dark mb-0"><i class="fas fa-box-open text-emerald me-2"></i> Recent Orders</h5>
                    <a href="{{ route('frontend.orders.index') }}" class="btn btn-link text-emerald font-weight-700 text-decoration-none fs-7">
                        View All Orders <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

                @if(count($orders) > 0)
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 fs-7">
                            <thead class="bg-light font-weight-700 text-secondary">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(array_slice($orders, 0, 5) as $ord)
                                    <tr>
                                        <td class="font-monospace font-weight-800 text-emerald">{{ $ord['id'] }}</td>
                                        <td>{{ $ord['date'] }}</td>
                                        <td class="font-weight-800 text-dark">${{ number_format($ord['total'], 2) }}</td>
                                        <td><span class="badge bg-success font-weight-700">{{ $ord['status'] }}</span></td>
                                        <td class="text-end">
                                            <a href="{{ route('frontend.orders.show', $ord['id']) }}" class="btn btn-outline-dark btn-sm font-weight-600">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-secondary mb-0">No recent orders found.</p>
                @endif
            </div>

            <!-- Recent Quotes Section -->
            <div class="bg-white border rounded-4 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="font-weight-800 text-dark mb-0"><i class="fas fa-file-invoice-dollar text-warning me-2"></i> Submitted Bulk Quotes</h5>
                    <a href="{{ route('frontend.quotes.create') }}" class="btn b2b-btn-accent btn-sm font-weight-700">
                        + New Quote RFQ
                    </a>
                </div>

                @if(count($quotes) > 0)
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 fs-7">
                            <thead class="bg-light font-weight-700 text-secondary">
                                <tr>
                                    <th>Quote ID</th>
                                    <th>Date</th>
                                    <th>Product Requested</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quotes as $qt)
                                    <tr>
                                        <td class="font-monospace font-weight-800 text-primary">{{ $qt['id'] }}</td>
                                        <td>{{ $qt['date'] }}</td>
                                        <td class="font-weight-700">{{ $qt['product_name'] }}</td>
                                        <td>{{ $qt['quantity'] }} units</td>
                                        <td><span class="badge bg-warning text-dark font-weight-700">{{ $qt['status'] }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-secondary mb-0">No active quote requests.</p>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection
