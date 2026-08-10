@extends('frontend.layouts.app')

@section('title', 'Order Details - ' . $order['id'])

@section('content')
<div class="container">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb fs-7">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('frontend.orders.index') }}">My Orders</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $order['id'] }}</li>
        </ol>
    </nav>

    <!-- Header Card -->
    <div class="bg-white border rounded-4 shadow-sm p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 border-bottom pb-3 mb-4">
            <div>
                <span class="badge bg-emerald-subtle text-emerald border border-emerald font-weight-700 px-3 py-1 rounded-pill mb-1">Commercial Wholesale Invoice</span>
                <h1 class="h2 font-weight-800 text-dark mb-0">Order: <span class="text-emerald font-monospace">{{ $order['id'] }}</span></h1>
                <div class="fs-7 text-secondary">Placed on {{ $order['date'] }}</div>
            </div>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-outline-dark font-weight-600">
                    <i class="fas fa-print me-1"></i> Print Invoice
                </button>
                <a href="{{ route('frontend.orders.index') }}" class="btn btn-outline-secondary font-weight-600">
                    <i class="fas fa-arrow-left me-1"></i> Back to Orders
                </a>
            </div>
        </div>

        <!-- 14. Visual Order Tracking Timeline -->
        <div class="mb-5 px-md-4">
            <h6 class="text-uppercase font-weight-800 text-secondary fs-7 mb-4 text-center">Visual Order Status Tracker</h6>
            
            @php
                $timelineSteps = [
                    ['key' => 'Pending', 'label' => 'Order Placed', 'icon' => 'fas fa-file-invoice'],
                    ['key' => 'Confirmed', 'label' => 'Confirmed', 'icon' => 'fas fa-check-circle'],
                    ['key' => 'Processing', 'label' => 'Processing', 'icon' => 'fas fa-cogs'],
                    ['key' => 'Shipped', 'label' => 'Shipped / In Transit', 'icon' => 'fas fa-truck-fast'],
                    ['key' => 'Delivered', 'label' => 'Delivered', 'icon' => 'fas fa-warehouse']
                ];

                // Calculate progress bar percentage based on step index
                $progressPercentage = min(100, max(0, ($currentStepIndex / (count($timelineSteps) - 1)) * 100));
            @endphp

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
                            <div class="fs-8 text-success font-weight-700 mt-1"><i class="fas fa-check"></i> Step Done</div>
                        @endif
                    </div>
                @endforeach
            </div>
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
                    @foreach($order['items'] as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $item['image'] }}" alt="" class="rounded border p-1" style="width: 50px; height: 50px; object-fit: contain;">
                                    <span class="font-weight-700 text-dark">{{ $item['name'] }}</span>
                                </div>
                            </td>
                            <td class="font-monospace fs-7 text-secondary">{{ $item['sku'] }}</td>
                            <td class="font-weight-700 text-center">{{ $item['quantity'] }} units</td>
                            <td class="font-weight-700 text-dark">${{ number_format($item['price'], 2) }}</td>
                            <td class="font-weight-800 text-emerald">${{ number_format($item['subtotal'], 2) }}</td>
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
                    <div>Company: <strong>{{ $order['company'] ?? 'ABC Technology Solutions Co., Ltd.' }}</strong></div>
                    <div>Contact: {{ $order['contact_person'] ?? 'John Doe' }}</div>
                    <div>Email: {{ $order['email'] ?? 'john@example.com' }}</div>
                    <div>Phone: {{ $order['phone'] ?? '+1 (555) 234-5678' }}</div>
                    <hr class="my-2">
                    <div><strong>Shipping Address:</strong> {{ $order['shipping_address'] }}</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded border h-100 fs-7">
                    <h6 class="font-weight-800 text-dark mb-2"><i class="fas fa-file-invoice-dollar text-emerald me-2"></i> Financial Summary</h6>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Items Subtotal:</span>
                        <span class="font-weight-700">${{ number_format($order['subtotal'], 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Sales Tax (10%):</span>
                        <span class="font-weight-700">${{ number_format($order['tax'], 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Freight Shipping:</span>
                        <span class="font-weight-700">${{ number_format($order['shipping'], 2) }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between h5 font-weight-800 mb-2">
                        <span>Grand Total:</span>
                        <span class="text-emerald">${{ number_format($order['total'], 2) }}</span>
                    </div>
                    <div>Payment Method: <strong>{{ $order['payment_method'] ?? 'Bank Transfer' }}</strong></div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
