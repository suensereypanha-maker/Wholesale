@extends('admin.layout.app')

@section('title', 'User Profile - ' . $user->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-indigo-600 transition-colors">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Back to User List</span>
        </a>

        <div class="flex items-center gap-3">
            <x-forms.button 
                href="{{ route('admin.users.edit', $user) }}" 
                variant="outline" 
                icon="fas fa-pen-to-square"
                permission="manage_users"
            >
                Edit Account
            </x-forms.button>
        </div>
    </div>

    <!-- Main Profile Card -->
    <x-forms.card title="User Account Overview" description="Detailed profile parameters and security permissions" icon="fas fa-id-card">
        
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 border-b border-slate-100 pb-6">
            <!-- Avatar Icon -->
            <div class="w-20 h-20 rounded-2xl bg-slate-900 text-white font-black flex items-center justify-center text-xl shadow-md border-2 border-slate-700 shrink-0">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>

            <div class="space-y-2 text-center sm:text-left flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">{{ $user->name }}</h2>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $user->email }}</p>
                        @if($user->company)
                            <p class="text-xs font-bold text-emerald-700 mt-1 flex items-center justify-center sm:justify-start gap-1.5">
                                <i class="fas fa-building"></i>
                                <span>{{ $user->company }}</span>
                            </p>
                        @endif
                    </div>

                    @if($user->status === 'active')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 self-center sm:self-auto">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Active Status
                        </span>
                    @elseif($user->status === 'pending' || empty($user->status))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-300 animate-pulse self-center sm:self-auto">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span> Pending Approval
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 self-center sm:self-auto">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Rejected / Suspended
                        </span>
                    @endif
                </div>

                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 pt-1">
                    @forelse ($user->roles as $role)
                        <span class="px-3 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 flex items-center gap-1.5">
                            <i class="fas fa-shield-halved text-xs"></i>
                            <span>{{ $role->name }}</span>
                        </span>
                    @empty
                        <span class="text-slate-400 italic text-xs">No assigned role</span>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- B2B Commercial & Tax Attributes -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-4">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                <p class="text-[11px] font-bold text-slate-400 uppercase">Tax Reg / VAT Number</p>
                <p class="text-sm font-extrabold text-slate-800 mt-1">{{ $user->tax_number ?? 'Not Provided (NULL)' }}</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                <p class="text-[11px] font-bold text-slate-400 uppercase">Phone Number</p>
                <p class="text-sm font-extrabold text-slate-800 mt-1">{{ $user->phone ?? 'N/A' }}</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                <p class="text-[11px] font-bold text-slate-400 uppercase">Wholesale Tier</p>
                <p class="text-sm font-extrabold text-indigo-700 mt-1">{{ $user->tier ?? 'Standard Wholesale' }}</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                <p class="text-[11px] font-bold text-slate-400 uppercase">Credit Limit & Discount</p>
                <p class="text-sm font-extrabold text-emerald-700 mt-1">${{ number_format($user->credit_limit ?? 0, 2) }} / {{ number_format($user->wholesale_discount ?? 0, 2) }}% OFF</p>
            </div>
        </div>

        <!-- Address & Registration Attributes Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                <p class="text-[11px] font-bold text-slate-400 uppercase">Registered Address</p>
                <p class="text-xs font-semibold text-slate-700 mt-1">
                    {{ $user->address ?? 'N/A' }}<br>
                    {{ implode(', ', array_filter([$user->city, $user->province, $user->zip, $user->country])) ?: '' }}
                </p>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                <p class="text-[11px] font-bold text-slate-400 uppercase">Registered Date</p>
                <p class="text-sm font-extrabold text-slate-800 mt-1">{{ $user->created_at ? $user->created_at->format('M d, Y - H:i') : 'N/A' }}</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                <p class="text-[11px] font-bold text-slate-400 uppercase">Last Profile Update</p>
                <p class="text-sm font-extrabold text-slate-800 mt-1">{{ $user->updated_at ? $user->updated_at->format('M d, Y - H:i') : 'N/A' }}</p>
            </div>
        </div>

    </x-forms.card>

</div>
@endsection
