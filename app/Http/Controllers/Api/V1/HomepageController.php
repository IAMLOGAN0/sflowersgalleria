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
        $flashSaleDate = FlashSale::first();

        $flashSaleItems = FlashSaleItem::where('show_at_home', 1)
            ->where('status', 1)
            ->pluck('product_id')
            ->toArray();

        $flashSaleProducts = Product::whereIn('id', $flashSaleItems)
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->with(['variants', 'category', 'productImageGalleries'])
            ->get();

        $brands = Brand::where('status', 1)->where('is_featured', 1)->get();
        $typeBaseProducts = $this->getTypeBaseProduct(); // make sure this method is public if reused
        $homepage_secion_banner_one = json_decode(optional(Adverisement::where('key', 'homepage_secion_banner_one')->first())->value);
        $homepage_secion_banner_two = json_decode(optional(Adverisement::where('key', 'homepage_secion_banner_two')->first())->value);
        $homepage_secion_banner_three = json_decode(optional(Adverisement::where('key', 'homepage_secion_banner_three')->first())->value);
        $homepage_secion_banner_four = json_decode(optional(Adverisement::where('key', 'homepage_secion_banner_four')->first())->value);

        return response()->json([
            'sliders' => $sliders,
            'flashSaleDate' => $flashSaleDate,
            'flashSaleProducts' => $flashSaleProducts,
            'brands' => $brands,
            'typeBaseProducts' => $typeBaseProducts,
            'homepage_secion_banner_one' => $homepage_secion_banner_one,
            'homepage_secion_banner_two' => $homepage_secion_banner_two,
            'homepage_secion_banner_three' => $homepage_secion_banner_three,
            'homepage_secion_banner_four' => $homepage_secion_banner_four,
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
}
