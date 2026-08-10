@extends('admin.layout.app')

@section('title', 'Create New User')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Back Header Link -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-indigo-600 transition-colors">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Back to User List</span>
        </a>
    </div>

    <!-- Form Container Card -->
    <x-forms.card 
        title="Create New Admin User" 
        description="Add a new administrator or manager account and assign security roles" 
        icon="fas fa-user-plus"
        permission="manage_users"
    >
        <x-forms.form action="{{ route('admin.users.store') }}" method="POST">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Name Field -->
                <x-forms.input 
                    name="name" 
                    label="Full Name" 
                    placeholder="e.g. Alexander Pierce" 
                    icon="fas fa-user" 
                    required 
                    helpText="User's official name display"
                />

                <!-- Email Field -->
                <x-forms.input 
                    type="email" 
                    name="email" 
                    label="Email Address" 
                    placeholder="alexander@b2bwholesale.com" 
                    icon="fas fa-envelope" 
                    required 
                    helpText="Must be a unique valid email"
                />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Password Field -->
                <x-forms.input 
                    type="password" 
                    name="password" 
                    label="Account Password" 
                    placeholder="••••••••" 
                    icon="fas fa-lock" 
                    required 
                    helpText="Minimum 8 characters"
                />

                <!-- Assign Role Field -->
                <x-forms.select 
                    name="role" 
                    label="Assign System Role" 
                    placeholder="-- Select User Role --"
                    :options="$roles->pluck('name', 'name')" 
                    icon="fas fa-user-shield" 
                    helpText="Defines user permissions in system"
                />
            </div>

            <!-- Footer Action Buttons -->
            <x-slot:footer>
                <x-forms.button href="{{ route('admin.users.index') }}" variant="outline">
                    Cancel
                </x-forms.button>

                <x-forms.button type="submit" variant="primary" icon="fas fa-check">
                    Create User Account
                </x-forms.button>
            </x-slot:footer>

        </x-forms.form>
    </x-forms.card>

</div>
@endsection
