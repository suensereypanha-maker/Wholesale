@extends('admin.layout.app')

@section('title', 'Role Details - ' . $role->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-violet-600 transition-colors">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Back to Role List</span>
        </a>

        <div class="flex items-center gap-3">
            <x-forms.button 
                href="{{ route('admin.roles.edit', $role) }}" 
                variant="outline" 
                icon="fas fa-pen-to-square"
                permission="manage_roles"
            >
                Edit Role
            </x-forms.button>
        </div>
    </div>

    <!-- Main Role Overview Card -->
    <x-forms.card title="Role Security Profile" description="Detailed permission configuration and assigned system accounts" icon="fas fa-shield-halved">
        
        <!-- Role Header Info -->
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 border-b border-slate-100 pb-6">
            <div class="w-16 h-16 rounded-2xl bg-violet-100 text-violet-700 font-black flex items-center justify-center text-2xl shadow-sm border border-violet-200 shrink-0">
                <i class="fas fa-user-shield"></i>
            </div>

            <div class="space-y-2 text-center sm:text-left flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">{{ $role->name }}</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Guard: <span class="font-mono font-bold text-slate-700">{{ $role->guard_name }}</span></p>
                    </div>

                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-violet-50 text-violet-700 border border-violet-200 self-center sm:self-auto">
                        <i class="fas fa-users text-xs"></i>
                        {{ $role->users->count() }} {{ Str::plural('User', $role->users->count()) }} Assigned
                    </span>
                </div>

                <p class="text-xs text-slate-600 leading-relaxed pt-1">
                    {{ $role->description ?? 'No description defined for this role.' }}
                </p>
            </div>
        </div>

        <!-- Role Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 border-b border-slate-100 pb-6">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                <p class="text-[11px] font-bold text-slate-400 uppercase">Role ID</p>
                <p class="text-sm font-mono font-extrabold text-slate-800 mt-1">#{{ $role->id }}</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                <p class="text-[11px] font-bold text-slate-400 uppercase">Granted Permissions</p>
                <p class="text-sm font-extrabold text-violet-600 mt-1">{{ $role->permissions->count() }} Capability Rules</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                <p class="text-[11px] font-bold text-slate-400 uppercase">Created Date</p>
                <p class="text-sm font-extrabold text-slate-800 mt-1">{{ $role->created_at ? $role->created_at->format('M d, Y') : 'N/A' }}</p>
            </div>
        </div>

        <!-- Granted Permissions List -->
        <div class="space-y-3 pt-2">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                <i class="fas fa-key text-violet-500"></i>
                <span>Granted System Permissions ({{ $role->permissions->count() }})</span>
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                @forelse ($role->permissions as $permission)
                    <div class="p-3 bg-slate-50/80 rounded-xl border border-slate-200/60 flex items-start gap-3">
                        <span class="p-1.5 bg-emerald-100 text-emerald-600 rounded-lg text-xs mt-0.5">
                            <i class="fas fa-check"></i>
                        </span>
                        <div>
                            <p class="text-xs font-bold text-slate-800">{{ $permission->name }}</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $permission->description ?? 'No permission description' }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-4 text-center text-xs text-slate-400 italic">
                        No permissions currently granted to this role.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Assigned Users List -->
        <div class="space-y-3 pt-4 border-t border-slate-100">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                <i class="fas fa-users-gear text-violet-500"></i>
                <span>Users Assigned To This Role ({{ $role->users->count() }})</span>
            </h4>

            <div class="divide-y divide-slate-100 border border-slate-200/80 rounded-xl overflow-hidden bg-white">
                @forelse ($role->users as $user)
                    <div class="p-3.5 flex items-center justify-between hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-900 text-white font-bold flex items-center justify-center text-xs">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-900">{{ $user->name }}</p>
                                <p class="text-[11px] text-slate-400">{{ $user->email }}</p>
                            </div>
                        </div>
                        <x-forms.button 
                            href="{{ route('admin.users.show', $user) }}" 
                            variant="ghost" 
                            size="sm" 
                            icon="fas fa-arrow-right"
                            title="View User Account" 
                        />
                    </div>
                @empty
                    <div class="p-6 text-center text-xs text-slate-400 italic">
                        No user accounts currently assigned to this role.
                    </div>
                @endforelse
            </div>
        </div>

    </x-forms.card>

</div>
@endsection
