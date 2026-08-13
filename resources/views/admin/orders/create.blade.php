@extends('admin.layout.app')

@section('title', 'Create B2B Wholesale Order - Admin Workspace')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Create Wholesale Order</h1>
                <p class="text-sm text-slate-500 mt-1">Issue a bulk procurement order for an approved B2B Wholesale Customer
                </p>
            </div>
            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-colors">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Orders</span>
            </a>
        </div>

        <!-- Main Order Form -->
        <form action="{{ route('admin.orders.store') }}" method="POST" id="orderForm"
            class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-6">
            @csrf

            <!-- Order Basic Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Order Number *</label>
                    <input type="text" name="order_number" value="{{ old('order_number', $suggestedOrderNumber) }}"
                        required
                        class="w-full px-3.5 py-2.5 bg-white border-2 border-slate-300 rounded-xl text-xs font-bold font-mono text-slate-900 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Order Source *</label>
                    <select name="order_source" required
                        class="w-full px-3.5 py-2.5 bg-white border-2 border-slate-300 rounded-xl text-xs font-semibold text-slate-900 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs">
                        <option value="admin" {{ request('source') !== 'frontend' ? 'selected' : '' }}>Partner Order (Admin)</option>
                        <option value="frontend" {{ request('source') === 'frontend' ? 'selected' : '' }}>Customer Register Order (Frontend)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Select Wholesale Customer / Partner *</label>
                    <select name="customer_id" id="customerSelect" required
                        class="w-full px-3.5 py-2.5 bg-white border-2 border-slate-300 rounded-xl text-xs font-semibold text-slate-900 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs"
                        onchange="calculateTotals()">
                        <option value="">-- Choose B2B Customer --</option>
                        @foreach ($customers as $cust)
                            <option value="{{ $cust->id }}" data-discount="{{ $cust->wholesale_discount }}"
                                data-terms="{{ $cust->payment_terms }}"
                                {{ old('customer_id') == $cust->id ? 'selected' : '' }}>
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
                            <option value="{{ $pm }}" {{ old('payment_method') == $pm ? 'selected' : '' }}>{{ $pm }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Fulfillment Status *</label>
                    <select name="status" required
                        class="w-full px-3.5 py-2.5 bg-white border-2 border-slate-300 rounded-xl text-xs font-semibold text-slate-900 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs">
                        <option value="pending">Pending</option>
                        <option value="processing" selected>Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Payment Status *</label>
                    <select name="payment_status" required
                        class="w-full px-3.5 py-2.5 bg-white border-2 border-slate-300 rounded-xl text-xs font-semibold text-slate-900 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs">
                        <option value="unpaid" selected>Unpaid</option>
                        <option value="partially_paid">Partially Paid</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>
            </div>

            <!-- Order Items Section -->
            <div class="border-t border-slate-100 pt-5 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Bulk Order Stock Items</h3>
                        <p class="text-xs text-slate-500">Add multiple products to this wholesale order</p>
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
                        class="flex flex-row items-center gap-3 px-3 py-2 bg-slate-200/70 rounded-lg text-[11px] font-bold text-slate-700 uppercase tracking-wider min-w-[650px]">
                        <div class="flex-1">Stock Item Product</div>
                        <div class="w-36 text-center shrink-0">Bulk Qty</div>
                        <div class="w-32 text-right shrink-0">Unit Price ($)</div>
                        <div class="w-32 text-right shrink-0">Subtotal</div>
                        <div class="w-12 text-center shrink-0">Action</div>
                    </div>

                    <div class="space-y-2 min-w-[650px]" id="itemsContainer">
                        <!-- Initial Row 0 -->
                        <div class="item-row flex flex-row items-center gap-3 bg-white p-3 rounded-lg border border-slate-200/80 shadow-2xs hover:border-indigo-200 transition-colors"
                            data-row="0">
                            <div class="flex-1">
                                <select name="items[0][stock_id]"
                                    class="stock-select w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 focus:bg-white focus:border-indigo-500"
                                    onchange="updateProductDetails(this, 0)">
                                    <option value="">-- Select Product Stock --</option>
                                    @foreach ($stocks as $stock)
                                        @php
                                            $price =
                                                $stock->retail_price > 0 ? $stock->retail_price : $stock->unit_cost;
                                        @endphp
                                        <option value="{{ $stock->id }}" data-name="{{ $stock->product_name }}"
                                            data-price="{{ $price }}" data-stock="{{ $stock->quantity }}">
                                            {{ $stock->product_name }} (${{ number_format($price, 2) }} - Stock:
                                            {{ $stock->quantity }})
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="items[0][product_name]" id="item_name_0"
                                    value="Custom Wholesale Item">
                            </div>
                            <div class="w-36 shrink-0 text-center">
                                <input type="number" name="items[0][quantity]" id="item_qty_0" value="1"
                                    min="1" required
                                    class="qty-input w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-center font-bold text-slate-900 focus:bg-white focus:border-indigo-500"
                                    oninput="calculateRowTotal(0)">
                                <span class="stock-warning block text-[10px] font-bold text-rose-600 mt-1 hidden"
                                    id="stock_warn_0"></span>
                            </div>
                            <div class="w-32 shrink-0">
                                <input type="number" step="0.01" name="items[0][unit_price]" id="item_price_0"
                                    value="0.00" min="0" required
                                    class="price-input w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-right font-bold text-slate-900 focus:bg-white focus:border-indigo-500"
                                    oninput="calculateRowTotal(0)">
                            </div>
                            <div class="w-32 shrink-0 text-right">
                                <span class="row-total text-xs font-black text-slate-900" id="item_total_0">$0.00</span>
                            </div>
                            <div class="w-12 shrink-0 text-center">
                                <button type="button" onclick="removeItemRow(this)"
                                    class="w-8 h-8 inline-flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all"
                                    title="Remove Item">
                                    <i class="fas fa-trash-can text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Live Total Summary Box -->
            <div
                class="bg-slate-900 text-white rounded-xl p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Wholesale Discount
                        Applied</span>
                    <div class="text-lg font-bold text-emerald-400 mt-0.5 flex items-center gap-2">
                        <span id="discountBadge">0% OFF</span>
                        <span class="text-xs text-slate-300 font-normal">(Auto-calculated from Customer Tier)</span>
                    </div>
                </div>

                <div
                    class="w-full sm:w-72 space-y-2 text-xs border-t sm:border-t-0 sm:border-l border-slate-800 pt-3 sm:pt-0 sm:pl-6">
                    <div class="flex justify-between text-slate-300">
                        <span>Gross Subtotal:</span>
                        <span class="font-bold text-white" id="summarySubtotal">$0.00</span>
                    </div>
                    <div class="flex justify-between text-emerald-400 font-medium">
                        <span>Tier Discount Rate:</span>
                        <span id="summaryDiscount">-$0.00</span>
                    </div>
                    <div class="flex justify-between text-slate-300">
                        <span>Est. Tax (5%):</span>
                        <span id="summaryTax">+$0.00</span>
                    </div>
                    <div class="flex justify-between text-sm font-black text-white border-t border-slate-800 pt-2">
                        <span>Total All (Grand Total):</span>
                        <span class="text-indigo-400 text-base" id="summaryGrandTotal">$0.00</span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="border-t border-slate-100 pt-5">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Order Notes & Logistics
                    Instructions</label>
                <textarea name="notes" rows="3"
                    placeholder="Special pallet delivery notes, liftgate service, or purchase order reference..."
                    class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:outline-none"></textarea>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <a href="{{ route('admin.orders.index') }}"
                    class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs transition-all">
                    Save & Issue Wholesale Order
                </button>
            </div>
        </form>
    </div>

    <!-- Stock Template for Dynamic Items -->
    <template id="stockOptionsTemplate">
        <option value="">-- Select Product Stock --</option>
        @foreach ($stocks as $stock)
            @php
                $price = $stock->retail_price > 0 ? $stock->retail_price : $stock->unit_cost;
            @endphp
            <option value="{{ $stock->id }}" data-name="{{ $stock->product_name }}"
                data-price="{{ $price }}" data-stock="{{ $stock->quantity }}">
                {{ $stock->product_name }} (${{ number_format($price, 2) }} - Stock: {{ $stock->quantity }})
            </option>
        @endforeach
    </template>

    <script>
        let rowCounter = 1;

        function updateProductDetails(selectElem, index) {
            const selectedOption = selectElem.options[selectElem.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const name = selectedOption.getAttribute('data-name');
                const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;

                document.getElementById('item_name_' + index).value = name;
                document.getElementById('item_price_' + index).value = price.toFixed(2);
            }
            calculateRowTotal(index);
        }

        function calculateRowTotal(index) {
            const qtyElem = document.getElementById('item_qty_' + index);
            const priceElem = document.getElementById('item_price_' + index);
            const totalElem = document.getElementById('item_total_' + index);
            const warnElem = document.getElementById('stock_warn_' + index);

            const row = document.querySelector(`.item-row[data-row="${index}"]`);
            const selectElem = row ? row.querySelector('.stock-select') : null;
            const selectedOption = selectElem && selectElem.selectedIndex >= 0 ? selectElem.options[selectElem
                .selectedIndex] : null;

            if (qtyElem && priceElem && totalElem) {
                const qty = parseFloat(qtyElem.value) || 0;
                const price = parseFloat(priceElem.value) || 0;
                const subtotal = qty * price;
                totalElem.textContent = '$' + subtotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            calculateTotals();
        }

        function addItemRow() {
            const container = document.getElementById('itemsContainer');
            const stockOptionsHtml = document.getElementById('stockOptionsTemplate').innerHTML;
            const rowIndex = rowCounter++;

            const newRow = document.createElement('div');
            newRow.className =
                'item-row flex flex-row items-center gap-3 bg-white p-3 rounded-lg border border-slate-200/80 shadow-2xs hover:border-indigo-200 transition-colors';
            newRow.setAttribute('data-row', rowIndex);

            newRow.innerHTML = `
            <div class="flex-1">
                <select name="items[${rowIndex}][stock_id]" class="stock-select w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 focus:bg-white focus:border-indigo-500" onchange="updateProductDetails(this, ${rowIndex})">
                    ${stockOptionsHtml}
                </select>
                <input type="hidden" name="items[${rowIndex}][product_name]" id="item_name_${rowIndex}" value="Custom Wholesale Item">
            </div>
            <div class="w-36 shrink-0 text-center">
                <input type="number" name="items[${rowIndex}][quantity]" id="item_qty_${rowIndex}" value="1" min="1" required class="qty-input w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-center font-bold text-slate-900 focus:bg-white focus:border-indigo-500" oninput="calculateRowTotal(${rowIndex})">
                <span class="stock-warning block text-[10px] font-bold text-rose-600 mt-1 hidden text-center" id="stock_warn_${rowIndex}"></span>
            </div>
            <div class="w-32 shrink-0">
                <input type="number" step="0.01" name="items[${rowIndex}][unit_price]" id="item_price_${rowIndex}" value="0.00" min="0" required class="price-input w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-right font-bold text-slate-900 focus:bg-white focus:border-indigo-500" oninput="calculateRowTotal(${rowIndex})">
            </div>
            <div class="w-32 shrink-0 text-right">
                <span class="row-total text-xs font-black text-slate-900" id="item_total_${rowIndex}">$0.00</span>
            </div>
            <div class="w-12 shrink-0 text-center">
                <button type="button" onclick="removeItemRow(this)" class="w-8 h-8 inline-flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Remove Item">
                    <i class="fas fa-trash-can text-sm"></i>
                </button>
            </div>
        `;

            container.appendChild(newRow);
            calculateTotals();
        }

        function removeItemRow(btnElem) {
            const rows = document.querySelectorAll('.item-row');
            if (rows.length > 1) {
                btnElem.closest('.item-row').remove();
                calculateTotals();
            } else {
                alert('A wholesale order must contain at least 1 item.');
            }
        }

        function calculateTotals() {
            let grossSubtotal = 0;
            let stockOverrunAlerts = [];

            const rows = document.querySelectorAll('.item-row');
            rows.forEach(row => {
                const qtyInput = row.querySelector('.qty-input');
                const priceInput = row.querySelector('.price-input');
                const selectElem = row.querySelector('.stock-select');
                const warnElem = row.querySelector('.stock-warning');

                if (qtyInput && priceInput) {
                    const qty = parseFloat(qtyInput.value) || 0;
                    const price = parseFloat(priceInput.value) || 0;
                    grossSubtotal += (qty * price);

                    // Stock check
                    const selectedOpt = selectElem && selectElem.selectedIndex >= 0 ? selectElem.options[selectElem
                        .selectedIndex] : null;
                    if (selectedOpt && selectedOpt.value && selectedOpt.getAttribute('data-stock') !== null) {
                        const availableStock = parseInt(selectedOpt.getAttribute('data-stock'), 10);
                        const prodName = selectedOpt.getAttribute('data-name') || 'Selected Product';

                        if (qty > availableStock) {
                            // Highlight input in RED
                            qtyInput.classList.add('border-rose-500', 'bg-rose-50', 'text-rose-700', 'ring-2',
                                'ring-rose-300');
                            qtyInput.classList.remove('bg-slate-50', 'border-slate-200', 'text-slate-900');
                            if (warnElem) {
                                warnElem.textContent = `⚠️ Exceeds Stock (${availableStock})`;
                                warnElem.classList.remove('hidden');
                            }
                            stockOverrunAlerts.push(
                                `"${prodName}" quantity (${qty}) exceeds available stock (${availableStock})`);
                        } else {
                            // Reset input style
                            qtyInput.classList.remove('border-rose-500', 'bg-rose-50', 'text-rose-700', 'ring-2',
                                'ring-rose-300');
                            qtyInput.classList.add('bg-slate-50', 'border-slate-200', 'text-slate-900');
                            if (warnElem) {
                                warnElem.classList.add('hidden');
                            }
                        }
                    }
                }
            });

            // Toggle global top stock alert banner
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

            // Customer discount calculation
            const customerSelect = document.getElementById('customerSelect');
            let discountPercent = 0;

            if (customerSelect && customerSelect.selectedIndex > 0) {
                const selectedOpt = customerSelect.options[customerSelect.selectedIndex];
                discountPercent = parseFloat(selectedOpt.getAttribute('data-discount')) || 0;
            }

            const discountAmount = (grossSubtotal * discountPercent) / 100;
            const taxAmount = (grossSubtotal - discountAmount) * 0.05;
            const grandTotal = grossSubtotal - discountAmount + taxAmount;

            // Update DOM displays
            document.getElementById('discountBadge').textContent = discountPercent + '% OFF';
            document.getElementById('summarySubtotal').textContent = '$' + grossSubtotal.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            document.getElementById('summaryDiscount').textContent = '-$' + discountAmount.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            document.getElementById('summaryTax').textContent = '+$' + taxAmount.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            document.getElementById('summaryGrandTotal').textContent = '$' + grandTotal.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Form submit validation for stock overrun
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

            // Initialize calculation on page load
            calculateRowTotal(0);
        });
    </script>
@endsection
