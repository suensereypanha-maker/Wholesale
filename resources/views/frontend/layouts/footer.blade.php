<footer class="bg-dark text-white pt-5 pb-4 mt-5">
    <div class="container">
        <div class="row g-4">
            
            <!-- Company Info Column -->
            <div class="col-12 col-md-4">
                <div class="b2b-logo text-white mb-3 fs-3">
                    <i class="fas fa-microchip text-success"></i>
                    <span>APEX<span class="text-success">IT</span> Wholesale</span>
                </div>
                <p class="text-secondary font-weight-400 fs-7 mb-4">
                    Apex IT Wholesale is a premier distributor of commercial enterprise computers, laptops, workstation components, and networking infrastructure. Serving Fortune 500 companies, educational institutions, government agencies, and IT resellers worldwide.
                </p>
                <div class="d-flex gap-3 text-secondary fs-5">
                    <a href="#" class="text-secondary hover-white"><i class="fab fa-linkedin"></i></a>
                    <a href="#" class="text-secondary hover-white"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-secondary hover-white"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-secondary hover-white"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-6 col-md-2">
                <h6 class="text-uppercase font-weight-700 text-light mb-3">Quick Links</h6>
                <ul class="list-unstyled text-secondary fs-7 d-grid gap-2">
                    <li><a href="{{ route('frontend.home') }}" class="text-secondary">Home</a></li>
                    <li><a href="{{ route('frontend.products.index') }}" class="text-secondary">All Products</a></li>
                    <li><a href="{{ route('frontend.categories.index') }}" class="text-secondary">Product Categories</a></li>
                    <li><a href="{{ route('frontend.quotes.create') }}" class="text-secondary">Request Wholesale Quote</a></li>
                    <li><a href="{{ route('frontend.about') }}" class="text-secondary">About Apex IT</a></li>
                    <li><a href="{{ route('frontend.contact') }}" class="text-secondary">Contact & Support</a></li>
                </ul>
            </div>

            <!-- Top Categories -->
            <div class="col-6 col-md-3">
                <h6 class="text-uppercase font-weight-700 text-light mb-3">Popular Hardware</h6>
                <ul class="list-unstyled text-secondary fs-7 d-grid gap-2">
                    <li><a href="{{ route('frontend.products.index', ['category' => 'laptop']) }}" class="text-secondary">Enterprise Business Laptops</a></li>
                    <li><a href="{{ route('frontend.products.index', ['category' => 'desktop']) }}" class="text-secondary">Small Form Factor Desktops</a></li>
                    <li><a href="{{ route('frontend.products.index', ['category' => 'workstation']) }}" class="text-secondary">CAD & Render Workstations</a></li>
                    <li><a href="{{ route('frontend.products.index', ['category' => 'gpu']) }}" class="text-secondary">NVIDIA RTX Enterprise GPUs</a></li>
                    <li><a href="{{ route('frontend.products.index', ['category' => 'cpu']) }}" class="text-secondary">Intel Xeon & AMD EPYC CPUs</a></li>
                    <li><a href="{{ route('frontend.products.index', ['category' => 'ssd']) }}" class="text-secondary">PCIe Gen4 Enterprise SSDs</a></li>
                </ul>
            </div>

            <!-- Contact & Corporate Desk -->
            <div class="col-12 col-md-3">
                <h6 class="text-uppercase font-weight-700 text-light mb-3">Corporate Headquarters</h6>
                <ul class="list-unstyled text-secondary fs-7 d-grid gap-3">
                    <li class="d-flex align-items-start gap-2">
                        <i class="fas fa-map-marker-alt text-success mt-1"></i>
                        <span>100 Technology Parkway, Tech Quarter, San Jose, CA 95134, USA</span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="fas fa-phone text-success"></i>
                        <span>Toll-Free: +1 (800) 555-0199</span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="fas fa-envelope text-success"></i>
                        <span>wholesale@apexit-distributor.com</span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="fas fa-clock text-success"></i>
                        <span>Mon - Fri: 8:00 AM - 6:00 PM PST</span>
                    </li>
                </ul>
            </div>

        </div>

        <hr class="border-secondary my-4">

        <div class="row align-items-center fs-7 text-secondary">
            <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                &copy; {{ date('Y') }} Apex IT Wholesale Corporation. All rights reserved. B2B Commercial Distributor.
            </div>
            <div class="col-md-6 text-center text-md-end">
                <span class="me-3"><i class="fas fa-university me-1 text-light"></i> Wire Transfer / ACH</span>
                <span class="me-3"><i class="fas fa-file-invoice-dollar me-1 text-light"></i> Net 30/60 Credit Terms</span>
                <span><i class="fab fa-cc-visa me-1 text-light"></i> Visa / MasterCard</span>
            </div>
        </div>
    </div>
</footer>
