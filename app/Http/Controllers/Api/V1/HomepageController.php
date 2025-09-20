<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Adverisement;
use App\Models\Blog;
use App\Models\BlogCategory;
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
use Illuminate\Support\Facades\Artisan;
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
        $event_category = BlogCategory::where('status', 1)->get();
        return response()->json([
            'sliders' => $sliders,
            'categories' => $categories,
            'typeBaseProducts' => $typeBaseProducts,
            'events' => $events,
            'event_categories' => $event_category
        ]);
    }

    public function getTypeBaseProduct()
    {
        // Base query builder with common relations and conditions
        $baseQuery = Product::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->with(['variants', 'category', 'productImageGalleries'])
            ->where(['is_approved' => 1, 'status' => 1]);

        // Define product types mapped to query conditions
        $configs = [
            'new_arrival' => fn ($q) => $q->where('product_type', 'new_arrival'),
            'featured_product' => fn ($q) => $q->where('product_type', 'featured_product'),
            'top_product' => fn ($q) => $q->where('product_type', 'top_product'),
            'best_product' => fn ($q) => $q->where('product_type', 'best_product'),
            'plants' => fn ($q) => $q->whereHas('category', fn ($cat) => $cat->where('slug', 'plants')),
            'event_corporate_gifts' => fn ($q) => $q->whereHas('category', fn ($cat) => $cat->where('slug', 'event-corporate-gifts')),
            'luxury' => fn ($q) => $q->whereHas('category', fn ($cat) => $cat->where('slug', 'luxury')),
            'cakes' => fn ($q) => $q->whereHas('category', fn ($cat) => $cat->where('slug', 'cakes')),
        ];

        // Run queries dynamically
        $typeBaseProducts = [];
        foreach ($configs as $key => $callback) {
            $query = (clone $baseQuery); // clone so builder isn't mutated
            $callback($query);
            $typeBaseProducts[$key] = $query->orderBy('id', 'DESC')->take(8)->get();
        }

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

        $deliveryLocations = Location::all()
            ->groupBy('pin') // group by Pincode
            ->map(function ($locations, $pin) {
                return [
                    'pin' => $pin,
                    'sectors' => $locations->map(function ($location) {
                        return [
                            'id' => $location->id,
                            'sector' => trim($location->sector),
                            'delivery_taken_time' => trim($location->b_time),
                            'time_slots' => json_decode(trim($location->t_time), true) ?? [],
                        ];
                    })->values(),
                ];
            })->values(); // reset keys

        return response()->json($deliveryLocations);
    }

}
