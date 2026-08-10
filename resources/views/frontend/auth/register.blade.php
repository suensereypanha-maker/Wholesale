@extends('frontend.layouts.app')

@section('title', 'Apply for B2B Wholesale Account')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-7">
            
            <div class="bg-white border rounded-4 shadow-sm p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="b2b-cat-icon mx-auto mb-3 text-primary bg-primary-subtle" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="fas fa-building"></i>
                    </div>
                    <h2 class="h3 font-weight-800 text-dark">Commercial Account Application</h2>
                    <p class="text-secondary fs-7">Register your organization to unlock bulk pricing and credit terms</p>
                </div>

                <form action="{{ route('frontend.register.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label font-weight-700 fs-7">Company Registered Name *</label>
                            <input type="text" name="company" class="form-control" placeholder="e.g. Pacific Hardware Distributors Co." required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Tax Registration Number (VAT / EIN)</label>
                            <input type="text" name="tax_number" class="form-control" placeholder="e.g. VAT-987654321">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Authorized Representative Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="Jane Smith" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Business Email Address *</label>
                            <input type="email" name="email" class="form-control" placeholder="jane@pacifichardware.com" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Phone Number *</label>
                            <input type="text" name="phone" class="form-control" placeholder="+1 (555) 345-6789" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Create Account Password *</label>
                            <input type="password" name="password" class="form-control" placeholder="At least 6 characters" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Confirm Password *</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn b2b-btn-accent btn-lg w-100 font-weight-800 mb-3 shadow-sm">
                            <i class="fas fa-user-plus me-2"></i> Register Commercial Account
                        </button>
                    </div>

                    <div class="text-center fs-7 text-secondary">
                        Already have an account? 
                        <a href="{{ route('frontend.login') }}" class="text-emerald font-weight-700">Log In Here</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
