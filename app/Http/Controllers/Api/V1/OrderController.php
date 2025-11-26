<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\ShippingRule;
use App\Models\GeneralSetting;
use App\Models\UserAddress;
use App\Models\Coupon;
use App\Models\CartItem;
use App\Models\RazorpaySetting;
use Exception;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
class OrderController extends Controller
{
    /**
     * Store order after payment success (app handled)
     */
    public function storeOrder(Request $request)
    {
        $request->validate([
            'shipping_method_id' => ['required', 'integer'],
            'coupon' => ['nullable', 'string'],
            'order_data' => ['nullable', 'string'],
            'payment_method' => 'required|string',
            'payment_status' => 'required|boolean',
            'transaction_id' => 'required|string',
            'paid_amount' => 'required|numeric',
            'paid_currency' => 'required|string',
        ]);

        $user_id = $request->user()->id;

        /** ───── Shipping Method ───── */
        $shippingMethodModel = ShippingRule::findOrFail($request->shipping_method_id);
        $shippingMethod = [
            'id' => $shippingMethodModel->id,
            'name' => $shippingMethodModel->name,
            'type' => $shippingMethodModel->type,
            'cost' => $shippingMethodModel->cost
        ];

        /** ───── Coupon Handling ───── */
        $couponModel = Coupon::where('code', $request->coupon)->first();
        if ($couponModel) {
            if ($couponModel->total_used >= $couponModel->quantity) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Coupon usage limit exceeded'
                ], 400);
            }

            $coupon = [
                'coupon_name' => $couponModel->coupon_name,
                'coupon_code' => $couponModel->coupon_code,
                'discount_type' => $couponModel->discount_type,
                'discount' => $couponModel->discount
            ];
        } else {
            $coupon = null;
        }

        /** ───── Cart Items ───── */
        $cart_items = CartItem::with('product')
            ->where('user_id', $user_id)
            ->get();

        if ($cart_items->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart is empty'
            ], 400);
        }

        $order_data = $request->order_data ? json_decode($request->order_data, true) : [];
        $setting = GeneralSetting::first();

        /** ───── Order Creation ───── */
        $order = new Order();
        $order->invocie_id = rand(100000, 999999);
        $order->user_id = $user_id;
        $order->sub_total = $this->getCartTotal($user_id);
        $order->amount = $this->getFinalPayableAmount($user_id, $couponModel, $shippingMethodModel);
        $order->currency_name = $setting->currency_name;
        $order->currency_icon = $setting->currency_icon;
        $order->product_qty = $cart_items->sum('qty');
        $order->payment_method = $request->payment_method;
        $order->payment_status = $request->payment_status;
        $order->shpping_method = json_encode($shippingMethod);
        $order->coupon = $coupon ? json_encode($coupon) : null;
        $order->order_status = 'pending';
        $order->save();

        /** ───── Save Order Products ───── */
        foreach ($cart_items as $key => $item) {
            $data = $order_data[$key] ?? [];

            $address = isset($data['address_id'])
                ? UserAddress::find($data['address_id'])?->toArray()
                : null;

            $product = Product::find($item->product_id);
            if (! $product) {
                continue;
            }

            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $product->id;
            $orderProduct->vendor_id = $product->vendor_id;
            $orderProduct->product_name = $product->name;
            $orderProduct->variants = json_encode($item->variants);
            $orderProduct->variant_total = $item->variants_total;
            $orderProduct->unit_price = $item->price;
            $orderProduct->qty = $item->qty;
            $orderProduct->delivery_address = json_encode($address);
            $orderProduct->delivery_date = $data['order_date'] ?? null;
            $orderProduct->delivery_pincode = $data['order_pincode'] ?? null;
            $orderProduct->delivery_sector = $data['order_sector'] ?? null;
            $orderProduct->delivery_slot = $data['order_slot'] ?? null;
            $orderProduct->occation = $data['occation'] ?? '';
            $orderProduct->message = $data['message'] ?? '';
            $orderProduct->save();

            // Update stock
            $product->decrement('qty', $item->qty);
        }

        /** ───── Transaction ───── */
        $transaction = new Transaction();
        $transaction->order_id = $order->id;
        $transaction->transaction_id = $request->transaction_id;
        $transaction->payment_method = $request->payment_method;
        $transaction->amount = $this->getFinalPayableAmount($user_id, $couponModel, $shippingMethodModel);
        $transaction->amount_real_currency = $request->paid_amount;
        $transaction->amount_real_currency_name = $request->paid_currency;
        $transaction->save();

        /** ───── Coupon Update ───── */
        if ($couponModel) {
            $couponModel->increment('total_used');
        }

        /** ───── Clear Cart ───── */
        $cart_items->each->delete();

        /** ───── Response ───── */
        return response()->json([
            'status' => 'success',
            'message' => 'Order created successfully',
            'data' => [
                'order_id' => $order->id,
                'invoice' => $order->invocie_id,
                'total_amount' => $order->amount,
                'currency' => $order->currency_name,
                'product_count' => $order->product_qty
            ]
        ]);
    }

    public function createTempOrder(Request $request)
    {
        try {
            $request->validate([
                'shipping_method_id' => ['required', 'integer'],
                'coupon' => ['nullable', 'string'],
                'payment_method' => 'required|string',
            ]);

            $user_id = $request->user()->id;

            $shippingMethodModel = ShippingRule::findOrFail($request->shipping_method_id);
            $couponModel = Coupon::where('code', $request->coupon)->first();
            $cart_items = CartItem::with('product')->where('user_id', $user_id)->get();

            if ($cart_items->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cart is empty'
                ], 400);
            }

            $setting = GeneralSetting::first();

            // Create Temporary Order
            $order = new Order();
            $order->invocie_id = rand(100000, 999999);
            $order->user_id = $user_id;
            $order->sub_total = $this->getCartTotal($user_id);
            $order->amount = $this->getFinalPayableAmount($user_id, $couponModel, $shippingMethodModel);
            $order->currency_name = $setting->currency_name;
            $order->currency_icon = $setting->currency_icon;
            $order->product_qty = $cart_items->sum('qty');
            $order->payment_method = $request->payment_method;
            $order->payment_status = 'pending';
            $order->order_status = 'pending_payment';
            $order->save();

            // Create Razorpay Order
            $razorPaySetting = RazorpaySetting::first();
            $api = new Api($razorPaySetting->razorpay_key, $razorPaySetting->razorpay_secret_key);

            $razorOrderData = [
                'receipt' => "RECPT-" . $order->id,
                'amount' => $order->amount * 100, // Amount in paise
                'currency' => $order->currency_name,
                'notes' => [
                    'order_id' => $order->id,
                    'invoice_id' => $order->invocie_id
                ]
            ];

            $razorpayOrder = $api->order->create($razorOrderData);

            // Save razorpay_order_id in database if you want
            $order->razorpay_order_id = $razorpayOrder['id'];
            $order->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Temporary order created',
                'data' => [
                    'order_id' => $order->id,
                    'invoice_id' => $order->invocie_id,
                    'razorpay_order_id' => $razorpayOrder['id'],   // REQUIRED BY APP
                    'amount' => $order->amount,
                    'currency' => $order->currency_name,
                ]
            ]);

        }catch (Exception $e) {
            Log::error('confirmOrder error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to confirm order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function confirmOrder(Request $request)
    {
        try {
            $request->validate([
                'order_id'       => 'required|integer',
                'order_status'    => 'required|string',
                'transaction_id' => 'required|string',
                'paid_amount'    => 'required|numeric',
                'paid_currency'  => 'required|string',
                'order_data'     => 'nullable|string',
                'payment_status' => 'nullable|string',
            ]);

            $order = Order::findOrFail($request->order_id);

            if ($order->payment_status == 'completed') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order already paid'
                ], 400);
            }

            /** --- Decode order_data sent again from app --- */
            $order_data = $request->order_data
                ? json_decode($request->order_data, true)
                : [];

            /** --- Get cart items again to convert them into order products --- */
            $cart_items = CartItem::with('product')
                ->where('user_id', $order->user_id)
                ->get();

            foreach ($cart_items as $key => $item) {

                $data = $order_data[$key] ?? [];

                /** --- Address (optional) --- */
                $address = isset($data['address_id'])
                    ? UserAddress::find($data['address_id'])?->toArray()
                    : null;

                $product = Product::find($item->product_id);
                if (! $product) continue;

                /** --- Save product into order_products exactly as before --- */
                $orderProduct = new OrderProduct();
                $orderProduct->order_id = $order->id;
                $orderProduct->product_id = $product->id;
                $orderProduct->vendor_id = $product->vendor_id;
                $orderProduct->product_name = $product->name;
                $orderProduct->variants = json_encode($item->variants);
                $orderProduct->variant_total = $item->variants_total;
                $orderProduct->unit_price = $item->price;
                $orderProduct->qty = $item->qty;

                // FULL DELIVERY DETAILS RESTORED
                $orderProduct->delivery_address = json_encode($address);
                $orderProduct->delivery_date = $data['order_date'] ?? null;
                $orderProduct->delivery_pincode = $data['order_pincode'] ?? null;
                $orderProduct->delivery_sector = $data['order_sector'] ?? null;
                $orderProduct->delivery_slot = $data['order_slot'] ?? null;
                $orderProduct->occation = $data['occation'] ?? '';
                $orderProduct->message = $data['message'] ?? '';

                $orderProduct->save();

                /** --- Stock update (same as your original code) --- */
                $product->decrement('qty', $item->qty);
            }

            /** --- Update order as paid --- */
            $order->payment_status = $request->payment_status;
            $order->order_status = 'pending';
            $order->save();

            /** --- Save transaction --- */
            $transaction = new Transaction();
            $transaction->order_id = $order->id;
            $transaction->transaction_id = $request->transaction_id;
            $transaction->payment_method = $order->payment_method;
            $transaction->amount = $order->amount;
            $transaction->amount_real_currency = $request->paid_amount;
            $transaction->amount_real_currency_name = $request->paid_currency;
            $transaction->save();

            /** --- Clear cart (same as before) --- */
            $cart_items->each->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Order confirmed successfully',
                'data' => [
                    'order_id' => $order->id,
                    'invoice' => $order->invocie_id,
                    'total_amount' => $order->amount
                ]
            ]);
        } catch (Exception $e) {
            Log::error('confirmOrder error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to confirm order',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Get all orders for logged-in user
     */
    public function orderList(Request $request)
    {
        $orders = Order::with('orderProducts.product')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'orders' => $orders
        ]);
    }

    /**
     * Get single order detail
     */
    public function orderDetail(Request $request,$id)
    {
        $order = Order::with('orderProducts.product')
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'order' => $order
        ]);
    }

    /** ───── Helpers ───── */
    private function getCartTotal($user_id)
    {
        $cartItems = CartItem::where('user_id', $user_id)->get();
        $total = 0;

        foreach ($cartItems as $item) {
            $total += ($item->price + $item->variants_total) * $item->qty;
        }

        return $total;
    }

    private function getMainCartTotal($user_id, $coupon)
    {
        $subTotal = $this->getCartTotal($user_id);

        if ($coupon) {
            if ($coupon->discount_type === 'amount') {
                return max(0, $subTotal - $coupon->discount);
            } elseif ($coupon->discount_type === 'percent') {
                $discount = ($subTotal * $coupon->discount / 100);
                return max(0, $subTotal - $discount);
            }
        }

        return $subTotal;
    }

    private function getFinalPayableAmount($user_id, $coupon, $shippingMethod)
    {
        return $this->getMainCartTotal($user_id, $coupon) + ($shippingMethod->cost ?? 0);
    }
}
