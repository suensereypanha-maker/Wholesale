@extends('admin.layout.app')

@section('title', 'Permission Details - ' . $permission->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.permissions.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Back to Permission List</span>
        </a>

        <div class="flex items-center gap-3">
            <x-forms.button 
                href="{{ route('admin.permissions.edit', $permission) }}" 
                variant="outline" 
                icon="fas fa-pen-to-square"
                permission="manage_roles"
            >
                Edit Permission
            </x-forms.button>
        </div>
    </div>

    <!-- Main Permission Overview Card -->
    <x-forms.card title="Permission Capability Profile" description="System access key and role mapping configuration" icon="fas fa-key">
        
        <!-- Header Info -->
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 border-b border-slate-100 pb-6">
            <div class="w-16 h-16 rounded-2xl bg-blue-100 text-blue-700 font-black flex items-center justify-center text-2xl shadow-sm border border-blue-200 shrink-0">
                <i class="fas fa-code"></i>
            </div>

            <div class="space-y-2 text-center sm:text-left flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-xl font-mono font-bold text-blue-600">{{ $permission->name }}</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Guard: <span class="font-mono font-bold text-slate-700">{{ $permission->guard_name }}</span></p>
                    </div>

                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 self-center sm:self-auto">
                        <i class="fas fa-shield-halved text-xs"></i>
                        Assigned to {{ $permission->roles->count() }} {{ Str::plural('Role', $permission->roles->count()) }}
                    </span>
                </div>

                <p class="text-xs text-slate-600 leading-relaxed pt-1">
                    {{ $permission->description ?? 'No description defined for this system permission.' }}
                </p>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 border-b border-slate-100 pb-6">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                <p class="text-[11px] font-bold text-slate-400 uppercase">Permission ID</p>
                <p class="text-sm font-mono font-extrabold text-slate-800 mt-1">#{{ $permission->id }}</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                <p class="text-[11px] font-bold text-slate-400 uppercase">Assigned Roles</p>
                <p class="text-sm font-extrabold text-violet-600 mt-1">{{ $permission->roles->count() }} Roles</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                <p class="text-[11px] font-bold text-slate-400 uppercase">Created Date</p>
                <p class="text-sm font-extrabold text-slate-800 mt-1">{{ $permission->created_at ? $permission->created_at->format('M d, Y') : 'N/A' }}</p>
            </div>
        </div>

        <!-- Roles Possessing Permission List -->
        <div class="space-y-3 pt-2">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                <i class="fas fa-user-shield text-blue-500"></i>
                <span>Roles Possessing This Permission ({{ $permission->roles->count() }})</span>
            </h4>

            <div class="divide-y divide-slate-100 border border-slate-200/80 rounded-xl overflow-hidden bg-white">
                @forelse ($permission->roles as $role)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-violet-50 text-violet-600 font-bold flex items-center justify-center text-sm border border-violet-100">
                                <i class="fas fa-shield"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-900">{{ $role->name }}</p>
                                <p class="text-[11px] text-slate-500">{{ $role->description ?? 'No description' }}</p>
                            </div>
                        </div>
                        
                        <x-forms.button 
                            href="{{ route('admin.roles.show', $role) }}" 
                            variant="ghost" 
                            size="sm" 
                            icon="fas fa-arrow-right"
                            title="View Role Details" 
                        />
                    </div>
                @empty
                    <div class="p-6 text-center text-xs text-slate-400 italic">
                        No roles currently possess this permission key.
                    </div>
                @endforelse
            </div>
        </div>

    </x-forms.card>

</div>
@endsection
