@extends('admin.layout.app')

@section('title', 'Log New Quote Request - Admin Workspace')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Log New Quote Request</h1>
            <p class="text-sm text-slate-500 mt-1">Record a wholesale price request or inquiry on behalf of a B2B client</p>
        </div>
        <a href="{{ route('admin.quotes.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Quotes</span>
        </a>
    </div>

    <!-- Main Form -->
    <form action="{{ route('admin.quotes.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-6">
        @csrf

        <!-- Section 1: Basic Info -->
        <div class="space-y-4">
            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">1. Inquiry & Client Details</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Quote Number *</label>
                    <input 
                        type="text" 
                        name="quote_number" 
                        value="{{ old('quote_number', $suggestedQuoteNumber) }}" 
                        required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none"
                    />
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Link Approved Customer (Optional)</label>
                    <select name="customer_id" id="customerSelect" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none" onchange="autoFillCustomer()">
                        <option value="">-- Manual Client Entry / Guest --</option>
                        @foreach($customers as $cust)
                            <option value="{{ $cust->id }}" data-company="{{ $cust->company_name }}" data-contact="{{ $cust->name }}" data-email="{{ $cust->email }}" data-phone="{{ $cust->phone }}">
                                {{ $cust->company_name ?? $cust->name }} ({{ $cust->name }} - {{ $cust->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Company Name *</label>
                    <input 
                        type="text" 
                        name="company_name" 
                        id="company_name"
                        value="{{ old('company_name') }}" 
                        required 
                        placeholder="Apex Logistics Ltd."
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Contact Person *</label>
                    <input 
                        type="text" 
                        name="contact_name" 
                        id="contact_name"
                        value="{{ old('contact_name') }}" 
                        required 
                        placeholder="John Doe"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Address *</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        value="{{ old('email') }}" 
                        required 
                        placeholder="procurement@apex.com"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Phone Number *</label>
                    <input 
                        type="text" 
                        name="phone" 
                        id="phone"
                        value="{{ old('phone') }}" 
                        required 
                        placeholder="+855 12 345 678"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none"
                    />
                </div>
            </div>
        </div>

        <!-- Section 2: Product & Quantity -->
        <div class="space-y-4 border-t border-slate-100 pt-5">
            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">2. Requested Product & Quantities</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Link Stock Product (Optional)</label>
                    <select name="stock_id" id="stockSelect" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none" onchange="autoFillStock()">
                        <option value="">-- Custom Requested Product --</option>
                        @foreach($stocks as $stk)
                            <option value="{{ $stk->id }}" data-name="{{ $stk->product_name }}" data-price="{{ $stk->retail_price > 0 ? $stk->retail_price : $stk->unit_cost }}">
                                {{ $stk->product_name }} (SKU: {{ $stk->sku }} - Stock: {{ $stk->quantity }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Product Name *</label>
                    <input 
                        type="text" 
                        name="product_name" 
                        id="product_name"
                        value="{{ old('product_name') }}" 
                        required 
                        placeholder="Industrial Power Generator 50kW"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Quantity Requested *</label>
                    <input 
                        type="number" 
                        name="quantity" 
                        value="{{ old('quantity', 1) }}" 
                        min="1" 
                        required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Buyer Target Price ($)</label>
                    <input 
                        type="number" 
                        step="0.01" 
                        name="target_price" 
                        value="{{ old('target_price') }}" 
                        placeholder="1250.00"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Admin Offered Price ($)</label>
                    <input 
                        type="number" 
                        step="0.01" 
                        name="offered_price" 
                        value="{{ old('offered_price') }}" 
                        placeholder="Leave blank if under review"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-emerald-700 focus:bg-white focus:border-emerald-500 focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Required Delivery Date</label>
                    <input 
                        type="date" 
                        name="required_date" 
                        value="{{ old('required_date') }}" 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Inquiry Status *</label>
                    <select name="status" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none">
                        <option value="pending">Pending Review</option>
                        <option value="under_review" selected>Under Review</option>
                        <option value="quoted">Quote Offered</option>
                        <option value="approved">Approved by Buyer</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Section 3: Notes & Message -->
        <div class="space-y-4 border-t border-slate-100 pt-5">
            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">3. Inquiry Notes & Special Requirements</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Customer Message / Inquiry Text</label>
                    <textarea name="message" rows="4" placeholder="Client comments, custom specifications, or packaging requests..." class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:outline-none">{{ old('message') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Internal Admin Notes</label>
                    <textarea name="admin_notes" rows="4" placeholder="Internal margin notes, freight cost estimate, or negotiation history..." class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:outline-none">{{ old('admin_notes') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
            <a href="{{ route('admin.quotes.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs transition-all">
                Save & Issue Quote Request
            </button>
        </div>
    </form>
</div>

<script>
    function autoFillCustomer() {
        const select = document.getElementById('customerSelect');
        if (select.selectedIndex > 0) {
            const opt = select.options[select.selectedIndex];
            document.getElementById('company_name').value = opt.getAttribute('data-company') || '';
            document.getElementById('contact_name').value = opt.getAttribute('data-contact') || '';
            document.getElementById('email').value = opt.getAttribute('data-email') || '';
            document.getElementById('phone').value = opt.getAttribute('data-phone') || '';
        }
    }

    function autoFillStock() {
        const select = document.getElementById('stockSelect');
        if (select.selectedIndex > 0) {
            const opt = select.options[select.selectedIndex];
            document.getElementById('product_name').value = opt.getAttribute('data-name') || '';
        }
    }
</script>
@endsection
