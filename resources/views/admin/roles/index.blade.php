@extends('admin.layout.app')

@section('title', 'Role Management')

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
                <span class="p-2.5 bg-violet-50 text-violet-600 rounded-xl">
                    <i class="fas fa-shield-halved text-xl"></i>
                </span>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Role Management</h1>
                    <p class="text-xs text-slate-500">Define access roles and configure permission boundaries across system modules</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <x-forms.button 
                href="{{ route('admin.roles.create') }}" 
                variant="primary" 
                icon="fas fa-plus"
                permission="manage_roles"
                class="!bg-violet-600 hover:!bg-violet-700"
            >
                Create New Role
            </x-forms.button>
        </div>
    </div>

    <!-- Search & Filters Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
        <x-forms.form action="{{ route('admin.roles.index') }}" method="GET" class="w-full sm:w-80 !space-y-0">
            <x-forms.input 
                name="search" 
                placeholder="Search roles by name or description..." 
                :value="request('search')"
                icon="fas fa-search"
            />
        </x-forms.form>

        <div class="flex items-center gap-3 text-xs text-slate-500">
            <span>Total System Roles: <strong class="text-slate-900 font-bold">{{ $roles->count() }}</strong></span>
        </div>
    </div>

    <!-- Roles Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 w-full">
        @forelse ($roles as $role)
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all flex flex-col justify-between h-full group">
                <div>
                    <!-- Header with Role Name and User Badge -->
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 font-bold flex items-center justify-center text-sm border border-violet-100 flex-shrink-0 group-hover:scale-105 transition-transform">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-base font-bold text-slate-900 leading-tight truncate">
                                    <a href="{{ route('admin.roles.show', $role) }}" class="hover:text-violet-600 transition-colors">
                                        {{ $role->name }}
                                    </a>
                                </h3>
                                <p class="text-[11px] text-slate-400">Guard: {{ $role->guard_name }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold border border-slate-200 flex-shrink-0">
                            <i class="fas fa-users text-[10px] text-slate-400"></i>
                            {{ $role->users_count }} {{ Str::plural('User', $role->users_count) }}
                        </span>
                    </div>

                    <!-- Role Description -->
                    <p class="text-xs text-slate-600 mb-5 leading-relaxed min-h-[36px]">
                        {{ $role->description ?? 'No description provided for this security role.' }}
                    </p>

                    <!-- Assigned Permissions -->
                    <div class="mb-5">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                Assigned Permissions ({{ $role->permissions->count() }})
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-1.5 max-h-32 overflow-y-auto pr-1">
                            @forelse ($role->permissions as $permission)
                                <span class="px-2.5 py-1 bg-slate-50 border border-slate-200 text-slate-700 rounded-lg text-xs font-medium inline-flex items-center">
                                    <i class="fas fa-check text-[9px] text-emerald-500 mr-1.5"></i> {{ $permission->name }}
                                </span>
                            @empty
                                <span class="text-xs text-slate-400 italic">No permissions assigned</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Footer Card Action Buttons -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between mt-auto">
                    <span class="text-[11px] font-mono text-slate-400">ID: #{{ $role->id }}</span>
                    <div class="flex items-center gap-1.5">
                        <!-- View Details -->
                        <x-forms.button 
                            href="{{ route('admin.roles.show', $role) }}" 
                            variant="ghost" 
                            size="sm" 
                            icon="fas fa-eye"
                            title="View Role Details" 
                        />

                        <!-- Edit Role -->
                        <x-forms.button 
                            href="{{ route('admin.roles.edit', $role) }}" 
                            variant="ghost" 
                            size="sm" 
                            icon="fas fa-pen-to-square"
                            permission="manage_roles"
                            title="Edit Role & Permissions" 
                        />

                        <!-- Delete Role -->
                        @if(!in_array($role->name, ['Super Admin', 'Admin']))
                            <x-forms.form 
                                action="{{ route('admin.roles.destroy', $role) }}" 
                                method="DELETE" 
                                class="inline-block !space-y-0"
                                permission="manage_roles"
                                onsubmit="return confirm('Are you sure you want to delete role {{ $role->name }}?');"
                            >
                                <x-forms.button 
                                    type="submit" 
                                    variant="ghost" 
                                    size="sm" 
                                    icon="fas fa-trash-can" 
                                    class="text-rose-500 hover:text-rose-700 hover:bg-rose-50"
                                    title="Delete Role"
                                />
                            </x-forms.form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-12 text-center rounded-2xl border border-slate-200/80 text-slate-400 space-y-3">
                <i class="fas fa-shield-slash text-3xl text-slate-300"></i>
                <p class="text-sm font-medium text-slate-600">No system roles found.</p>
                <p class="text-xs text-slate-400">Try adjusting your search criteria or create a new role.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
