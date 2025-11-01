<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\ShippingRule;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Cart;
class CheckOutController extends Controller
{
    public function index()
    {
        $addresses = UserAddress::where('user_id', Auth::user()->id)->get();
        $shippingMethods = ShippingRule::where('status', 1)->get();
        $cartItems = Cart::content();
        return view('frontend.pages.checkout', compact('addresses', 'shippingMethods', 'cartItems'));
    }

    public function createAddress(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'pincode' => 'required|string|max:20',
            'sector' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'type' => 'required|in:home,office,other',
            'alt_phone' => 'nullable|string|max:20',
        ]);

        $address = new UserAddress();
        $address->user_id = Auth::user()->id;
        $address->name = $request->name;
        $address->email = $request->email;
        $address->phone = $request->phone;
        $address->address = $request->address;
        $address->pincode = $request->pincode;
        $address->sector = $request->sector;
        $address->landmark = $request->landmark;
        $address->city = $request->city;
        $address->country = $request->country;
        $address->type = $request->type;
        $address->alt_phone = $request->alt_phone;
        $address->save();

        // get updated addresses for current user
        $addresses = UserAddress::where('user_id', Auth::id())->get();

        // prepare updated HTML for select dropdown
        $html = view('frontend.partials.address_dropdown', compact('addresses'))->render();

        return response()->json([
            'status'  => 'success',
            'message' => 'Address added successfully!',
            'data'    => $html
        ]);
    }

    public function checkOutFormSubmit(Request $request)
    {
       $request->validate([
        'shipping_method_id' => ['required', 'integer'],
       ]);

       $shippingMethod = ShippingRule::findOrFail($request->shipping_method_id);
       if($shippingMethod){
           Session::put('shipping_method', [
                'id' => $shippingMethod->id,
                'name' => $shippingMethod->name,
                'type' => $shippingMethod->type,
                'cost' => $shippingMethod->cost
           ]);
       }

       $tempAddressArray = $request->address;
       $tempOccasionsArray = $request->occasion;
       $tempMessagesArray = $request->message;
       Session::put('address', $tempAddressArray);
       Session::put('occasion', $tempOccasionsArray);
       Session::put('message', $tempMessagesArray);

       return response(['status' => 'success', 'redirect_url' => route('user.payment')]);
    }

    public function checkPincode(Request $request)
    {
        $request->validate([
            'zip' => ['required', 'max:200']
        ]);

        $deliveryData = Location::all()->toArray();

        $zip = $request->zip;
        $deliveryInfo = collect($deliveryData)->firstWhere('pin', $zip);
        if ($deliveryInfo) {
            return response()->json([
                'status' => 'success',
                'message' => 'Delivery available',
                'data' => $deliveryInfo
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Delivery not available for this pincode'
            ]);
        }
    }
}
