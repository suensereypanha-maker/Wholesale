@extends('frontend.layouts.app')

@section('title', 'Company Profile - B2B Wholesale')

@section('content')
<div class="container">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb fs-7">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('frontend.account') }}">Account</a></li>
            <li class="breadcrumb-item active" aria-current="page">Company Profile</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="bg-white border rounded-4 shadow-sm p-4 p-md-5">
                <h2 class="h3 font-weight-800 text-dark mb-4 border-bottom pb-3">
                    <i class="fas fa-id-card text-emerald me-2"></i> Company Profile & Business Information
                </h2>

                <form action="{{ route('frontend.account.profile.update') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Company Name *</label>
                            <input type="text" name="company" class="form-control" value="{{ old('company', $customer['company'] ?? '') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Tax Registration (VAT / EIN)</label>
                            <input type="text" name="tax_number" class="form-control" value="{{ old('tax_number', $customer['tax_number'] ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Primary Contact Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $customer['name'] ?? '') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Contact Email *</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $customer['email'] ?? '') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Phone Number *</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer['phone'] ?? '') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Country *</label>
                            <input type="text" name="country" class="form-control" value="{{ old('country', $customer['country'] ?? 'United States') }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label font-weight-700 fs-7">Headquarters Address *</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $customer['address'] ?? '') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label font-weight-700 fs-7">City *</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', $customer['city'] ?? '') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label font-weight-700 fs-7">State / Province *</label>
                            <input type="text" name="province" class="form-control" value="{{ old('province', $customer['province'] ?? '') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label font-weight-700 fs-7">Postal Zip Code *</label>
                            <input type="text" name="zip" class="form-control" value="{{ old('zip', $customer['zip'] ?? '') }}" required>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <button type="submit" class="btn b2b-btn-accent font-weight-800 px-4">
                            <i class="fas fa-save me-1"></i> Save Changes
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
