<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DeliveryBoyController extends Controller
{
    public function changeOrderStatus(Request $request)
    {
        $order = Order::findOrFail($request->id);
        $order->order_status = $request->status;
        $order->save();

        return response(['status' => 'success', 'message' => 'Updated Order Status']);
    }

    public function getOrders(Request $request)
    {
        $orders = Order::where('delivery_boy_id', $request->id)->get();

        return response(['status' => 'success', 'data' => $orders]);
    }
}
