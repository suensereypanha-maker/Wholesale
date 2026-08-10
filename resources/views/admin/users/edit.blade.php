@extends('admin.layout.app')

@section('title', 'Edit User - ' . $user->name)

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
        title="Edit User Account" 
        description="Update profile information, security password, or assigned role" 
        icon="fas fa-user-pen"
        permission="manage_users"
    >
        <x-forms.form action="{{ route('admin.users.update', $user) }}" method="PUT">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Name Field -->
                <x-forms.input 
                    name="name" 
                    label="Full Name" 
                    :value="$user->name"
                    placeholder="e.g. Alexander Pierce" 
                    icon="fas fa-user" 
                    required 
                />

                <!-- Email Field -->
                <x-forms.input 
                    type="email" 
                    name="email" 
                    label="Email Address" 
                    :value="$user->email"
                    placeholder="alexander@b2bwholesale.com" 
                    icon="fas fa-envelope" 
                    required 
                />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- New Password Field -->
                <x-forms.input 
                    type="password" 
                    name="password" 
                    label="New Password (Optional)" 
                    placeholder="••••••••" 
                    icon="fas fa-lock" 
                    helpText="Leave blank to keep existing password"
                />

                <!-- Assign Role Field -->
                <x-forms.select 
                    name="role" 
                    label="Assign System Role" 
                    placeholder="-- Select User Role --"
                    :options="$roles->pluck('name', 'name')" 
                    :selected="$userRole"
                    icon="fas fa-user-shield" 
                />
            </div>

            <!-- Footer Action Buttons -->
            <x-slot:footer>
                <x-forms.button href="{{ route('admin.users.index') }}" variant="outline">
                    Cancel
                </x-forms.button>

                <x-forms.button type="submit" variant="primary" icon="fas fa-save">
                    Update User Account
                </x-forms.button>
            </x-slot:footer>

        </x-forms.form>
    </x-forms.card>

</div>
@endsection
