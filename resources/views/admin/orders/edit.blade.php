@extends('admin.layout.app')

@section('title', 'Edit Order #' . $order->order_number . ' - Admin Workspace')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-3">
                <span>Edit Order #{{ $order->order_number }}</span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $order->status_badge }}">
                    {{ ucfirst($order->status) }}
                </span>
            </h1>
            <p class="text-sm text-slate-500 mt-1">Modify order details, items, fulfillment status, and payment terms</p>
        </div>
        <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Details</span>
        </a>
    </div>

    <!-- Main Order Form -->
    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" id="orderForm" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Order Basic Details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Order Number</label>
                <input 
                    type="text" 
                    value="{{ $order->order_number }}" 
                    disabled
                    class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-semibold text-slate-500 cursor-not-allowed"
                />
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Wholesale Customer / Partner *</label>
                <select name="customer_id" id="customerSelect" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none" onchange="calculateTotals()">
                    @foreach($customers as $cust)
                        <option value="{{ $cust->id }}" data-discount="{{ $cust->wholesale_discount }}" data-terms="{{ $cust->payment_terms }}" {{ old('customer_id', $order->customer_id) == $cust->id ? 'selected' : '' }}>
                            {{ $cust->name }} ({{ $cust->company_name ?? 'Individual' }}) - Tier: {{ $cust->tier }} ({{ $cust->wholesale_discount }}% Discount)
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Payment Terms *</label>
                <select name="payment_terms" id="paymentTermsSelect" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none">
                    @foreach(['Net 30', 'Net 60', 'Net 15', 'Bank Transfer', 'Cash on Delivery', 'Prepaid', 'Credit Terms'] as $term)
                        <option value="{{ $term }}" {{ old('payment_terms', $order->payment_terms) === $term ? 'selected' : '' }}>{{ $term }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Fulfillment Status *</label>
                <select name="status" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none">
                    <option value="pending" {{ old('status', $order->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ old('status', $order->status) === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ old('status', $order->status) === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ old('status', $order->status) === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ old('status', $order->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Payment Status *</label>
                <select name="payment_status" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none">
                    <option value="unpaid" {{ old('payment_status', $order->payment_status) === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="partially_paid" {{ old('payment_status', $order->payment_status) === 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                    <option value="paid" {{ old('payment_status', $order->payment_status) === 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
        </div>

        <!-- Shipping & Notes -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 border-t border-slate-100 pt-5">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Delivery & Shipping Address</label>
                <textarea name="shipping_address" rows="2" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:border-indigo-500 focus:outline-none">{{ old('shipping_address', $order->shipping_address) }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Order Notes & Logistics Details</label>
                <textarea name="notes" rows="2" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:border-indigo-500 focus:outline-none">{{ old('notes', $order->notes) }}</textarea>
            </div>
        </div>

        <!-- Order Items Section -->
        <div class="border-t border-slate-100 pt-5 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Bulk Order Stock Items</h3>
                    <p class="text-xs text-slate-500">Modify products included in this order</p>
                </div>
                <button type="button" onclick="addItemRow()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 text-xs font-bold rounded-lg border border-indigo-200 transition-colors">
                    <i class="fas fa-plus"></i>
                    <span>Add Item Product</span>
                </button>
            </div>

            <!-- Items Table/List Container -->
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3" id="itemsContainer">
                @foreach($order->items as $index => $item)
                    <div class="item-row grid grid-cols-1 sm:grid-cols-12 gap-3 items-center bg-white p-3 rounded-lg border border-slate-200/80 shadow-2xs" data-row="{{ $index }}">
                        <div class="sm:col-span-5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Stock Item Product</label>
                            <select name="items[{{ $index }}][stock_id]" class="stock-select w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:bg-white focus:border-indigo-500" onchange="updateProductDetails(this, {{ $index }})">
                                <option value="">-- Select Product Stock --</option>
                                @foreach($stocks as $stock)
                                    <option value="{{ $stock->id }}" data-name="{{ $stock->product_name }}" data-price="{{ $stock->unit_price }}" {{ $item->stock_id == $stock->id ? 'selected' : '' }}>
                                        {{ $stock->product_name }} (Stock: {{ $stock->quantity }}) - ${{ number_format($stock->unit_price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-3">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Product Description / Name *</label>
                            <input type="text" name="items[{{ $index }}][product_name]" value="{{ $item->product_name }}" required class="product-name-input w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:bg-white focus:border-indigo-500" />
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Qty *</label>
                            <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" min="1" required class="qty-input w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:bg-white focus:border-indigo-500" oninput="calculateTotals()" />
                        </div>
                        <div class="sm:col-span-2 flex items-center gap-2">
                            <div class="grow">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Unit Price ($) *</label>
                                <input type="number" step="0.01" name="items[{{ $index }}][unit_price]" value="{{ $item->unit_price }}" min="0" required class="price-input w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:bg-white focus:border-indigo-500" oninput="calculateTotals()" />
                            </div>
                            <button type="button" onclick="removeItemRow(this)" class="mt-4 p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Remove Item">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Totals & Calculations Section -->
        <div class="border-t border-slate-100 pt-5 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="text-xs text-slate-500 max-w-sm space-y-1">
                <p><i class="fas fa-info-circle text-indigo-500 mr-1"></i> Customer discount & tax calculations update automatically based on customer tier and wholesale pricing rules.</p>
            </div>
            <div class="w-full sm:w-72 bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2.5 text-xs">
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal:</span>
                    <span class="font-bold text-slate-900" id="subtotalDisplay">${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Wholesale Discount:</span>
                    <span class="font-bold text-emerald-600" id="discountDisplay">-${{ number_format($order->discount_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Estimated Tax (5%):</span>
                    <span class="font-bold text-slate-900" id="taxDisplay">${{ number_format($order->tax_amount, 2) }}</span>
                </div>
                <div class="border-t border-slate-200 pt-2 flex justify-between text-sm font-black text-slate-900">
                    <span>Grand Total:</span>
                    <span class="text-indigo-600" id="grandTotalDisplay">${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Action Submit Buttons -->
        <div class="border-t border-slate-100 pt-5 flex items-center justify-end gap-3">
            <a href="{{ route('admin.orders.show', $order->id) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs hover:shadow transition-all flex items-center gap-2">
                <i class="fas fa-save"></i>
                <span>Update Wholesale Order</span>
            </button>
        </div>
    </form>
</div>

<script>
let rowCounter = {{ count($order->items) }};

function addItemRow() {
    const container = document.getElementById('itemsContainer');
    const newRow = document.createElement('div');
    newRow.className = 'item-row grid grid-cols-1 sm:grid-cols-12 gap-3 items-center bg-white p-3 rounded-lg border border-slate-200/80 shadow-2xs';
    newRow.setAttribute('data-row', rowCounter);
    
    newRow.innerHTML = `
        <div class="sm:col-span-5">
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Stock Item Product</label>
            <select name="items[\${rowCounter}][stock_id]" class="stock-select w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:bg-white focus:border-indigo-500" onchange="updateProductDetails(this, \${rowCounter})">
                <option value="">-- Select Product Stock --</option>
                @foreach($stocks as $stock)
                    <option value="{{ $stock->id }}" data-name="{{ $stock->product_name }}" data-price="{{ $stock->unit_price }}">
                        {{ $stock->product_name }} (Stock: {{ $stock->quantity }}) - \${{ number_format($stock->unit_price, 2) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="sm:col-span-3">
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Product Description / Name *</label>
            <input type="text" name="items[\${rowCounter}][product_name]" placeholder="e.g. Dell XPS 15 Laptop" required class="product-name-input w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:bg-white focus:border-indigo-500" />
        </div>
        <div class="sm:col-span-2">
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Qty *</label>
            <input type="number" name="items[\${rowCounter}][quantity]" value="1" min="1" required class="qty-input w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:bg-white focus:border-indigo-500" oninput="calculateTotals()" />
        </div>
        <div class="sm:col-span-2 flex items-center gap-2">
            <div class="grow">
                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Unit Price ($) *</label>
                <input type="number" step="0.01" name="items[\${rowCounter}][unit_price]" value="0.00" min="0" required class="price-input w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:bg-white focus:border-indigo-500" oninput="calculateTotals()" />
            </div>
            <button type="button" onclick="removeItemRow(this)" class="mt-4 p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Remove Item">
                <i class="fas fa-trash text-xs"></i>
            </button>
        </div>
    `;
    
    container.appendChild(newRow);
    rowCounter++;
}

function removeItemRow(button) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length <= 1) {
        alert('At least one item is required in a wholesale order.');
        return;
    }
    button.closest('.item-row').remove();
    calculateTotals();
}

function updateProductDetails(select, rowIndex) {
    const selectedOption = select.options[select.selectedIndex];
    const row = select.closest('.item-row');
    const nameInput = row.querySelector('.product-name-input');
    const priceInput = row.querySelector('.price-input');

    if (selectedOption && selectedOption.value) {
        nameInput.value = selectedOption.getAttribute('data-name');
        priceInput.value = selectedOption.getAttribute('data-price');
    }
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0;
    const rows = document.querySelectorAll('.item-row');

    rows.forEach(row => {
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        subtotal += (qty * price);
    });

    const customerSelect = document.getElementById('customerSelect');
    const selectedCustomer = customerSelect.options[customerSelect.selectedIndex];
    const discountPercent = selectedCustomer ? (parseFloat(selectedCustomer.getAttribute('data-discount')) || 0) : 0;

    const discountAmount = (subtotal * discountPercent) / 100;
    const taxAmount = (subtotal - discountAmount) * 0.05;
    const grandTotal = subtotal - discountAmount + taxAmount;

    document.getElementById('subtotalDisplay').innerText = '$' + subtotal.toFixed(2);
    document.getElementById('discountDisplay').innerText = '-$' + discountAmount.toFixed(2);
    document.getElementById('taxDisplay').innerText = '$' + taxAmount.toFixed(2);
    document.getElementById('grandTotalDisplay').innerText = '$' + grandTotal.toFixed(2);
}
</script>
@endsection
