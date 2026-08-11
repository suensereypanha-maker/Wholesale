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
        title="Create New User / B2B Customer Account" 
        description="Add a new user, customer or admin account with full profile details and security roles" 
        icon="fas fa-user-plus"
        permission="manage_users"
    >
        <x-forms.form action="{{ route('admin.users.store') }}" method="POST">
            
            <!-- Contact & Credentials Section -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Account Credentials</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <x-forms.input 
                        name="name" 
                        label="Full Representative Name" 
                        placeholder="e.g. Jane Smith" 
                        icon="fas fa-user" 
                        required 
                    />

                    <x-forms.input 
                        type="email" 
                        name="email" 
                        label="Business Email Address" 
                        placeholder="jane@company.com" 
                        icon="fas fa-envelope" 
                        required 
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <x-forms.input 
                        type="password" 
                        name="password" 
                        label="Account Password" 
                        placeholder="••••••••" 
                        icon="fas fa-lock" 
                        required 
                        helpText="Minimum 8 characters"
                    />

                    <x-forms.select 
                        name="role" 
                        label="Assign System Role" 
                        placeholder="-- Select User Role --"
                        :options="$roles->pluck('name', 'name')" 
                        icon="fas fa-user-shield" 
                    />

                    <x-forms.select 
                        name="status" 
                        label="Account Status" 
                        :options="['active' => 'Active', 'pending' => 'Pending Approval', 'rejected' => 'Rejected']" 
                        selected="active"
                        icon="fas fa-circle-check" 
                    />
                </div>
            </div>

            <!-- B2B Organization & Tax Info -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Company & Business Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <x-forms.input 
                        name="company" 
                        label="Company Registered Name" 
                        placeholder="e.g. Pacific Hardware Distributors Co." 
                        icon="fas fa-building" 
                    />

                    <x-forms.input 
                        name="tax_number" 
                        label="Tax Registration Number (VAT / EIN)" 
                        placeholder="e.g. VAT-987654321 (Optional)" 
                        icon="fas fa-file-invoice" 
                        helpText="Leave empty if not available"
                    />

                    <x-forms.input 
                        name="phone" 
                        label="Phone Number" 
                        placeholder="+1 (555) 345-6789" 
                        icon="fas fa-phone" 
                    />
                </div>
            </div>

            <!-- Address & Location -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Address & Location</h3>
                <div class="grid grid-cols-1 gap-5">
                    <x-forms.input 
                        name="address" 
                        label="Headquarters Address" 
                        placeholder="100 Tech Enterprise Way" 
                        icon="fas fa-location-dot" 
                    />
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-5">
                    <x-forms.input name="city" label="City" placeholder="San Jose" icon="fas fa-city" />
                    <x-forms.input name="province" label="State / Province" placeholder="California" icon="fas fa-map" />
                    <x-forms.input name="zip" label="Postal Zip Code" placeholder="95134" icon="fas fa-mail-bulk" />
                    <x-forms.input name="country" label="Country" placeholder="United States" icon="fas fa-globe" />
                </div>
            </div>

            <!-- Wholesale Tier & Credit Limits -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Wholesale Tier & Credit Setup</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <x-forms.select 
                        name="tier" 
                        label="Wholesale Tier Level" 
                        :options="[
                            'Standard Wholesale' => 'Standard Wholesale (5%)',
                            'Bulk Silver' => 'Bulk Silver (8-10%)',
                            'Wholesale Gold' => 'Wholesale Gold (12-15%)',
                            'VIP Platinum Wholesale' => 'VIP Platinum Wholesale (20%+)'
                        ]" 
                        selected="Standard Wholesale"
                        icon="fas fa-award" 
                    />

                    <x-forms.input 
                        type="number" 
                        step="0.01" 
                        name="credit_limit" 
                        label="Credit Limit ($)" 
                        placeholder="0.00" 
                        icon="fas fa-credit-card" 
                    />

                    <x-forms.input 
                        type="number" 
                        step="0.01" 
                        name="wholesale_discount" 
                        label="Wholesale Discount (%)" 
                        placeholder="0.00" 
                        icon="fas fa-percent" 
                    />
                </div>
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
