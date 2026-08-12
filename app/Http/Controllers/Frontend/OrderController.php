<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Data\FrontendData;
use App\Models\Order;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user) {
            $dbOrders = Order::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('customer', function ($cq) use ($user) {
                      $cq->where('email', $user->email);
                  });
            })->with('customer', 'items.stock')->latest('order_date')->get();
        } else {
            $dbOrders = Order::with('customer', 'items.stock')->latest('order_date')->take(10)->get();
        }

        if ($dbOrders->isNotEmpty()) {
            $orders = $dbOrders;
        } else {
            $orders = session()->get('frontend_orders', FrontendData::sampleOrders());
        }

        return view('frontend.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = null;

        // Search DB by ID or order_number
        $dbOrder = Order::with('customer', 'items.stock')
            ->where('id', $id)
            ->orWhere('order_number', $id)
            ->first();

        if ($dbOrder) {
            $order = $dbOrder;
            $currentStatus = ucfirst($dbOrder->status);
        } else {
            // Session fallback
            $sessionOrders = session()->get('frontend_orders', FrontendData::sampleOrders());
            foreach ($sessionOrders as $o) {
                if (($o['id'] ?? '') === $id || ($o['db_id'] ?? '') == $id) {
                    $order = $o;
                    $currentStatus = $o['status'] ?? 'Pending';
                    break;
                }
            }
        }

        if (!$order) {
            abort(404, 'Order not found');
        }

        // Timeline status indexing helper
        $statusKey = strtolower($currentStatus);
        $stepMap = [
            'pending' => 0,
            'confirmed' => 0,
            'processing' => 1,
            'packed' => 1,
            'shipped' => 2,
            'in_transit' => 2,
            'delivered' => 3,
            'completed' => 3,
        ];
        $currentStepIndex = $stepMap[$statusKey] ?? 0;

        return view('frontend.orders.show', compact('order', 'currentStatus', 'currentStepIndex'));
    }

    public function cancel($id)
    {
        $dbOrder = Order::with('items')->where('id', $id)->orWhere('order_number', $id)->first();

        if ($dbOrder) {
            if ($dbOrder->status !== 'pending') {
                return back()->with('error', 'Only pending orders can be cancelled.');
            }

            $dbOrder->update(['status' => 'cancelled']);

            // Restore stock quantities if applicable
            foreach ($dbOrder->items as $item) {
                if ($item->stock_id) {
                    Stock::where('id', $item->stock_id)->increment('quantity', $item->quantity);
                }
            }

            return back()->with('success', "Order #{$dbOrder->order_number} has been cancelled successfully.");
        }

        // Session fallback cancel
        $sessionOrders = session()->get('frontend_orders', []);
        $updated = false;
        foreach ($sessionOrders as &$o) {
            if (($o['id'] ?? '') === $id || ($o['db_id'] ?? '') == $id) {
                $o['status'] = 'Cancelled';
                $updated = true;
                break;
            }
        }
        if ($updated) {
            session()->put('frontend_orders', $sessionOrders);
            return back()->with('success', "Order cancellation request received.");
        }

        return back()->with('error', 'Order not found or cannot be cancelled.');
    }
}

