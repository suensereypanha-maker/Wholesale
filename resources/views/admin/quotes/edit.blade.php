@extends('admin.layout.app')

@section('title', 'Edit Quote Request - ' . $quote->quote_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Quote Request</h1>
            <p class="text-sm text-slate-500 mt-1">Modify inquiry details, requested products, and pricing offer for #{{ $quote->quote_number }}</p>
        </div>
        <a href="{{ route('admin.quotes.show', $quote->id) }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Details</span>
        </a>
    </div>

    <!-- Main Form -->
    <form action="{{ route('admin.quotes.update', $quote->id) }}" method="POST" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Section 1: Basic Info -->
        <div class="space-y-4">
            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">1. Inquiry & Client Details</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Quote Number</label>
                    <input 
                        type="text" 
                        value="{{ $quote->quote_number }}" 
                        disabled
                        class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-semibold text-slate-500 cursor-not-allowed"
                    />
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Linked Customer Profile</label>
                    <select name="customer_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none">
                        <option value="">-- Manual Client Entry / Guest --</option>
                        @foreach($customers as $cust)
                            <option value="{{ $cust->id }}" {{ old('customer_id', $quote->customer_id) == $cust->id ? 'selected' : '' }}>
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
                        value="{{ old('company_name', $quote->company_name) }}" 
                        required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Contact Person *</label>
                    <input 
                        type="text" 
                        name="contact_name" 
                        value="{{ old('contact_name', $quote->contact_name) }}" 
                        required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Address *</label>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email', $quote->email) }}" 
                        required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Phone Number *</label>
                    <input 
                        type="text" 
                        name="phone" 
                        value="{{ old('phone', $quote->phone) }}" 
                        required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none"
                    />
                </div>
            </div>
        </div>

        <!-- Section 2: Product & Quantity -->
        <div class="space-y-4 border-t border-slate-100 pt-5">
            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">2. Requested Product & Pricing</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Linked Stock Product</label>
                    <select name="stock_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none">
                        <option value="">-- Custom Requested Product --</option>
                        @foreach($stocks as $stk)
                            <option value="{{ $stk->id }}" {{ old('stock_id', $quote->stock_id) == $stk->id ? 'selected' : '' }}>
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
                        value="{{ old('product_name', $quote->product_name) }}" 
                        required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Quantity Requested *</label>
                    <input 
                        type="number" 
                        name="quantity" 
                        value="{{ old('quantity', $quote->quantity) }}" 
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
                        value="{{ old('target_price', $quote->target_price) }}" 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Admin Offered Price ($)</label>
                    <input 
                        type="number" 
                        step="0.01" 
                        name="offered_price" 
                        value="{{ old('offered_price', $quote->offered_price) }}" 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-emerald-700 focus:bg-white focus:border-emerald-500 focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Required Delivery Date</label>
                    <input 
                        type="date" 
                        name="required_date" 
                        value="{{ old('required_date', $quote->required_date ? $quote->required_date->format('Y-m-d') : '') }}" 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Inquiry Status *</label>
                    <select name="status" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-indigo-500 focus:outline-none">
                        <option value="pending" {{ old('status', $quote->status) === 'pending' ? 'selected' : '' }}>Pending Review</option>
                        <option value="under_review" {{ old('status', $quote->status) === 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="quoted" {{ old('status', $quote->status) === 'quoted' ? 'selected' : '' }}>Quote Offered</option>
                        <option value="approved" {{ old('status', $quote->status) === 'approved' ? 'selected' : '' }}>Approved by Buyer</option>
                        <option value="converted" {{ old('status', $quote->status) === 'converted' ? 'selected' : '' }}>Converted to Order</option>
                        <option value="rejected" {{ old('status', $quote->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                    <textarea name="message" rows="4" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:outline-none">{{ old('message', $quote->message) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Internal Admin Notes</label>
                    <textarea name="admin_notes" rows="4" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:outline-none">{{ old('admin_notes', $quote->admin_notes) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
            <a href="{{ route('admin.quotes.show', $quote->id) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs transition-all">
                Update Quote Details
            </button>
        </div>
    </form>
</div>
@endsection
