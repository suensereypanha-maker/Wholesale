@extends('admin.layout.app')

@section('title', 'Permission Management')

@section('content')
<div class="space-y-6 w-full">

    <!-- Flash Alert Messages -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-xl flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2.5">
                <i class="fas fa-circle-check text-emerald-500 text-sm"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 cursor-pointer">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-xl flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2.5">
                <i class="fas fa-triangle-exclamation text-rose-500 text-sm"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 cursor-pointer">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
    @endif

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <span class="p-2.5 bg-blue-50 text-blue-600 rounded-xl">
                    <i class="fas fa-key text-xl"></i>
                </span>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Permission Management</h1>
                    <p class="text-xs text-slate-500">Manage module access capabilities and role-level authorization keys</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <x-forms.button 
                href="{{ route('admin.permissions.create') }}" 
                variant="primary" 
                icon="fas fa-plus"
                permission="manage_roles"
                class="!bg-blue-600 hover:!bg-blue-700"
            >
                Create Permission
            </x-forms.button>
        </div>
    </div>

    <!-- Permissions Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden w-full">
        
        <!-- Table Search & Filter Header -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <x-forms.form action="{{ route('admin.permissions.index') }}" method="GET" class="w-full sm:w-80 !space-y-0">
                <x-forms.input 
                    name="search" 
                    placeholder="Search permissions by name..." 
                    :value="request('search')"
                    icon="fas fa-search"
                />
            </x-forms.form>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
                <span class="text-xs text-slate-500">Total System Permissions: <strong class="text-slate-900 font-bold">{{ $permissions->count() }}</strong></span>
            </div>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Permission Key</th>
                        <th class="py-3.5 px-6">Description</th>
                        <th class="py-3.5 px-6">Guard</th>
                        <th class="py-3.5 px-6">Assigned Roles</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse ($permissions as $permission)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-mono font-bold text-blue-600">
                                <a href="{{ route('admin.permissions.show', $permission) }}" class="hover:underline flex items-center gap-2">
                                    <i class="fas fa-key text-[10px] text-slate-400"></i>
                                    <span>{{ $permission->name }}</span>
                                </a>
                            </td>
                            <td class="py-4 px-6 text-slate-600 max-w-xs truncate">
                                {{ $permission->description ?? 'No description available' }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-[11px] font-mono font-semibold">
                                    {{ $permission->guard_name }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse ($permission->roles as $role)
                                        <span class="px-2.5 py-1 bg-violet-50 text-violet-700 border border-violet-200 rounded-lg text-xs font-semibold">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-slate-400 italic text-[11px]">Unassigned</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- View Details Button -->
                                    <x-forms.button 
                                        href="{{ route('admin.permissions.show', $permission) }}" 
                                        variant="ghost" 
                                        size="sm" 
                                        icon="fas fa-eye"
                                        title="View Details" 
                                    />

                                    <!-- Edit Permission Button -->
                                    <x-forms.button 
                                        href="{{ route('admin.permissions.edit', $permission) }}" 
                                        variant="ghost" 
                                        size="sm" 
                                        icon="fas fa-pen-to-square"
                                        permission="manage_roles"
                                        title="Edit Permission" 
                                    />

                                    <!-- Delete Permission Form -->
                                    @if(!in_array($permission->name, ['view_dashboard', 'manage_users', 'manage_roles', 'manage_orders', 'manage_products', 'view_reports']))
                                        <x-forms.form 
                                            action="{{ route('admin.permissions.destroy', $permission) }}" 
                                            method="DELETE" 
                                            class="inline-block !space-y-0"
                                            permission="manage_roles"
                                            onsubmit="return confirm('Are you sure you want to delete permission {{ $permission->name }}?');"
                                        >
                                            <x-forms.button 
                                                type="submit" 
                                                variant="ghost" 
                                                size="sm" 
                                                icon="fas fa-trash-can" 
                                                class="text-rose-500 hover:text-rose-700 hover:bg-rose-50"
                                                title="Delete Permission"
                                            />
                                        </x-forms.form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                No permissions found matching search criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
