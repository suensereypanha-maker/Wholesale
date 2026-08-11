<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of customer orders.
     */
    public function index()
    {
        $orders = session('frontend_orders', []);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show($id)
    {
        $orders = session('frontend_orders', []);
        $order = collect($orders)->firstWhere('id', (int) $id);

        if (!$order) {
            abort(404, 'Order not found');
        }

        return view('admin.orders.show', compact('order'));
    }
}
