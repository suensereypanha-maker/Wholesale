@extends('admin.layout.app')

@section('title', 'Edit Role - ' . $role->name)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Back Header Link -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-indigo-600 transition-colors">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Back to Role List</span>
        </a>
    </div>

    <!-- Form Container Card -->
    <x-forms.card 
        title="Edit Security Role & Permissions" 
        description="Configure granular CRUD permissions (Create, Read, Update, Delete) for {{ $role->name }}" 
        icon="fas fa-user-pen"
        permission="manage_roles"
    >
        <x-forms.form action="{{ route('admin.roles.update', $role) }}" method="PUT">
            
            <!-- Role Details -->
            <div class="space-y-5 mb-6">
                <x-forms.input 
                    name="name" 
                    label="Role Name" 
                    :value="old('name', $role->name)"
                    placeholder="e.g. Store Manager" 
                    icon="fas fa-user-shield" 
                    required 
                    helpText="Unique title representing security level or job function"
                />

                <x-forms.textarea 
                    name="description" 
                    label="Role Description" 
                    :value="old('description', $role->description)"
                    placeholder="Briefly describe the security boundaries and responsibilities assigned to this role..." 
                    rows="3"
                    helpText="Optional summary of access scope"
                />
            </div>

            <!-- Permission Selection Matrix Grouped by Module -->
            <div class="pt-6 border-t border-slate-100">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <i class="fas fa-key text-indigo-600"></i>
                            <span>Module Access & CRUD Permissions</span>
                        </h4>
                        <p class="text-xs text-slate-500">Check or uncheck individual Create, Read, Update, Delete capabilities allowed by Admin</p>
                    </div>
                </div>

                @php
                    $groupedPermissions = $permissions->groupBy(function($p) {
                        return $p->module ?? 'General System';
                    });
                @endphp

                <div class="space-y-5">
                    @foreach ($groupedPermissions as $moduleName => $modulePermissions)
                        <div class="bg-white rounded-xl border border-slate-200/80 shadow-2xs overflow-hidden">
                            <div class="px-4 py-3 bg-slate-50/80 border-b border-slate-200/60 flex items-center justify-between">
                                <h5 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                                    <i class="fas fa-layer-group text-indigo-500"></i>
                                    <span>{{ $moduleName }}</span>
                                </h5>
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-200/60 text-slate-600">
                                    {{ $modulePermissions->count() }} Actions
                                </span>
                            </div>
                            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                @foreach ($modulePermissions as $permission)
                                    @php
                                        $badgeColor = match(true) {
                                            str_contains($permission->name, '.view') || str_starts_with($permission->name, 'view_') => 'bg-emerald-50 border-emerald-200 text-emerald-700',
                                            str_contains($permission->name, '.create') => 'bg-blue-50 border-blue-200 text-blue-700',
                                            str_contains($permission->name, '.edit') => 'bg-amber-50 border-amber-200 text-amber-700',
                                            str_contains($permission->name, '.delete') => 'bg-rose-50 border-rose-200 text-rose-700',
                                            default => 'bg-indigo-50 border-indigo-200 text-indigo-700',
                                        };
                                    @endphp
                                    <div class="p-3 rounded-lg border border-slate-200/70 hover:border-indigo-300 hover:bg-slate-50 transition-all">
                                        <x-forms.checkbox 
                                            name="permissions[]" 
                                            value="{{ $permission->id }}" 
                                            label="{{ $permission->action }}" 
                                            description="{{ $permission->description }}"
                                            :checked="in_array($permission->id, old('permissions', $assignedPermissionIds))"
                                        />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Footer Action Buttons -->
            <x-slot:footer>
                <x-forms.button href="{{ route('admin.roles.index') }}" variant="outline">
                    Cancel
                </x-forms.button>

                <x-forms.button type="submit" variant="primary" icon="fas fa-floppy-disk" class="!bg-indigo-600 hover:!bg-indigo-700">
                    Save Role Changes
                </x-forms.button>
            </x-slot:footer>

        </x-forms.form>
    </x-forms.card>

</div>
@endsection
