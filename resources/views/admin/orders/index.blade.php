@extends('admin.layout.app')

@section('title', 'Customer Orders - Admin Workspace')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Customer Orders</h1>
            <p class="text-sm text-slate-500 mt-1">Manage wholesale procurement orders and fulfillment statuses</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-8 text-center text-slate-500">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fas fa-cart-shopping"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-lg">Customer Orders Management</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto mt-1">View and process wholesale orders placed by corporate clients and business buyers.</p>
        </div>
    </div>
</div>
@endsection
