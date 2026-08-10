@extends('frontend.layouts.app')

@section('title', 'Contact Support & Corporate Desk - Apex IT')

@section('content')
<div class="container">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb fs-7">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Contact & Support</li>
        </ol>
    </nav>

    <div class="row g-4 mb-5">
        
        <!-- Contact Information -->
        <div class="col-lg-5">
            <div class="bg-white border rounded-4 shadow-sm p-4 p-md-5 h-100">
                <span class="badge bg-emerald-subtle text-emerald border border-emerald font-weight-700 px-3 py-2 rounded-pill mb-3">
                    Corporate Helpdesk
                </span>
                <h2 class="h3 font-weight-800 text-dark mb-4">Get in Touch with Our Procurement Specialists</h2>
                
                <div class="d-grid gap-4 text-secondary fs-6">
                    <div class="d-flex align-items-start gap-3">
                        <div class="b2b-cat-icon text-emerald bg-emerald-subtle mb-0" style="width: 45px; height: 45px; font-size: 1.2rem; flex-shrink: 0;">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <strong class="text-dark d-block">Corporate Office Address</strong>
                            100 Technology Parkway, Tech Quarter, San Jose, CA 95134, United States
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="b2b-cat-icon text-primary bg-primary-subtle mb-0" style="width: 45px; height: 45px; font-size: 1.2rem; flex-shrink: 0;">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <strong class="text-dark d-block">Wholesale Sales Hotlines</strong>
                            Toll-Free: +1 (800) 555-0199<br>
                            Direct Desk: +1 (408) 555-0188
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="b2b-cat-icon text-info bg-info-subtle mb-0" style="width: 45px; height: 45px; font-size: 1.2rem; flex-shrink: 0;">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <strong class="text-dark d-block">Official Email Desks</strong>
                            General Enquiries: info@apexit-distributor.com<br>
                            RFQs & Quotes: rfq@apexit-distributor.com
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="b2b-cat-icon text-warning bg-warning-subtle mb-0" style="width: 45px; height: 45px; font-size: 1.2rem; flex-shrink: 0;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <strong class="text-dark d-block">Warehouse Logistics Hours</strong>
                            Monday – Friday: 8:00 AM – 6:00 PM PST<br>
                            Saturday (Freight Only): 9:00 AM – 1:00 PM PST
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-7">
            <div class="bg-white border rounded-4 shadow-sm p-4 p-md-5">
                <h3 class="h4 font-weight-800 text-dark mb-4 border-bottom pb-2">Send Us a Direct Message</h3>

                <form action="#" method="POST" onsubmit="event.preventDefault(); showB2BToast('Thank you for contacting Apex IT! An account manager will reply shortly.', 'success');">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Your Name *</label>
                            <input type="text" class="form-control" placeholder="John Doe" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Company Name *</label>
                            <input type="text" class="form-control" placeholder="Acme Technologies" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Email Address *</label>
                            <input type="email" class="form-control" placeholder="john@acme.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-weight-700 fs-7">Phone Number *</label>
                            <input type="text" class="form-control" placeholder="+1 (555) 000-0000" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label font-weight-700 fs-7">Subject / Inquiry Type *</label>
                            <select class="form-select" required>
                                <option value="">Select Inquiry Topic</option>
                                <option value="product">Product Availability & Lead Times</option>
                                <option value="credit">Credit Terms & Net 30 Application</option>
                                <option value="freight">Palletized Freight & Tracking</option>
                                <option value="warranty">Warranty & RMA Support</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label font-weight-700 fs-7">Message *</label>
                            <textarea class="form-control" rows="4" placeholder="How can our wholesale team assist you?" required></textarea>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn b2b-btn-accent btn-lg font-weight-800 px-5 rounded-pill shadow-sm">
                            <i class="fas fa-paper-plane me-2"></i> Send Inquiry
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
