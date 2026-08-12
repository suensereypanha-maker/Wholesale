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
            $search = strtolower($request->search);
            $query = $query->filter(function ($p) use ($search) {
                return str_contains(strtolower($p['name']), $search) ||
                       str_contains(strtolower($p['sku']), $search) ||
                       str_contains(strtolower($p['brand']), $search) ||
                       str_contains(strtolower($p['category']), $search);
            });
        }

        if ($request->filled('category')) {
            $query = $query->where('category_slug', strtolower($request->category));
        }

        if ($request->filled('brand')) {
            $query = $query->where('brand_slug', strtolower($request->brand));
        }

        if ($request->filled('min_price')) {
            $query = $query->where('price', '>=', (float)$request->min_price);
        }

        if ($request->filled('max_price')) {
            $query = $query->where('price', '<=', (float)$request->max_price);
        }

        if ($request->filled('max_moq')) {
            $query = $query->where('moq', '<=', (int)$request->max_moq);
        }

        if ($request->boolean('in_stock')) {
            $query = $query->where('stock', '>', 0);
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
        $page = $request->get('page', 1);
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
            return str_contains(strtolower($p['name']), $search) ||
                   str_contains(strtolower($p['sku']), $search) ||
                   str_contains(strtolower($p['brand']), $search) ||
                   str_contains(strtolower($p['category']), $search);
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
