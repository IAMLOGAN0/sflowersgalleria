<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Adverisement;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\HomePageSetting;
use App\Models\Location;
use App\Models\Product;
use App\Models\Slider;
use App\Models\SubCategory;
use App\Models\Vendor;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class HomepageController extends Controller
{
    public function getHomepageData(Request $request)
    {
        $sliders = Slider::where('status', 1)->orderBy('serial', 'asc')->get();
        $categories = Category::where('status', 1)->get();
        $typeBaseProducts = $this->getTypeBaseProduct();
        $events = Blog::with('category')->where('status', 1)->get()->groupBy('category.name');

        return response()->json([
            'sliders' => $sliders,
            'categories' => $categories,
            'typeBaseProducts' => $typeBaseProducts,
            'events' => $events
        ]);
    }

    public function getTypeBaseProduct()
    {
        $typeBaseProducts = [];

        $typeBaseProducts['new_arrival'] = Product::withAvg('reviews', 'rating')->withCount('reviews')
            ->with(['variants', 'category', 'productImageGalleries'])
            ->where(['product_type' => 'new_arrival', 'is_approved' => 1, 'status' => 1])->orderBy('id', 'DESC')->take(8)->get();

        $typeBaseProducts['featured_product'] = Product::withAvg('reviews', 'rating')->withCount('reviews')
            ->with(['variants', 'category', 'productImageGalleries'])
            ->where(['product_type' => 'featured_product', 'is_approved' => 1, 'status' => 1])->orderBy('id', 'DESC')->take(8)->get();

        $typeBaseProducts['top_product'] = Product::withAvg('reviews', 'rating')->withCount('reviews')
            ->with(['variants', 'category', 'productImageGalleries'])
            ->where(['product_type' => 'top_product', 'is_approved' => 1, 'status' => 1])->orderBy('id', 'DESC')->take(8)->get();

        $typeBaseProducts['best_product'] = Product::withAvg('reviews', 'rating')->withCount('reviews')
            ->with(['variants', 'category', 'productImageGalleries'])
            ->where(['product_type' => 'best_product', 'is_approved' => 1, 'status' => 1])->orderBy('id', 'DESC')->take(8)->get();

        return $typeBaseProducts;
    }

    public function getAllCategoriesWithSubcategories()
    {
        $categories = Category::where('status', 1)->get();

        $data = [];
        foreach ($categories as $category) {
            $subCategories = SubCategory::where('category_id', $category->id)->where('status', 1)->get();
            $data[] = [
                'category' => $category,
                'subCategories' => $subCategories
            ];
        }

        return response()->json($data);
    }

    public function getSubcategoriesByCategoryId($id)
    {
        $subCategories = SubCategory::where('category_id', $id)->where('status', 1)->get();

        return response()->json($subCategories);
    }

    public function getDeliveryLocations()
    {
        $deliveryLocations = Location::all();
        return response()->json($deliveryLocations);
    }
}
