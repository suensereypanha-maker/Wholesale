@extends('admin.layout.app')

@section('title', 'Create New Permission')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Back Header Link -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.permissions.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Back to Permission List</span>
        </a>
    </div>

    <!-- Form Container Card -->
    <x-forms.card 
        title="Create System Permission" 
        description="Register a new granular capability key for role authorization" 
        icon="fas fa-key"
        permission="manage_roles"
    >
        <x-forms.form action="{{ route('admin.permissions.store') }}" method="POST">
            
            <div class="space-y-5">
                <!-- Name Field -->
                <x-forms.input 
                    name="name" 
                    label="Permission Key Name" 
                    placeholder="e.g. export_customer_data" 
                    icon="fas fa-code" 
                    required 
                    helpText="Snake_case string used in gate checks (e.g. @can('export_customer_data'))"
                />

                <!-- Description Field -->
                <x-forms.textarea 
                    name="description" 
                    label="Permission Description" 
                    placeholder="Briefly describe what action or module access this permission grants..." 
                    rows="3"
                    helpText="Detailed explanation for administrators during role configuration"
                />
            </div>

            <!-- Footer Action Buttons -->
            <x-slot:footer>
                <x-forms.button href="{{ route('admin.permissions.index') }}" variant="outline">
                    Cancel
                </x-forms.button>

                <x-forms.button type="submit" variant="primary" icon="fas fa-check" class="!bg-blue-600 hover:!bg-blue-700">
                    Create Permission
                </x-forms.button>
            </x-slot:footer>

        </x-forms.form>
    </x-forms.card>

</div>
@endsection
