<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariantItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
{
    /** Add item to cart */
    public function addToCart(Request $request)
    {
        $user = $request->user();

        $product = Product::findOrFail($request->product_id);

        if ($product->qty === 0) {
            return response()->json(['status' => 'error', 'message' => 'Product stock out'], 400);
        } elseif ($product->qty < $request->qty) {
            return response()->json(['status' => 'error', 'message' => 'Quantity not available in stock'], 400);
        }

        $variants = [];
        $variantTotalAmount = 0;

        if ($request->has('variants_items')) {
            foreach ($request->variants_items as $item_id) {
                $variantItem = ProductVariantItem::find($item_id);
                if ($variantItem) {
                    $variants[$variantItem->productVariant->name]['name'] = $variantItem->name;
                    $variants[$variantItem->productVariant->name]['price'] = $variantItem->price;
                    $variants[$variantItem->productVariant->name]['image'] = $variantItem->image;
                    $variantTotalAmount += $variantItem->price;
                }
            }
        }

        $productPrice = checkDiscount($product) ? $product->offer_price : $product->price;

        // Check if the product is already in cart
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->update([
                'qty' => $cartItem->qty + $request->qty,
                'price' => $productPrice,
                'variants' => $variants,
                'variants_total' => $variantTotalAmount,
                'image' => $product->thumb_image,
                'slug' => $product->slug,
            ]);
        } else {
            CartItem::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'qty' => $request->qty,
                'price' => $productPrice,
                'variants' => $variants,
                'variants_total' => $variantTotalAmount,
                'image' => $product->thumb_image,
                'slug' => $product->slug,
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Added to cart successfully!']);
    }

    /** Get all cart items */
    public function getCart(Request $request)
    {
        $items = CartItem::with('product', 'product.category', 'product.productImageGalleries')
        ->where('user_id', $request->user()->id)
        ->get();
        return response()->json(['status' => 'success', 'data' => $items]);
    }

    /** Update quantity */
    public function updateQuantity(Request $request, $id)
    {
        $cartItem = CartItem::where('user_id', $request->user()->id)->findOrFail($id);

        $product = Product::findOrFail($cartItem->product_id);

        if ($product->qty < $request->qty) {
            return response()->json(['status' => 'error', 'message' => 'Quantity not available'], 400);
        }

        $cartItem->update(['qty' => $request->qty]);

        return response()->json(['status' => 'success', 'message' => 'Quantity updated']);
    }

    /** Remove item */
    public function removeItem(Request $request, $id)
    {
        CartItem::where('user_id', $request->user()->id)->where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Item removed']);
    }

    /** Clear cart */
    public function clearCart(Request $request)
    {
        CartItem::where('user_id', $request->user()->id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Cart cleared']);
    }

public function couponCalculation(Request $request)
{
    try {
        $request->validate([
            'cupon_code' => 'required|string',
            'total' => 'required|numeric'
        ]);

        $user = auth()->user();
        $code = strtoupper($request->cupon_code);
        $total = floatval($request->total);

        $coupon = Coupon::where('code', $code)->where('status', 1)->first();

        if (!$coupon) {
            return response([
                'status' => 'error',
                'message' => 'Invalid coupon code'
            ]);
        }

        $today = date('Y-m-d');

        // 1️⃣ Check date validity
        if ($today < $coupon->start_date || $today > $coupon->end_date) {
            return response([
                'status' => 'error',
                'message' => 'Coupon expired or not active'
            ]);
        }

        // 2️⃣ Global usage limit
        if ($coupon->total_used >= $coupon->quantity) {
            return response([
                'status' => 'error',
                'message' => 'Coupon usage limit reached'
            ]);
        }

        // 3️⃣ Check user usage limit
        $usage = DB::table('coupon_users')
            ->where('coupon_id', $coupon->id)
            ->where('user_id', $user->id)
            ->first();

        if ($usage && $usage->times_used >= $coupon->max_use) {
            return response([
                'status' => 'error',
                'message' => 'You already used this coupon maximum allowed times'
            ]);
        }

        // 4️⃣ Dynamic Minimum Order Amount
        if ($coupon->min_order_amount > 0 && $total < $coupon->min_order_amount) {
            return response([
                'status' => 'error',
                'message' => "Minimum order amount ₹{$coupon->min_order_amount} required"
            ]);
        }

        // 5️⃣ Apply discount
        if ($coupon->discount_type === 'amount') {
            $discountAmount = $coupon->discount;
        } else {
            $discountAmount = ($total * $coupon->discount) / 100;
        }

        $finalTotal = max($total - $discountAmount, 0);

        return response([
            'status' => 'success',
            'message' => 'Coupon Applied Successfully',
            'discount_amount' => round($discountAmount),
            'final_total' => round($finalTotal)
        ]);

    } catch (\Exception $e) {
        return response([
            'status' => 'error',
            'message' => 'Something went wrong',
            'error' => $e->getMessage()
        ], 500);
    }
}


    // public function allCoupon(Request $request){
    //     $coupons = Coupon::where(['status' => 1])->get();
    //     return response(['status' => 'success', 'data' => $coupons]);
    // }
    
    
    public function allCoupon(Request $request)
{
    $user = auth()->user();

    $today = date('Y-m-d');

    // Get all active coupons where:
    // - status = 1
    // - start_date <= today
    // - end_date >= today
    // - total_used < quantity (still available)
    $coupons = Coupon::where('status', 1)
        ->where('start_date', '<=', $today)
        ->where('end_date', '>=', $today)
        ->whereColumn('total_used', '<', 'quantity')
        ->get();

    // Filter out coupons user already used max times
    $filtered = $coupons->filter(function ($coupon) use ($user) {
        $userUsed = DB::table('coupon_users')
            ->where('coupon_id', $coupon->id)
            ->where('user_id', $user->id)
            ->count();

        return $userUsed < $coupon->max_use;
    });

    return response([
        'status' => 'success',
        'data' => array_values($filtered->toArray()),
    ]);
}

}
