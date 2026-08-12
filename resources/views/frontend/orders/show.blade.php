@extends('frontend.layouts.app')

@section('title', 'Order Details - ' . $order['id'])

@push('styles')
<style>
@media print {
    /* Hide topbar, headers, footers, navigation, breadcrumbs, and print/back buttons */
    header, 
    footer, 
    nav, 
    .b2b-topbar, 
    .b2b-navbar, 
    .breadcrumb, 
    .d-print-none, 
    .no-print, 
    .btn, 
    button,
    #mobileMenuOffcanvas,
    .toast-container {
        display: none !important;
    }

    body, html {
        background: #ffffff !important;
        color: #000000 !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }

    main {
        padding: 0 !important;
        margin: 0 !important;
    }

    .container, .container-fluid {
        max-width: 100% !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .printable-invoice-card {
        border: 1px solid #cbd5e1 !important;
        box-shadow: none !important;
        padding: 24px !important;
        border-radius: 12px !important;
        margin: 0 !important;
        background: #ffffff !important;
    }

    /* Preserve colors and badges when printing */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .table {
        border-color: #cbd5e1 !important;
    }

    .bg-light {
        background-color: #f8fafc !important;
    }

    .table-responsive {
        overflow: visible !important;
    }
    
    tr {
        page-break-inside: avoid;
    }
}
</style>
@endpush

@section('content')
@php
    $isObject = is_object($order);
    $orderNum = $isObject ? $order->order_number : ($order['id'] ?? '');
    $orderDate = $isObject ? ($order->order_date ? $order->order_date->format('F d, Y \a\t h:i A') : $order->created_at->format('F d, Y')) : ($order['date'] ?? '');
    $companyName = $isObject ? ($order->customer->company_name ?? 'Individual') : ($order['company'] ?? '');
    $contactName = $isObject ? ($order->customer->name ?? '') : ($order['contact_person'] ?? '');
    $email = $isObject ? ($order->customer->email ?? '') : ($order['email'] ?? '');
    $phone = $isObject ? ($order->customer->phone ?? '') : ($order['phone'] ?? '');
    $shippingAddr = $isObject ? $order->shipping_address : ($order['shipping_address'] ?? '');
    $subtotal = $isObject ? $order->subtotal : ($order['subtotal'] ?? 0);
    $tax = $isObject ? $order->tax_amount : ($order['tax'] ?? 0);
    $discount = $isObject ? $order->discount_amount : 0;
    $shipping = $isObject ? 0 : ($order['shipping'] ?? 0);
    $totalAmount = $isObject ? $order->total_amount : ($order['total'] ?? 0);
    $paymentMethod = $isObject ? ($order->payment_terms ?? 'Standard') : ($order['payment_method'] ?? 'Bank Transfer');
    $itemsList = $isObject ? $order->items : ($order['items'] ?? []);
@endphp

<div class="container-fluid px-4 px-lg-5">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4 d-print-none">
        <ol class="breadcrumb fs-7">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('frontend.orders.index') }}">My Orders</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $orderNum }}</li>
        </ol>
    </nav>

    <!-- Header Card / Printable Invoice -->
    <div class="bg-white border rounded-4 shadow-sm p-4 mb-4 printable-invoice-card">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 border-bottom pb-3 mb-4">
            <div>
                <span class="badge bg-emerald-subtle text-emerald border border-emerald font-weight-700 px-3 py-1 rounded-pill mb-1">Commercial Wholesale Invoice</span>
                <h1 class="h2 font-weight-800 text-dark mb-0">Order: <span class="text-emerald font-monospace">{{ $orderNum }}</span></h1>
                <div class="fs-7 text-secondary">Placed on {{ $orderDate }}</div>
            </div>
            <div class="d-flex gap-2 d-print-none">
                @if(strtolower($isObject ? ($order->status ?? '') : ($order['status'] ?? '')) === 'pending')
                    <form action="{{ route('frontend.orders.cancel', $orderNum) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this order request?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger font-weight-600">
                            <i class="fas fa-times-circle me-1"></i> Cancel Request
                        </button>
                    </form>
                @endif
                <button onclick="window.print()" class="btn btn-outline-dark font-weight-600">
                    <i class="fas fa-print me-1"></i> Print Invoice
                </button>
                <a href="{{ route('frontend.orders.index') }}" class="btn btn-outline-secondary font-weight-600">
                    <i class="fas fa-arrow-left me-1"></i> Back to Orders
                </a>
            </div>
        </div>

        <!-- Visual Order Tracking Timeline -->
        <div class="mb-5 px-md-4">
            <h6 class="text-uppercase font-weight-800 text-secondary fs-7 mb-4 text-center">Visual Order Status Tracker</h6>
            
            @php
                $statusKey = strtolower($isObject ? ($order->status ?? 'pending') : ($order['status'] ?? 'pending'));
                $isCancelled = ($statusKey === 'cancelled');

                $timelineSteps = [
                    ['key' => 'pending', 'label' => 'Order Placed', 'icon' => 'fas fa-file-invoice'],
                    ['key' => 'processing', 'label' => 'Processing', 'icon' => 'fas fa-cogs'],
                    ['key' => 'shipped', 'label' => 'Shipped / In Transit', 'icon' => 'fas fa-truck-fast'],
                    ['key' => 'delivered', 'label' => 'Delivered', 'icon' => 'fas fa-warehouse']
                ];

                $stepMap = [
                    'pending' => 0,
                    'confirmed' => 0,
                    'processing' => 1,
                    'packed' => 1,
                    'shipped' => 2,
                    'in_transit' => 2,
                    'delivered' => 3,
                    'completed' => 3,
                ];

                $currentStepIndex = $stepMap[$statusKey] ?? 0;
                $progressPercentage = min(100, max(0, ($currentStepIndex / (count($timelineSteps) - 1)) * 100));
            @endphp

            @if($isCancelled)
                <div class="alert alert-danger d-flex align-items-center gap-3 rounded-3 p-3 mb-4">
                    <i class="fas fa-ban fs-3 text-danger"></i>
                    <div>
                        <h6 class="font-weight-800 mb-0">Order #{{ $orderNum }} Has Been Cancelled</h6>
                        <div class="fs-7">This wholesale order was cancelled by the administrator or buyer request.</div>
                    </div>
                </div>
            @else
                <div class="b2b-timeline">
                    <div class="b2b-timeline-progress" style="width: {{ $progressPercentage }}%;"></div>
                    @foreach($timelineSteps as $index => $step)
                        @php
                            $isCompleted = $index <= $currentStepIndex;
                            $isActive = $index === $currentStepIndex;
                        @endphp
                        <div class="b2b-timeline-step {{ $isCompleted ? 'completed' : '' }} {{ $isActive ? 'active' : '' }}">
                            <div class="b2b-step-icon">
                                <i class="{{ $step['icon'] }}"></i>
                            </div>
                            <div class="b2b-step-label">{{ $step['label'] }}</div>
                            @if($isCompleted)
                                <div class="fs-8 text-success font-weight-700 mt-1"><i class="fas fa-check"></i> {{ $isActive ? 'Current Status' : 'Step Done' }}</div>
                            @else
                                <div class="fs-8 text-secondary mt-1"><i class="far fa-clock"></i> Pending</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Line Items Table -->
        <h5 class="font-weight-800 text-dark mb-3">Order Items Breakdown</h5>
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle">
                <thead class="bg-light fs-7 text-uppercase font-weight-700">
                    <tr>
                        <th>Item Description</th>
                        <th>SKU</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($itemsList as $item)
                        @php
                            $itemObj = is_object($item);
                            $itemName = $itemObj ? $item->product_name : ($item['name'] ?? '');
                            $itemSku = $itemObj ? ($item->stock->sku ?? 'N/A') : ($item['sku'] ?? 'N/A');
                            $itemImage = $itemObj ? ($item->stock->image ?? asset('frontend/images/product-placeholder.png')) : ($item['image'] ?? asset('frontend/images/product-placeholder.png'));
                            $itemQty = $itemObj ? $item->quantity : ($item['quantity'] ?? 1);
                            $itemPrice = $itemObj ? $item->unit_price : ($item['price'] ?? 0);
                            $itemSubtotal = $itemObj ? $item->subtotal : ($item['subtotal'] ?? 0);
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $itemImage }}" alt="" class="rounded border p-1" style="width: 50px; height: 50px; object-fit: contain;">
                                    <span class="font-weight-700 text-dark">{{ $itemName }}</span>
                                </div>
                            </td>
                            <td class="font-monospace fs-7 text-secondary">{{ $itemSku }}</td>
                            <td class="font-weight-700 text-center">{{ $itemQty }} units</td>
                            <td class="font-weight-700 text-dark">${{ number_format($itemPrice, 2) }}</td>
                            <td class="font-weight-800 text-emerald">${{ number_format($itemSubtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals & Addresses -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="p-3 bg-light rounded border h-100 fs-7">
                    <h6 class="font-weight-800 text-dark mb-2"><i class="fas fa-building text-primary me-2"></i> Buyer Information</h6>
                    <div>Company: <strong>{{ $companyName }}</strong></div>
                    <div>Contact: {{ $contactName }}</div>
                    <div>Email: {{ $email }}</div>
                    <div>Phone: {{ $phone }}</div>
                    <hr class="my-2">
                    <div><strong>Shipping Address:</strong> {{ $shippingAddr }}</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded border h-100 fs-7">
                    <h6 class="font-weight-800 text-dark mb-2"><i class="fas fa-file-invoice-dollar text-emerald me-2"></i> Financial Summary</h6>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Items Subtotal:</span>
                        <span class="font-weight-700">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    @if($discount > 0)
                    <div class="d-flex justify-content-between mb-1 text-success font-weight-700">
                        <span>Wholesale Discount:</span>
                        <span>-${{ number_format($discount, 2) }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-1">
                        <span>Sales Tax:</span>
                        <span class="font-weight-700">${{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Freight Shipping:</span>
                        <span class="font-weight-700">${{ number_format($shipping, 2) }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between h5 font-weight-800 mb-2">
                        <span>Grand Total:</span>
                        <span class="text-emerald">${{ number_format($totalAmount, 2) }}</span>
                    </div>
                    <div>Payment Method / Terms: <strong>{{ $paymentMethod }}</strong></div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
