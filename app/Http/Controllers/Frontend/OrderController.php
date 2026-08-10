<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Data\FrontendData;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = session()->get('frontend_orders', FrontendData::sampleOrders());
        return view('frontend.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $orders = session()->get('frontend_orders', FrontendData::sampleOrders());
        $order = null;

        foreach ($orders as $o) {
            if ($o['id'] === $id) {
                $order = $o;
                break;
            }
        }

        if (!$order) {
            abort(404, 'Order not found');
        }

        // Timeline status indexing helper
        $statuses = ['Pending', 'Confirmed', 'Processing', 'Packed', 'Shipped', 'Delivered', 'Completed'];
        $currentStatus = $order['status'];
        $currentStepIndex = array_search($currentStatus, $statuses);
        if ($currentStepIndex === false) {
            $currentStepIndex = 1; // default to Confirmed for custom orders
        }

        return view('frontend.orders.show', compact('order', 'statuses', 'currentStepIndex'));
    }
}
