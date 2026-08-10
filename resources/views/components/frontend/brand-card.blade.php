@props(['brand'])

<a href="{{ route('frontend.products.index', ['brand' => $brand['slug']]) }}" class="b2b-brand-box">
    <span>{{ $brand['logo'] }}</span>
</a>
