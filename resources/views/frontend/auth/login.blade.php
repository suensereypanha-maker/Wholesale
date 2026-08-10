@extends('frontend.layouts.app')

@section('title', 'B2B Customer Portal Login')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">
            
            <div class="bg-white border rounded-4 shadow-sm p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="b2b-cat-icon mx-auto mb-3 text-emerald bg-emerald-subtle" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="fas fa-user-lock"></i>
                    </div>
                    <h2 class="h3 font-weight-800 text-dark">B2B Customer Login</h2>
                    <p class="text-secondary fs-7">Access your wholesale pricing tiers & purchase orders</p>
                </div>

                <!-- Demo Account Information Box -->
               

                <form action="{{ route('frontend.login.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label font-weight-700 fs-7">Business Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope text-secondary"></i></span>
                            <input type="email" name="email" class="form-control" value="john@example.com" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label font-weight-700 fs-7 mb-0">Password</label>
                            <a href="#" class="fs-7 text-emerald text-decoration-none">Forgot Password?</a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key text-secondary"></i></span>
                            <input type="password" name="password" class="form-control" value="123456" required>
                        </div>
                    </div>

                    <button type="submit" class="btn b2b-btn-accent btn-lg w-100 font-weight-800 mb-3 shadow-sm">
                        <i class="fas fa-sign-in-alt me-2"></i> Log In to Portal
                    </button>

                    <div class="text-center fs-7 text-secondary">
                        Don't have a commercial account? 
                        <a href="{{ route('frontend.register') }}" class="text-emerald font-weight-700">Apply for B2B Account</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
