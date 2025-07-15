<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getAllProducts(Request $request)
    {
        $perPage = $request->input('per_page', 12); // Default is 12 products per page

        $products = Product::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->with(['variants', 'category', 'productImageGalleries'])
            ->where(['status' => 1, 'is_approved' => 1])
            ->orderBy('id', 'DESC')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function getProductDetails($slug)
    {
        $product = Product::with([
            'category',
            'brand',
            'variants',
            'productImageGalleries',
            'reviews.user',
        ])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('slug', $slug)
            ->where(['status' => 1, 'is_approved' => 1])
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    public function searchProducts(Request $request)
    {
        $query = $request->input('q');

        $products = Product::with(['category', 'variants', 'productImageGalleries'])
            ->where('status', 1)
            ->where('is_approved', 1)
            ->where('name', 'like', '%' . $query . '%')
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
}
