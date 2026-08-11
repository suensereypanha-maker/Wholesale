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
        title="Edit User / B2B Customer Account" 
        description="Update profile details, company tax information, status, or security settings" 
        icon="fas fa-user-pen"
        permission="manage_users"
    >
        <x-forms.form action="{{ route('admin.users.update', $user) }}" method="PUT">
            
            <!-- Contact & Credentials Section -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Account Credentials & Status</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <x-forms.input 
                        name="name" 
                        label="Full Representative Name" 
                        :value="$user->name"
                        placeholder="e.g. Jane Smith" 
                        icon="fas fa-user" 
                        required 
                    />

                    <x-forms.input 
                        type="email" 
                        name="email" 
                        label="Business Email Address" 
                        :value="$user->email"
                        placeholder="jane@company.com" 
                        icon="fas fa-envelope" 
                        required 
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <x-forms.input 
                        type="password" 
                        name="password" 
                        label="New Password (Optional)" 
                        placeholder="••••••••" 
                        icon="fas fa-lock" 
                        helpText="Leave blank to keep current"
                    />

                    <x-forms.select 
                        name="role" 
                        label="Assign System Role" 
                        placeholder="-- Select User Role --"
                        :options="$roles->pluck('name', 'name')" 
                        :selected="$userRole"
                        icon="fas fa-user-shield" 
                    />

                    <x-forms.select 
                        name="status" 
                        label="Account Status" 
                        :options="['active' => 'Active', 'pending' => 'Pending Approval', 'rejected' => 'Rejected']" 
                        :selected="$user->status"
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
                        :value="$user->company"
                        placeholder="e.g. Pacific Hardware Distributors Co." 
                        icon="fas fa-building" 
                    />

                    <x-forms.input 
                        name="tax_number" 
                        label="Tax Registration Number (VAT / EIN)" 
                        :value="$user->tax_number"
                        placeholder="e.g. VAT-987654321 (Optional)" 
                        icon="fas fa-file-invoice" 
                    />

                    <x-forms.input 
                        name="phone" 
                        label="Phone Number" 
                        :value="$user->phone"
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
                        :value="$user->address"
                        placeholder="100 Tech Enterprise Way" 
                        icon="fas fa-location-dot" 
                    />
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-5">
                    <x-forms.input name="city" label="City" :value="$user->city" placeholder="San Jose" icon="fas fa-city" />
                    <x-forms.input name="province" label="State / Province" :value="$user->province" placeholder="California" icon="fas fa-map" />
                    <x-forms.input name="zip" label="Postal Zip Code" :value="$user->zip" placeholder="95134" icon="fas fa-mail-bulk" />
                    <x-forms.input name="country" label="Country" :value="$user->country" placeholder="United States" icon="fas fa-globe" />
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
                        :selected="$user->tier ?? 'Standard Wholesale'"
                        icon="fas fa-award" 
                    />

                    <x-forms.input 
                        type="number" 
                        step="0.01" 
                        name="credit_limit" 
                        label="Credit Limit ($)" 
                        :value="$user->credit_limit"
                        placeholder="0.00" 
                        icon="fas fa-credit-card" 
                    />

                    <x-forms.input 
                        type="number" 
                        step="0.01" 
                        name="wholesale_discount" 
                        label="Wholesale Discount (%)" 
                        :value="$user->wholesale_discount"
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

                <x-forms.button type="submit" variant="primary" icon="fas fa-save">
                    Update User Account
                </x-forms.button>
            </x-slot:footer>

        </x-forms.form>
    </x-forms.card>

</div>
@endsection
