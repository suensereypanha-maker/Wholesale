<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CacheController extends Controller
{
    /**
     * Clear all application, route, view, and config caches in Laravel.
     */
    public function clear(Request $request)
    {
        try {
            // Run Laravel cache clearing artisan commands
            Artisan::call('optimize:clear');
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            return redirect()->back()->with('success', '⚡ System & Laravel cache cleared successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }
}
