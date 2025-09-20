<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariantItem;
use Illuminate\Http\Request;

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
                'image' => $a->thumb_image,
                'slug' => $product->slug,
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Added to cart successfully!']);
    }

    /** Get all cart items */
    public function getCart(Request $request)
    {
        dd($request->user());
        $items = CartItem::with('products')
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
}
