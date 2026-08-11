@props(['brand'])

<a href="{{ route('frontend.products.index', ['brand' => $brand['slug']]) }}" class="b2b-brand-card">
    <div class="b2b-brand-card-inner" style="--brand-accent: {{ $brand['accent'] ?? '#059669' }};">
        <div class="b2b-brand-icon-wrapper">
            <i class="{{ $brand['icon'] ?? 'fas fa-building' }}"></i>
        </div>
        <div class="b2b-brand-details">
            <h4 class="b2b-brand-title">{{ $brand['name'] ?? $brand['logo'] }}</h4>
            <span class="b2b-brand-count">{{ $brand['count'] ?? 0 }} Models</span>
        </div>
    </div>
</a>
