@extends('frontend.layouts.app')

@section('title', 'My Bulk Quotes & RFQs - B2B Wholesale Portal')

@section('content')
<div class="container-fluid px-4 px-lg-5">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb fs-7">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('frontend.account') }}">B2B Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">My Bulk Quotes</li>
        </ol>
    </nav>

    <div class="row g-4">
        
        <!-- Sidebar Navigation -->
        <div class="col-lg-3">
            <div class="bg-white border rounded-4 shadow-sm p-4 sticky-top" style="top: 90px;">
                <div class="text-center mb-4 pb-3 border-bottom">
                    <div class="b2b-cat-icon mx-auto mb-2 text-primary bg-primary-subtle" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="fas fa-building"></i>
                    </div>
                    <h6 class="font-weight-800 text-dark mb-0">{{ $customer['company'] ?? 'B2B Client' }}</h6>
                    <span class="fs-7 text-secondary">{{ $customer['name'] ?? 'Procurement' }}</span>
                </div>

                <div class="list-group list-group-flush font-weight-600 fs-7">
                    <a href="{{ route('frontend.account') }}" class="list-group-item list-group-item-action border-0 rounded-3 mb-1">
                        <i class="fas fa-tachometer-alt me-2 text-primary"></i> Dashboard Overview
                    </a>
                    <a href="{{ route('frontend.orders.index') }}" class="list-group-item list-group-item-action border-0 rounded-3 mb-1">
                        <i class="fas fa-box-open me-2 text-info"></i> My Orders
                    </a>
                    <a href="{{ route('frontend.quotes.index') }}" class="list-group-item list-group-item-action border-0 rounded-3 active bg-emerald mb-1">
                        <i class="fas fa-file-invoice-dollar me-2"></i> My Bulk Quotes & RFQs
                    </a>
                    <a href="{{ route('frontend.quotes.create') }}" class="list-group-item list-group-item-action border-0 rounded-3 mb-1 text-emerald">
                        <i class="fas fa-plus-circle me-2"></i> Submit New RFQ
                    </a>
                    <a href="{{ route('frontend.account.wishlist') }}" class="list-group-item list-group-item-action border-0 rounded-3 mb-1">
                        <i class="fas fa-heart me-2 text-danger"></i> Saved Wishlist
                    </a>
                    <a href="{{ route('frontend.account.profile') }}" class="list-group-item list-group-item-action border-0 rounded-3 mb-1">
                        <i class="fas fa-id-card me-2 text-secondary"></i> Company Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content area -->
        <div class="col-lg-9">
            
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="font-weight-800 text-dark mb-1">My Bulk Quotes & Price Requests</h3>
                    <p class="text-secondary fs-7 mb-0">Track live admin price quotes, review offered discounts, and convert approved quotes to orders</p>
                </div>
                <a href="{{ route('frontend.quotes.create') }}" class="btn b2b-btn-accent font-weight-700 shadow-sm">
                    <i class="fas fa-plus me-1"></i> Submit New RFQ
                </a>
            </div>

            <!-- RFQ Summary Metrics Bar -->
            @php
                $fqTotalCount = $quotes->count();
                $fqTotalQty = $quotes->sum(function($q) { return is_object($q) ? ($q->quantity ?? 0) : ($q['quantity'] ?? 0); });
                $fqApprovedCount = $quotes->filter(fn($q) => in_array(is_object($q) ? ($q->status ?? '') : ($q['status'] ?? ''), ['quoted', 'approved', 'Approved', 'Quote Offered']))->count();
            @endphp
            <div class="row g-3 mb-4">
                <div class="col-4">
                    <div class="bg-white border rounded-4 p-3 text-center shadow-sm">
                        <div class="fs-4 font-weight-800 text-primary">{{ number_format($fqTotalCount) }}</div>
                        <div class="fs-8 text-secondary font-weight-700 uppercase">Total RFQs Submitted</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-white border rounded-4 p-3 text-center shadow-sm">
                        <div class="fs-4 font-weight-800 text-purple" style="color: #6f42c1;">{{ number_format($fqTotalQty) }}</div>
                        <div class="fs-8 text-secondary font-weight-700 uppercase">Total Requested Units</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-white border rounded-4 p-3 text-center shadow-sm">
                        <div class="fs-4 font-weight-800 text-success">{{ number_format($fqApprovedCount) }}</div>
                        <div class="fs-8 text-secondary font-weight-700 uppercase">Price Offers Ready</div>
                    </div>
                </div>
            </div>

            <!-- Flash Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show font-weight-600 fs-7 shadow-sm mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show font-weight-600 fs-7 shadow-sm mb-4" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Quotes Data Card -->
            <div class="bg-white border rounded-4 shadow-sm p-4">
                @if($quotes->count() > 0)
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 fs-7">
                            <thead class="bg-light font-weight-700 text-secondary">
                                <tr>
                                    <th class="text-nowrap">Quote ID</th>
                                    <th class="text-nowrap">Date</th>
                                    <th>Product Requested</th>
                                    <th class="text-center text-nowrap">Qty</th>
                                    <th class="text-end text-nowrap">Target / Offered</th>
                                    <th class="text-center text-nowrap">Status</th>
                                    <th class="text-end text-nowrap">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quotes as $qt)
                                    @php
                                        $qId = is_object($qt) ? ($qt->quote_number ?? $qt->id ?? '') : ($qt['quote_number'] ?? $qt['id'] ?? '');
                                        $qDate = is_object($qt) ? (isset($qt->created_at) ? (is_string($qt->created_at) ? $qt->created_at : $qt->created_at->format('Y-m-d')) : date('Y-m-d')) : ($qt['date'] ?? date('Y-m-d'));
                                        $pName = is_object($qt) ? ($qt->product_name ?? '') : ($qt['product_name'] ?? '');
                                        $pShortName = \Illuminate\Support\Str::limit($pName, 35);
                                        $qty = is_object($qt) ? ($qt->quantity ?? 0) : ($qt['quantity'] ?? 0);
                                        $targetPrice = is_object($qt) ? ($qt->target_price ?? null) : ($qt['target_price'] ?? null);
                                        $offeredPrice = is_object($qt) ? ($qt->offered_price ?? null) : ($qt['offered_price'] ?? null);
                                        $status = is_object($qt) ? ($qt->status ?? 'pending') : ($qt['status'] ?? 'pending');
                                        $message = is_object($qt) ? ($qt->message ?? '') : ($qt['message'] ?? '');
                                        $adminNotes = is_object($qt) ? ($qt->admin_notes ?? '') : ($qt['admin_notes'] ?? '');
                                        $rawId = is_object($qt) ? ($qt->id ?? $qId) : ($qt['id'] ?? $qId);
                                    @endphp
                                    <tr>
                                        <td class="font-monospace font-weight-800 text-primary text-nowrap">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quoteModal_{{ $rawId }}" class="text-primary text-decoration-none">
                                                {{ $qId }}
                                            </a>
                                        </td>
                                        <td class="text-nowrap text-secondary">{{ $qDate }}</td>
                                        <td>
                                            <div class="font-weight-700 text-dark">{{ $pShortName }}</div>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quoteModal_{{ $rawId }}" class="text-primary text-decoration-none fs-8 font-weight-600">
                                                <i class="fas fa-eye me-1"></i>View Full Request
                                            </a>
                                        </td>
                                        <td class="text-center font-weight-800 text-nowrap">{{ number_format($qty) }}</td>
                                        <td class="text-end text-nowrap">
                                            @if($offeredPrice)
                                                <div class="font-weight-800 text-success fs-6">${{ number_format($offeredPrice, 2) }} <span class="fs-8 text-secondary">/ unit</span></div>
                                                @if($targetPrice)
                                                    <div class="fs-8 text-secondary text-decoration-line-through">Target: ${{ number_format($targetPrice, 2) }}</div>
                                                @endif
                                            @elseif($targetPrice)
                                                <div class="font-weight-700 text-dark">${{ number_format($targetPrice, 2) }}</div>
                                                <div class="fs-8 text-warning font-weight-600">Requested Target</div>
                                            @else
                                                <span class="text-secondary">Open Quote</span>
                                            @endif
                                        </td>

                                        <td class="text-center text-nowrap">
                                            @if(in_array($status, ['quoted', 'approved', 'Approved', 'Quote Offered']))
                                                <span class="badge bg-success p-2 font-weight-700">
                                                    <i class="fas fa-check-circle me-1"></i> Admin Approved
                                                </span>
                                            @elseif(in_array($status, ['converted', 'Converted']))
                                                <span class="badge bg-purple text-white p-2 font-weight-700" style="background-color: #6f42c1;">
                                                    <i class="fas fa-cart-check me-1"></i> Converted to Order
                                                </span>
                                            @elseif(in_array($status, ['rejected', 'Rejected']))
                                                <span class="badge bg-danger p-2 font-weight-700">
                                                    <i class="fas fa-times-circle me-1"></i> Declined
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark p-2 font-weight-700">
                                                    <i class="fas fa-clock me-1"></i> Under Review
                                                </span>
                                            @endif
                                        </td>

                                        <td class="text-end text-nowrap">
                                            <div class="d-flex items-center justify-content-end gap-1">
                                                <button type="button" class="btn btn-outline-secondary btn-sm font-weight-600" data-bs-toggle="modal" data-bs-target="#quoteModal_{{ $rawId }}" title="View Request Details">
                                                    <i class="fas fa-eye"></i> Details
                                                </button>

                                                @if(in_array($status, ['quoted', 'approved', 'Approved', 'Quote Offered']))
                                                    <form action="{{ route('frontend.quotes.accept', $rawId) }}" method="POST" class="d-inline" onsubmit="return confirm('Accept this quote offer and place a Wholesale Order for {{ number_format($qty) }} units?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-emerald btn-sm font-weight-700 shadow-sm">
                                                            <i class="fas fa-cart-plus me-1"></i> Accept & Order
                                                        </button>
                                                    </form>
                                                @elseif(in_array($status, ['converted', 'Converted']))
                                                    <span class="text-success font-weight-700 fs-8 ms-1"><i class="fas fa-check me-1"></i> Order Placed</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Quote Details Modal for this row -->
                                    <div class="modal fade" id="quoteModal_{{ $rawId }}" tabindex="-1" aria-labelledby="quoteModalLabel_{{ $rawId }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content border-0 rounded-4 shadow-lg">
                                                <div class="modal-header bg-light border-bottom p-4">
                                                    <div>
                                                        <h5 class="modal-title font-weight-800 text-dark mb-1" id="quoteModalLabel_{{ $rawId }}">
                                                            Quote Request #{{ $qId }}
                                                        </h5>
                                                        <span class="fs-7 text-secondary">Submitted on {{ $qDate }}</span>
                                                    </div>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="row g-3 mb-4">
                                                        <div class="col-md-6">
                                                            <div class="bg-light p-3 rounded-3 border">
                                                                <span class="fs-8 text-secondary font-weight-700 uppercase">Product Name Requested</span>
                                                                <h6 class="font-weight-800 text-dark mt-1 mb-0">{{ $pName }}</h6>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="bg-light p-3 rounded-3 border text-center">
                                                                <span class="fs-8 text-secondary font-weight-700 uppercase">Quantity</span>
                                                                <h6 class="font-weight-800 text-dark mt-1 mb-0">{{ number_format($qty) }} units</h6>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="bg-light p-3 rounded-3 border text-center">
                                                                <span class="fs-8 text-secondary font-weight-700 uppercase">Current Status</span>
                                                                <div class="mt-1">
                                                                    @if(in_array($status, ['quoted', 'approved', 'Approved', 'Quote Offered']))
                                                                        <span class="badge bg-success font-weight-700">Approved</span>
                                                                    @elseif(in_array($status, ['converted', 'Converted']))
                                                                        <span class="badge bg-purple text-white font-weight-700" style="background-color: #6f42c1;">Converted</span>
                                                                    @elseif(in_array($status, ['rejected', 'Rejected']))
                                                                        <span class="badge bg-danger font-weight-700">Declined</span>
                                                                    @else
                                                                        <span class="badge bg-warning text-dark font-weight-700">Under Review</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row g-3 mb-4">
                                                        <div class="col-md-6">
                                                            <div class="p-3 rounded-3 border bg-primary-subtle text-primary">
                                                                <span class="fs-8 font-weight-700 uppercase">Target Price Requested</span>
                                                                <h5 class="font-weight-800 mt-1 mb-0">{{ $targetPrice ? '$' . number_format($targetPrice, 2) . ' / unit' : 'Open Target' }}</h5>
                                                                @if($targetPrice)
                                                                    <div class="fs-8 font-weight-700 mt-1">Est. Target Subtotal: ${{ number_format($targetPrice * $qty, 2) }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="p-3 rounded-3 border bg-success-subtle text-success">
                                                                <span class="fs-8 font-weight-700 uppercase">Admin Offered Price</span>
                                                                <h5 class="font-weight-800 mt-1 mb-0">{{ $offeredPrice ? '$' . number_format($offeredPrice, 2) . ' / unit' : 'Pending Admin Offer' }}</h5>
                                                                @if($offeredPrice)
                                                                    <div class="fs-8 font-weight-700 mt-1">Est. Total Offer: ${{ number_format($offeredPrice * $qty, 2) }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if($message)
                                                        <div class="mb-3">
                                                            <h6 class="fs-7 font-weight-800 text-dark mb-2">Customer Message & Requirements</h6>
                                                            <div class="p-3 bg-light rounded-3 text-secondary fs-7 font-weight-500 whitespace-pre-line">{{ $message }}</div>
                                                        </div>
                                                    @endif

                                                    @if($adminNotes)
                                                        <div class="mb-3">
                                                            <h6 class="fs-7 font-weight-800 text-success mb-2"><i class="fas fa-comment-dots me-1"></i> Admin Notes & Response</h6>
                                                            <div class="p-3 bg-success-subtle text-success-emphasis rounded-3 fs-7 font-weight-500 whitespace-pre-line">{{ $adminNotes }}</div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer bg-light border-top p-3">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm font-weight-700" data-bs-dismiss="modal">Close</button>
                                                    @if(in_array($status, ['quoted', 'approved', 'Approved', 'Quote Offered']))
                                                        <form action="{{ route('frontend.quotes.accept', $rawId) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-emerald btn-sm font-weight-700 shadow-sm">
                                                                <i class="fas fa-cart-plus me-1"></i> Accept & Order Now (${{ number_format(($offeredPrice ?? $targetPrice ?? 0) * $qty, 2) }})
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-invoice-dollar text-secondary opacity-25 mb-3" style="font-size: 3.5rem;"></i>
                        <h6 class="font-weight-800 text-dark">No Quote Requests Found</h6>
                        <p class="text-secondary fs-7 mb-3">You haven't submitted any bulk quote inquiries yet.</p>
                        <a href="{{ route('frontend.quotes.create') }}" class="btn b2b-btn-accent btn-sm font-weight-700">
                            Submit Your First RFQ
                        </a>
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection
