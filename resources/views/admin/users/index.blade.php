@extends('admin.layout.app')

@php
    $isCustomer = !empty($isCustomerRegister);
    $routeBase = $isCustomer ? 'admin.customers.register' : 'admin.users.index';
@endphp

@section('title', $isCustomer ? 'Customers Register' : 'User Management')

@section('content')
<div class="space-y-6 w-full">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <span class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                    <i class="fas {{ $isCustomer ? 'fa-id-card' : 'fa-users-gear' }} text-xl"></i>
                </span>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">{{ $isCustomer ? 'Customers Register' : 'User Management' }}</h1>
                    <p class="text-xs text-slate-500">
                        {{ $isCustomer ? 'Manage and approve B2B customer accounts registered from the storefront' : 'Manage administrator accounts, user registration approvals, roles, and permissions' }}
                    </p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <x-forms.button 
                href="{{ route('admin.users.create') }}" 
                variant="primary" 
                icon="fas fa-user-plus"
                permission="manage_users"
            >
                Add New User
            </x-forms.button>
        </div>
    </div>

    <!-- Summary Metrics Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 w-full">
        <!-- Total Accounts -->
        <a href="{{ route($routeBase) }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between hover:border-indigo-300 transition-colors group">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Accounts</p>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $totalUsers }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                <i class="fas fa-users"></i>
            </div>
        </a>

        <!-- Active Users -->
        <a href="{{ route($routeBase, ['status' => 'active']) }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between hover:border-emerald-300 transition-colors group">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Users</p>
                <p class="text-2xl font-black text-emerald-600 mt-1">{{ $activeUsers }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                <i class="fas fa-user-check"></i>
            </div>
        </a>

        <!-- Pending Approval Alert Card -->
        <a href="{{ route($routeBase, ['status' => 'pending']) }}" class="bg-white p-5 rounded-2xl border {{ $pendingUsers > 0 ? 'border-amber-300 bg-amber-50/20 ring-2 ring-amber-400/20' : 'border-slate-200/80' }} shadow-xs flex items-center justify-between hover:border-amber-400 transition-colors group">
            <div>
                <div class="flex items-center gap-2">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pending Approval</p>
                    @if($pendingUsers > 0)
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                        </span>
                    @endif
                </div>
                <p class="text-2xl font-black {{ $pendingUsers > 0 ? 'text-amber-600' : 'text-slate-900' }} mt-1">{{ $pendingUsers }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl {{ $pendingUsers > 0 ? 'bg-amber-100 text-amber-700' : 'bg-amber-50 text-amber-600' }} flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                <i class="fas fa-clock-rotate-left"></i>
            </div>
        </a>

        <!-- Rejected / Suspended -->
        <a href="{{ route($routeBase, ['status' => 'rejected']) }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between hover:border-rose-300 transition-colors group">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rejected / Suspended</p>
                <p class="text-2xl font-black text-rose-600 mt-1">{{ $rejectedUsers }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                <i class="fas fa-user-xmark"></i>
            </div>
        </a>
    </div>

    <!-- Pending Approval Notification Alert Banner -->
    @if($pendingUsers > 0 && request('status') !== 'pending')
        <div class="bg-gradient-to-r from-amber-500 to-amber-600 text-white p-4 rounded-2xl shadow-lg flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-lg flex-shrink-0">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div>
                    <h2 class="font-bold text-sm">You have {{ $pendingUsers }} pending customer {{ Str::plural('registration', $pendingUsers) }} awaiting approval!</h2>
                    <p class="text-xs text-amber-100">Review and approve new user requests to grant system access.</p>
                </div>
            </div>
            <a href="{{ route($routeBase, ['status' => 'pending']) }}" class="px-4 py-2 bg-white text-amber-800 rounded-xl text-xs font-bold hover:bg-amber-50 transition-colors shadow-xs flex-shrink-0 flex items-center gap-1.5">
                <span>View Pending Customers</span>
                <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    @endif

    <!-- Users Table Container -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden w-full">
        
        <!-- Table Search & Status Filter Header -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col lg:flex-row items-center justify-between gap-4">
            
            <!-- Status Filter Pills -->
            <div class="flex items-center gap-1.5 overflow-x-auto w-full lg:w-auto pb-2 lg:pb-0">
                <a href="{{ route($routeBase, array_merge(request()->except(['status', 'page']))) }}" 
                   class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ !request()->filled('status') ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    All {{ $isCustomer ? 'Customers' : 'Users' }} ({{ $totalUsers }})
                </a>
                <a href="{{ route($routeBase, array_merge(request()->except(['page']), ['status' => 'pending'])) }}" 
                   class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all flex items-center gap-1.5 {{ request('status') === 'pending' ? 'bg-amber-500 text-white shadow-xs' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                    <i class="fas fa-clock text-[10px]"></i>
                    <span>Pending Approval</span>
                    @if($pendingUsers > 0)
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-amber-600 text-white font-extrabold">{{ $pendingUsers }}</span>
                    @endif
                </a>
                <a href="{{ route($routeBase, array_merge(request()->except(['page']), ['status' => 'active'])) }}" 
                   class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ request('status') === 'active' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                    Active ({{ $activeUsers }})
                </a>
                <a href="{{ route($routeBase, array_merge(request()->except(['page']), ['status' => 'rejected'])) }}" 
                   class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ request('status') === 'rejected' ? 'bg-rose-600 text-white shadow-xs' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">
                    Rejected ({{ $rejectedUsers }})
                </a>
            </div>

            <!-- Search Form -->
            <x-forms.form action="{{ route($routeBase) }}" method="GET" class="w-full lg:w-72 !space-y-0">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <x-forms.input 
                    name="search" 
                    placeholder="Search name, email, or role..." 
                    :value="request('search')"
                    icon="fas fa-search"
                />
            </x-forms.form>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse min-w-[750px]">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">User Account</th>
                        <th class="py-3.5 px-6">Assigned Roles</th>
                        <th class="py-3.5 px-6">Account Status</th>
                        <th class="py-3.5 px-6">Registered Date</th>
                        <th class="py-3.5 px-6 text-right">Approval Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-50/60 transition-colors {{ $user->status === 'pending' ? 'bg-amber-50/10' : '' }}">
                            <!-- User Account & B2B Details -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl {{ $user->status === 'pending' ? 'bg-amber-500 text-white' : ($user->status === 'rejected' ? 'bg-rose-500 text-white' : 'bg-slate-900 text-white') }} font-bold flex items-center justify-center text-xs shadow-xs flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.users.show', $user) }}" class="font-bold text-slate-900 text-sm truncate hover:text-indigo-600 transition-colors flex items-center gap-1.5">
                                            <span>{{ $user->name }}</span>
                                            @if($user->status === 'pending')
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-300">New</span>
                                            @endif
                                        </a>
                                        <p class="text-xs text-slate-500 truncate">{{ $user->email }}</p>
                                        @if($user->company)
                                            <p class="text-[11px] font-semibold text-emerald-700 truncate mt-0.5">
                                                <i class="fas fa-building text-[10px] me-1"></i>{{ $user->company }}
                                                @if($user->tax_number)
                                                    <span class="text-slate-400 font-normal">({{ $user->tax_number }})</span>
                                                @else
                                                    <span class="text-slate-400 font-normal">(No Tax ID)</span>
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Assigned Roles -->
                            <td class="py-4 px-6">
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse ($user->roles as $role)
                                        @php
                                            $badgeStyle = match($role->name) {
                                                'Super Admin' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                'Admin'       => 'bg-violet-50 text-violet-700 border-violet-200',
                                                'Manager'     => 'bg-blue-50 text-blue-700 border-blue-200',
                                                default       => 'bg-slate-100 text-slate-700 border-slate-200',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border {{ $badgeStyle }}">
                                            <i class="fas fa-shield text-[10px] mr-1.5 opacity-70"></i>
                                            <span class="capitalize">{{ $role->name }}</span>
                                        </span>
                                    @empty
                                        <span class="text-slate-400 italic text-[11px]">No Role Assigned</span>
                                    @endforelse
                                </div>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-4 px-6">
                                @if($user->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                @elseif($user->status === 'pending' || empty($user->status))
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-300 animate-pulse">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending Approval
                                    </span>
                                @elseif($user->status === 'rejected')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Rejected
                                    </span>
                                @endif
                            </td>

                            <!-- Created Date -->
                            <td class="py-4 px-6 text-slate-500">
                                {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}
                                <p class="text-[10px] text-slate-400">{{ $user->created_at ? $user->created_at->diffForHumans() : '' }}</p>
                            </td>

                            <!-- Action Buttons -->
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    
                                    <!-- Approve Button (If pending or rejected) -->
                                    @if($user->status !== 'active')
                                        <x-forms.form 
                                            action="{{ route('admin.users.approve', $user) }}" 
                                            method="POST" 
                                            class="inline-block !space-y-0"
                                            permission="manage_users"
                                        >
                                            <x-forms.button 
                                                type="submit" 
                                                variant="success" 
                                                size="sm" 
                                                icon="fas fa-check-double" 
                                                title="Approve User Account"
                                            >
                                                Approve
                                            </x-forms.button>
                                        </x-forms.form>
                                    @endif

                                    <!-- Reject / Suspend Button (If pending or active) -->
                                    @if($user->status !== 'rejected' && auth()->id() !== $user->id)
                                        <x-forms.form 
                                            action="{{ route('admin.users.reject', $user) }}" 
                                            method="POST" 
                                            class="inline-block !space-y-0"
                                            permission="manage_users"
                                            onsubmit="return confirm('Are you sure you want to reject/suspend account {{ $user->name }}?');"
                                        >
                                            <x-forms.button 
                                                type="submit" 
                                                variant="ghost" 
                                                size="sm" 
                                                icon="fas fa-ban" 
                                                class="text-amber-600 hover:text-amber-700 hover:bg-amber-50"
                                                title="Reject or Suspend Account"
                                            />
                                        </x-forms.form>
                                    @endif

                                    <!-- View Profile -->
                                    <x-forms.button 
                                        href="{{ route('admin.users.show', $user) }}" 
                                        variant="ghost" 
                                        size="sm" 
                                        icon="fas fa-eye"
                                        title="View Profile" 
                                    />

                                    <!-- Edit User -->
                                    <x-forms.button 
                                        href="{{ route('admin.users.edit', $user) }}" 
                                        variant="ghost" 
                                        size="sm" 
                                        icon="fas fa-pen-to-square"
                                        permission="manage_users"
                                        title="Edit User" 
                                    />

                                    <!-- Delete User Form -->
                                    @if(auth()->id() !== $user->id)
                                        <x-forms.form 
                                            action="{{ route('admin.users.destroy', $user) }}" 
                                            method="DELETE" 
                                            class="inline-block !space-y-0"
                                            permission="manage_users"
                                            onsubmit="return confirm('Are you sure you want to delete user {{ $user->name }}?');"
                                        >
                                            <x-forms.button 
                                                type="submit" 
                                                variant="ghost" 
                                                size="sm" 
                                                icon="fas fa-trash-can" 
                                                class="text-rose-500 hover:text-rose-700 hover:bg-rose-50"
                                                title="Delete User"
                                            />
                                        </x-forms.form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                No users found matching filter criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
