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
            <a href="{{ $order->order_source === 'frontend' ? route('admin.orders.registered') : route('admin.orders.show', $order->id) }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-colors">
                <i class="fas fa-arrow-left"></i>
                <span>Back</span>
            </a>
        </div>

        <!-- Main Order Form -->
        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" id="orderForm"
            class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Order Basic Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Order Number</label>
                    <input type="text" value="{{ $order->order_number }}" disabled
                        class="w-full px-3.5 py-2.5 bg-slate-100 border-2 border-slate-300 rounded-xl text-xs font-bold font-mono text-slate-500 cursor-not-allowed" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Order Source</label>
                    <div
                        class="px-3.5 py-2.5 bg-slate-100 border-2 border-slate-300 rounded-xl text-xs font-bold text-slate-700 flex items-center gap-2">
                        <i
                            class="fas {{ $order->order_source === 'frontend' ? 'fa-globe text-purple-600' : 'fa-handshake text-indigo-600' }}"></i>
                        <span>{{ $order->order_source === 'frontend' ? 'Frontend Order' : 'Partner Order' }}</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Wholesale Customer / Partner *</label>
                    <select name="customer_id" id="customerSelect" required
                        class="w-full px-3.5 py-2.5 bg-white border-2 border-slate-300 rounded-xl text-xs font-semibold text-slate-900 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs"
                        onchange="calculateTotals()">
                        @foreach ($customers as $cust)
                            <option value="{{ $cust->id }}" data-discount="{{ $cust->wholesale_discount }}"
                                data-terms="{{ $cust->payment_terms }}"
                                {{ old('customer_id', $order->customer_id) == $cust->id ? 'selected' : '' }}>
                                {{ $cust->name }} ({{ $cust->company_name ?? 'Individual' }}) - Tier:
                                {{ $cust->tier }} ({{ $cust->wholesale_discount }}% Discount)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Payment Method *</label>
                    <select name="payment_method" id="paymentMethodSelect" required
                        class="w-full px-3.5 py-2.5 bg-white border-2 border-slate-300 rounded-xl text-xs font-semibold text-slate-900 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs">
                        <option value="">-- Choose Payment Method --</option>
                        @foreach($paymentMethods as $pm)
                            <option value="{{ $pm }}" {{ old('payment_method', $order->payment_method) == $pm ? 'selected' : '' }}>{{ $pm }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Fulfillment Status *</label>
                    <select name="status" required
                        class="w-full px-3.5 py-2.5 bg-white border-2 border-slate-300 rounded-xl text-xs font-semibold text-slate-900 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs">
                        <option value="pending" {{ old('status', $order->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ old('status', $order->status) === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ old('status', $order->status) === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ old('status', $order->status) === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ old('status', $order->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Payment Status *</label>
                    <select name="payment_status" required
                        class="w-full px-3.5 py-2.5 bg-white border-2 border-slate-300 rounded-xl text-xs font-semibold text-slate-900 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs">
                        <option value="unpaid" {{ old('payment_status', $order->payment_status) === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="partially_paid" {{ old('payment_status', $order->payment_status) === 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                        <option value="paid" {{ old('payment_status', $order->payment_status) === 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
            </div>

            <!-- Shipping & Notes -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 border-t border-slate-100 pt-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Delivery & Shipping
                        Address</label>
                    <textarea name="shipping_address" rows="2"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:border-indigo-500 focus:outline-none">{{ old('shipping_address', $order->shipping_address) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Order Notes &
                        Logistics Details</label>
                    <textarea name="notes" rows="2"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:border-indigo-500 focus:outline-none">{{ old('notes', $order->notes) }}</textarea>
                </div>
            </div>

            <!-- Order Items Section -->
            <div class="border-t border-slate-100 pt-5 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Bulk Order Stock Items</h3>
                        <p class="text-xs text-slate-500">Modify products included in this order</p>
                    </div>
                    <button type="button" onclick="addItemRow()"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 text-xs font-bold rounded-lg border border-indigo-200 transition-colors">
                        <i class="fas fa-plus"></i>
                        <span>Add Item Product</span>
                    </button>
                </div>

                <!-- Items Table Header & List Container -->
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3 overflow-x-auto">
                    <!-- Stock Warning Banner -->
                    <div id="stockAlertBanner"
                        class="hidden bg-rose-50 border-l-4 border-rose-500 p-3 rounded-r-xl text-xs text-rose-800 font-semibold flex items-center gap-2">
                        <i class="fas fa-triangle-exclamation text-rose-500 text-sm"></i>
                        <span id="stockAlertBannerText"></span>
                    </div>

                    <!-- Table Column Headers -->
                    <div
                        class="flex flex-row items-center gap-3 px-3 py-2 bg-slate-200/70 rounded-lg text-[11px] font-bold text-slate-700 uppercase tracking-wider min-w-[700px]">
                        <div class="flex-1">Stock Item Product</div>
                        <div class="w-48 shrink-0">Product Description / Name</div>
                        <div class="w-24 text-center shrink-0">Qty</div>
                        <div class="w-28 text-right shrink-0">Unit Price ($)</div>
                        <div class="w-12 text-center shrink-0">Action</div>
                    </div>

                    <div class="space-y-2 min-w-[700px]" id="itemsContainer">
                        @foreach ($order->items as $index => $item)
                            <div class="item-row flex flex-row items-center gap-3 bg-white p-3 rounded-lg border border-slate-200/80 shadow-2xs hover:border-indigo-200 transition-colors"
                                data-row="{{ $index }}">
                                <div class="flex-1">
                                    <select name="items[{{ $index }}][stock_id]"
                                        class="stock-select w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 focus:bg-white focus:border-indigo-500"
                                        onchange="updateProductDetails(this, {{ $index }})">
                                        <option value="">-- Select Product Stock --</option>
                                        @foreach ($stocks as $stock)
                                            @php
                                                $price =
                                                    $stock->retail_price > 0 ? $stock->retail_price : $stock->unit_cost;
                                            @endphp
                                            <option value="{{ $stock->id }}" data-name="{{ $stock->product_name }}"
                                                data-price="{{ $price }}" data-stock="{{ $stock->quantity }}"
                                                {{ $item->stock_id == $stock->id ? 'selected' : '' }}>
                                                {{ $stock->product_name }} (Stock: {{ $stock->quantity }}) -
                                                ${{ number_format($price, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-48 shrink-0">
                                    <input type="text" name="items[{{ $index }}][product_name]"
                                        value="{{ $item->product_name }}" required placeholder="Product Description / Name"
                                        class="product-name-input w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 focus:bg-white focus:border-indigo-500" />
                                </div>
                                <div class="w-24 shrink-0">
                                    <input type="number" name="items[{{ $index }}][quantity]"
                                        id="item_qty_{{ $index }}" value="{{ $item->quantity }}" min="1"
                                        required
                                        class="qty-input w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-center font-bold text-slate-900 focus:bg-white focus:border-indigo-500"
                                        oninput="calculateTotals()" />
                                    <span
                                        class="stock-warning block text-[10px] font-bold text-rose-600 mt-1 hidden text-center"
                                        id="stock_warn_{{ $index }}"></span>
                                </div>
                                <div class="w-28 shrink-0">
                                    <input type="number" step="0.01" name="items[{{ $index }}][unit_price]"
                                        value="{{ $item->unit_price }}" min="0" required
                                        class="price-input w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-right font-bold text-slate-900 focus:bg-white focus:border-indigo-500"
                                        oninput="calculateTotals()" />
                                </div>
                                <div class="w-12 shrink-0 text-center">
                                    <button type="button" onclick="removeItemRow(this)"
                                        class="w-8 h-8 inline-flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all"
                                        title="Remove Item">
                                        <i class="fas fa-trash-can text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Totals & Calculations Section -->
            <div class="border-t border-slate-100 pt-5 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div class="text-xs text-slate-500 max-w-sm space-y-1">
                    <p><i class="fas fa-info-circle text-indigo-500 mr-1"></i> Customer discount & tax calculations update
                        automatically based on customer tier and wholesale pricing rules.</p>
                </div>
                <div class="w-full sm:w-72 bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2.5 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal:</span>
                        <span class="font-bold text-slate-900"
                            id="subtotalDisplay">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Wholesale Discount:</span>
                        <span class="font-bold text-emerald-600"
                            id="discountDisplay">-${{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Estimated Tax (5%):</span>
                        <span class="font-bold text-slate-900"
                            id="taxDisplay">${{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    <div class="border-t border-slate-200 pt-2 flex justify-between text-sm font-black text-slate-900">
                        <span>Grand Total:</span>
                        <span class="text-indigo-600"
                            id="grandTotalDisplay">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Submit Buttons -->
            <div class="border-t border-slate-100 pt-5 flex items-center justify-end gap-3">
                <a href="{{ route('admin.orders.show', $order->id) }}"
                    class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs hover:shadow transition-all flex items-center gap-2">
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
            newRow.className =
                'item-row flex flex-row items-center gap-3 bg-white p-3 rounded-lg border border-slate-200/80 shadow-2xs hover:border-indigo-200 transition-colors';
            newRow.setAttribute('data-row', rowCounter);

            newRow.innerHTML = `
        <div class="flex-1">
            <select name="items[${rowCounter}][stock_id]" class="stock-select w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 focus:bg-white focus:border-indigo-500" onchange="updateProductDetails(this, ${rowCounter})">
                <option value="">-- Select Product Stock --</option>
                @foreach ($stocks as $stock)
                    @php
                        $price = $stock->retail_price > 0 ? $stock->retail_price : $stock->unit_cost;
                    @endphp
                    <option value="{{ $stock->id }}" data-name="{{ $stock->product_name }}" data-price="{{ $price }}" data-stock="{{ $stock->quantity }}">
                        {{ $stock->product_name }} (Stock: {{ $stock->quantity }}) - \${{ number_format($price, 2) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="w-48 shrink-0">
            <input type="text" name="items[${rowCounter}][product_name]" placeholder="e.g. Dell XPS 15 Laptop" required class="product-name-input w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 focus:bg-white focus:border-indigo-500" />
        </div>
        <div class="w-24 shrink-0">
            <input type="number" name="items[${rowCounter}][quantity]" id="item_qty_${rowCounter}" value="1" min="1" required class="qty-input w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-center font-bold text-slate-900 focus:bg-white focus:border-indigo-500" oninput="calculateTotals()" />
            <span class="stock-warning block text-[10px] font-bold text-rose-600 mt-1 hidden text-center" id="stock_warn_${rowCounter}"></span>
        </div>
        <div class="w-28 shrink-0">
            <input type="number" step="0.01" name="items[${rowCounter}][unit_price]" value="0.00" min="0" required class="price-input w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-right font-bold text-slate-900 focus:bg-white focus:border-indigo-500" oninput="calculateTotals()" />
        </div>
        <div class="w-12 shrink-0 text-center">
            <button type="button" onclick="removeItemRow(this)" class="w-8 h-8 inline-flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Remove Item">
                <i class="fas fa-trash-can text-sm"></i>
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
            let stockOverrunAlerts = [];
            const rows = document.querySelectorAll('.item-row');

            rows.forEach((row, idx) => {
                const qtyElem = row.querySelector('.qty-input');
                const priceElem = row.querySelector('.price-input');
                const selectElem = row.querySelector('.stock-select');
                const warnElem = row.querySelector('.stock-warning');

                const qty = parseFloat(qtyElem ? qtyElem.value : 0) || 0;
                const price = parseFloat(priceElem ? priceElem.value : 0) || 0;
                subtotal += (qty * price);

                const selectedOption = selectElem && selectElem.selectedIndex >= 0 ? selectElem.options[selectElem
                    .selectedIndex] : null;
                if (selectedOption && selectedOption.value && selectedOption.getAttribute('data-stock') !== null) {
                    const availableStock = parseInt(selectedOption.getAttribute('data-stock'), 10);
                    const prodName = selectedOption.getAttribute('data-name') || 'Selected Product';

                    if (qty > availableStock) {
                        qtyElem.classList.add('border-rose-500', 'bg-rose-50', 'text-rose-700', 'ring-2',
                            'ring-rose-200');
                        qtyElem.classList.remove('bg-slate-50', 'border-slate-200', 'text-slate-900');
                        if (warnElem) {
                            warnElem.textContent = `⚠️ Max: ${availableStock}`;
                            warnElem.classList.remove('hidden');
                        }
                        stockOverrunAlerts.push(
                            `"${prodName}" quantity (${qty}) exceeds available stock (${availableStock})`);
                    } else {
                        qtyElem.classList.remove('border-rose-500', 'bg-rose-50', 'text-rose-700', 'ring-2',
                            'ring-rose-200');
                        qtyElem.classList.add('bg-slate-50', 'border-slate-200', 'text-slate-900');
                        if (warnElem) {
                            warnElem.classList.add('hidden');
                        }
                    }
                }
            });

            const bannerElem = document.getElementById('stockAlertBanner');
            const bannerTextElem = document.getElementById('stockAlertBannerText');
            if (bannerElem && bannerTextElem) {
                if (stockOverrunAlerts.length > 0) {
                    bannerTextElem.textContent = '⚠️ STOCK WARNING: ' + stockOverrunAlerts.join(' | ');
                    bannerElem.classList.remove('hidden');
                } else {
                    bannerElem.classList.add('hidden');
                }
            }

            const customerSelect = document.getElementById('customerSelect');
            const selectedCustomer = customerSelect ? customerSelect.options[customerSelect.selectedIndex] : null;
            const discountPercent = selectedCustomer ? (parseFloat(selectedCustomer.getAttribute('data-discount')) || 0) :
            0;

            const discountAmount = (subtotal * discountPercent) / 100;
            const taxAmount = (subtotal - discountAmount) * 0.05;
            const grandTotal = subtotal - discountAmount + taxAmount;

            document.getElementById('subtotalDisplay').innerText = '$' + subtotal.toFixed(2);
            document.getElementById('discountDisplay').innerText = '-$' + discountAmount.toFixed(2);
            document.getElementById('taxDisplay').innerText = '$' + taxAmount.toFixed(2);
            document.getElementById('grandTotalDisplay').innerText = '$' + grandTotal.toFixed(2);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const orderForm = document.getElementById('orderForm');
            if (orderForm) {
                orderForm.addEventListener('submit', function(e) {
                    let stockAlerts = [];
                    const rows = document.querySelectorAll('.item-row');
                    rows.forEach((row) => {
                        const select = row.querySelector('.stock-select');
                        const qtyElem = row.querySelector('.qty-input');
                        const selectedOption = select && select.selectedIndex >= 0 ? select.options[
                            select.selectedIndex] : null;

                        if (selectedOption && selectedOption.value && selectedOption.getAttribute(
                                'data-stock') !== null) {
                            const availableStock = parseInt(selectedOption.getAttribute(
                                'data-stock'), 10);
                            const currentQty = parseInt(qtyElem.value || '0', 10);
                            const prodName = selectedOption.getAttribute('data-name') ||
                                'Selected Product';

                            if (currentQty > availableStock) {
                                stockAlerts.push(
                                    `• ${prodName}: Quantity entered is ${currentQty}, but only ${availableStock} units are available in stock.`
                                    );
                            }
                        }
                    });

                    if (stockAlerts.length > 0) {
                        e.preventDefault();
                        alert("⚠️ INSUFFICIENT STOCK ALERT!\n\nCannot process wholesale order due to inventory stock limits:\n\n" +
                            stockAlerts.join("\n") +
                            "\n\nPlease lower the requested quantity to match available stock.");
                        return false;
                    }
                });
            }

            calculateTotals();
        });
    </script>
@endsection
