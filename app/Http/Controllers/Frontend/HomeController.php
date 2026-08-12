<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Data\FrontendData;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = collect(FrontendData::products());
        $categories = FrontendData::categories();
        $brands = FrontendData::brands();

        $allProducts = $products->values();
        $featuredProducts = $products->where('featured', true)->values();
        $bestSellers = $products->where('best_seller', true)->take(8)->values();
        $newArrivals = $products->where('new_arrival', true)->take(8)->values();

        return view('frontend.home.index', compact(
            'categories',
            'brands',
            'allProducts',
            'featuredProducts',
            'bestSellers',
            'newArrivals'
        ));
    }
}
