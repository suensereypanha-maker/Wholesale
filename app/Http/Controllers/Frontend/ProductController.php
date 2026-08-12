<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Data\FrontendData;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $allProducts = collect(FrontendData::products());
        $categories = FrontendData::categories();
        $brands = FrontendData::brands();

        // Filtering
        $query = $allProducts;

        if ($request->filled('search')) {
            $search = strtolower(trim($request->search));
            $query = $query->filter(function ($p) use ($search) {
                $name = strtolower($p['name'] ?? '');
                $sku = strtolower($p['sku'] ?? '');
                $brand = strtolower($p['brand'] ?? '');
                $category = strtolower($p['category'] ?? '');
                $desc = strtolower($p['description'] ?? '');
                $specs = strtolower(json_encode($p['specifications'] ?? []));

                return str_contains($name, $search) ||
                       str_contains($sku, $search) ||
                       str_contains($brand, $search) ||
                       str_contains($category, $search) ||
                       str_contains($desc, $search) ||
                       str_contains($specs, $search);
            });
        }

        if ($request->filled('category')) {
            $category = strtolower(trim($request->category));
            $query = $query->filter(function ($p) use ($category) {
                $catSlug = strtolower($p['category_slug'] ?? '');
                $catName = strtolower($p['category'] ?? '');
                return $catSlug === $category ||
                       str_contains($catSlug, $category) ||
                       str_contains($category, $catSlug) ||
                       $catName === $category ||
                       str_contains($catName, $category);
            });
        }

        if ($request->filled('brand')) {
            $brand = strtolower(trim($request->brand));
            $query = $query->filter(function ($p) use ($brand) {
                $brandSlug = strtolower($p['brand_slug'] ?? '');
                $brandName = strtolower($p['brand'] ?? '');
                return $brandSlug === $brand ||
                       str_contains($brandSlug, $brand) ||
                       str_contains($brand, $brandSlug) ||
                       $brandName === $brand ||
                       str_contains($brandName, $brand);
            });
        }

        if ($request->filled('min_price')) {
            $minPrice = (float) $request->min_price;
            $query = $query->filter(fn($p) => (float)($p['price'] ?? 0) >= $minPrice);
        }

        if ($request->filled('max_price')) {
            $maxPrice = (float) $request->max_price;
            $query = $query->filter(fn($p) => (float)($p['price'] ?? 0) <= $maxPrice);
        }

        if ($request->filled('max_moq')) {
            $maxMoq = (int) $request->max_moq;
            $query = $query->filter(fn($p) => (int)($p['moq'] ?? 1) <= $maxMoq);
        }

        if ($request->boolean('in_stock')) {
            $query = $query->filter(fn($p) => (int)($p['stock'] ?? 0) > 0);
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        if ($sort === 'price_asc') {
            $query = $query->sortBy('price');
        } elseif ($sort === 'price_desc') {
            $query = $query->sortByDesc('price');
        } elseif ($sort === 'name') {
            $query = $query->sortBy('name');
        } elseif ($sort === 'rating') {
            $query = $query->sortByDesc('rating');
        } else {
            $query = $query->sortByDesc('id');
        }

        // Custom Pagination
        $perPage = 12;
        $page = (int) $request->get('page', 1);
        $total = $query->count();
        $items = $query->slice(($page - 1) * $perPage, $perPage)->values();

        $products = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('frontend.products.index', compact('products', 'categories', 'brands'));
    }

    public function apiSearch(Request $request)
    {
        $queryStr = trim($request->get('q', ''));
        if (strlen($queryStr) < 2) {
            return response()->json([]);
        }

        $search = strtolower($queryStr);
        $allProducts = collect(FrontendData::products());

        $results = $allProducts->filter(function ($p) use ($search) {
            $name = strtolower($p['name'] ?? '');
            $sku = strtolower($p['sku'] ?? '');
            $brand = strtolower($p['brand'] ?? '');
            $category = strtolower($p['category'] ?? '');
            $desc = strtolower($p['description'] ?? '');
            $specs = strtolower(json_encode($p['specifications'] ?? []));

            return str_contains($name, $search) ||
                   str_contains($sku, $search) ||
                   str_contains($brand, $search) ||
                   str_contains($category, $search) ||
                   str_contains($desc, $search) ||
                   str_contains($specs, $search);
        })->take(6)->values()->map(function ($p) {
            return [
                'id' => $p['id'],
                'name' => $p['name'],
                'sku' => $p['sku'],
                'brand' => $p['brand'],
                'price' => '$' . number_format($p['price'], 2),
                'image' => $p['image'],
                'url' => route('frontend.products.show', $p['id']),
            ];
        });

        return response()->json($results);
    }

    public function show($id)
    {
        $product = FrontendData::getProductById($id);

        if (!$product) {
            abort(404, 'Product not found');
        }

        $allProducts = collect(FrontendData::products());
        $relatedProducts = $allProducts->where('category_slug', $product['category_slug'])
            ->where('id', '!=', $product['id']);

        if ($relatedProducts->count() < 6) {
            $fallback = $allProducts->where('id', '!=', $product['id'])
                ->reject(fn($p) => $relatedProducts->contains('id', $p['id']));
            $relatedProducts = $relatedProducts->concat($fallback);
        }

        $relatedProducts = $relatedProducts->take(8)->values();

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }

    public function categories()
    {
        $categories = FrontendData::categories();
        return view('frontend.categories.index', compact('categories'));
    }

    public function categoryShow($slug)
    {
        return redirect()->route('frontend.products.index', ['category' => $slug]);
    }

    public function brandShow($slug)
    {
        return redirect()->route('frontend.products.index', ['brand' => $slug]);
    }
}
