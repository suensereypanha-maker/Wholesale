@extends('admin.layout.app')

@section('title', 'Create New Role')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Back Header Link -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-violet-600 transition-colors">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Back to Role List</span>
        </a>
    </div>

    <!-- Form Container Card -->
    <x-forms.card 
        title="Create New System Role" 
        description="Configure a new access role and grant granular system permissions" 
        icon="fas fa-shield-plus"
        permission="manage_roles"
    >
        <x-forms.form action="{{ route('admin.roles.store') }}" method="POST">
            
            <!-- Role Details -->
            <div class="space-y-5">
                <x-forms.input 
                    name="name" 
                    label="Role Name" 
                    placeholder="e.g. Store Manager" 
                    icon="fas fa-user-shield" 
                    required 
                    helpText="Unique title representing security level or job function"
                />

                <x-forms.textarea 
                    name="description" 
                    label="Role Description" 
                    placeholder="Briefly describe the security boundaries and responsibilities assigned to this role..." 
                    rows="3"
                    helpText="Optional summary of access scope"
                />
            </div>

            <!-- Permission Selection Grid -->
            <div class="pt-4 border-t border-slate-100">
                <div class="mb-4">
                    <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-key text-violet-500"></i>
                        <span>Assign System Permissions</span>
                    </h4>
                    <p class="text-xs text-slate-500">Check permissions to include in this security role profile</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 p-4 bg-slate-50/70 rounded-xl border border-slate-200/60">
                    @forelse ($permissions as $permission)
                        <div class="bg-white p-3 rounded-lg border border-slate-200/80 shadow-2xs hover:border-violet-300 transition-colors">
                            <x-forms.checkbox 
                                name="permissions[]" 
                                value="{{ $permission->id }}" 
                                label="{{ $permission->name }}" 
                                description="{{ $permission->description }}"
                            />
                        </div>
                    @empty
                        <div class="col-span-full py-4 text-center text-xs text-slate-400">
                            No permissions registered in the system.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Footer Action Buttons -->
            <x-slot:footer>
                <x-forms.button href="{{ route('admin.roles.index') }}" variant="outline">
                    Cancel
                </x-forms.button>

                <x-forms.button type="submit" variant="primary" icon="fas fa-check" class="!bg-violet-600 hover:!bg-violet-700">
                    Create Role Profile
                </x-forms.button>
            </x-slot:footer>

        </x-forms.form>
    </x-forms.card>

</div>
@endsection
