@extends('frontend.layouts.app')

@section('title', 'Order History - Apex IT Wholesale')

@section('content')
<div class="container">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb fs-7">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('frontend.account') }}">Account</a></li>
            <li class="breadcrumb-item active" aria-current="page">My Orders</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 font-weight-800 text-dark mb-0">
            <i class="fas fa-box-open text-emerald me-2"></i> Commercial Order History
        </h1>
        <a href="{{ route('frontend.products.index') }}" class="btn b2b-btn-primary font-weight-700">
            <i class="fas fa-plus me-1"></i> New Order
        </a>
    </div>

    @if(count($orders) > 0)
        <div class="bg-white border rounded-4 shadow-sm p-4 mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light fs-7 text-uppercase font-weight-700 text-secondary">
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Items Count</th>
                            <th>Total Amount</th>
                            <th>Payment Terms</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            @php
                                $isObject = is_object($order);
                                $orderId = $isObject ? $order->id : ($order['id'] ?? '');
                                $orderNum = $isObject ? $order->order_number : ($order['id'] ?? '');
                                $orderDate = $isObject ? ($order->order_date ? $order->order_date->format('Y-m-d') : $order->created_at->format('Y-m-d')) : ($order['date'] ?? '');
                                $itemsCount = $isObject ? $order->items->count() : count($order['items'] ?? []);
                                $totalAmount = $isObject ? $order->total_amount : ($order['total'] ?? 0);
                                $paymentMethod = $isObject ? ($order->payment_terms ?? 'Standard') : ($order['payment_method'] ?? 'Bank Transfer');
                                $status = $isObject ? ucfirst($order->status) : ($order['status'] ?? 'Pending');

                                $statusBg = match(strtolower($status)) {
                                    'delivered', 'completed' => 'bg-success',
                                    'shipped' => 'bg-info',
                                    'processing', 'packed' => 'bg-warning text-dark',
                                    'confirmed', 'pending' => 'bg-primary',
                                    'cancelled' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <tr>
                                <td class="font-monospace font-weight-800 text-emerald">
                                    <a href="{{ route('frontend.orders.show', $orderId) }}" class="text-emerald text-decoration-none">
                                        {{ $orderNum }}
                                    </a>
                                </td>
                                <td class="font-weight-600 text-secondary fs-7">{{ $orderDate }}</td>
                                <td class="font-weight-600 fs-7">{{ $itemsCount }} line items</td>
                                <td class="font-weight-800 text-dark fs-6">${{ number_format($totalAmount, 2) }}</td>
                                <td class="fs-7 text-secondary">{{ $paymentMethod }}</td>
                                <td>
                                    <span class="badge {{ $statusBg }} px-3 py-2 font-weight-700 rounded-pill">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('frontend.orders.show', $orderId) }}" class="btn btn-outline-dark btn-sm font-weight-600">
                                        <i class="fas fa-eye me-1"></i> Track Order
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white border rounded-4 p-5 text-center my-4">
            <i class="fas fa-box-open text-secondary fs-1 mb-3"></i>
            <h3 class="font-weight-800">No Orders Found</h3>
            <p class="text-secondary">You haven't placed any wholesale orders yet.</p>
            <a href="{{ route('frontend.products.index') }}" class="btn b2b-btn-accent font-weight-700">
                Browse Products
            </a>
        </div>
    @endif

</div>
@endsection
