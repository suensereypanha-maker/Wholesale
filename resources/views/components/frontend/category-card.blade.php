@props(['category'])

<a href="{{ route('frontend.products.index', ['category' => $category['slug']]) }}" class="b2b-cat-card">
    <div class="b2b-cat-icon">
        <i class="{{ $category['icon'] }}"></i>
    </div>
    <h6 class="font-weight-800 text-dark mb-1">{{ $category['name'] }}</h6>
    <p class="text-secondary fs-7 mb-0">{{ $category['count'] }} Wholesale Products</p>
</a>
