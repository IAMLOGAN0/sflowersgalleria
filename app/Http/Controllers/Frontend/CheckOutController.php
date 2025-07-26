<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ShippingRule;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckOutController extends Controller
{
    public function index()
    {
        $addresses = UserAddress::where('user_id', Auth::user()->id)->get();
        $shippingMethods = ShippingRule::where('status', 1)->get();
        return view('frontend.pages.checkout', compact('addresses', 'shippingMethods'));
    }

    public function createAddress(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:200'],
            'phone' => ['required', 'max:200'],
            'email' => ['required', 'email'],
            'country' => ['required', 'max: 200'],
            'state' => ['required', 'max: 200'],
            'city' => ['required', 'max: 200'],
            'zip' => ['required', 'max: 200'],
            'address' => ['required', 'max: 200']
        ]);

        $address = new UserAddress();
        $address->user_id = Auth::user()->id;
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->email = $request->email;
        $address->country = $request->country;
        $address->state = $request->state;
        $address->city = $request->city;
        $address->zip = $request->zip;
        $address->address = $request->address;
        $address->save();

        toastr('Address created successfully!', 'success', 'Success');

        return redirect()->back();

    }

    public function checkOutFormSubmit(Request $request)
    {
       $request->validate([
        'shipping_method_id' => ['required', 'integer'],
        'shipping_address_id' => ['required', 'integer'],
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
       $address = UserAddress::findOrFail($request->shipping_address_id)->toArray();
       if($address){
           Session::put('address', $address);
       }

       return response(['status' => 'success', 'redirect_url' => route('user.payment')]);
    }

    public function checkPincode(Request $request)
    {
        $request->validate([
            'zip' => ['required', 'max:200']
        ]);

        $deliveryData = [
            ['sector' => 'Sec-1',   'pin' => '122001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-2',   'pin' => '122017', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-3',   'pin' => '122006', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-4',   'pin' => '122001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-5',   'pin' => '129001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-6',   'pin' => '122022', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-7',   'pin' => '129001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-8',   'pin' => '122009', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-9',   'pin' => '122001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-10',  'pin' => '129001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-11',  'pin' => '122001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-12',  'pin' => '122029', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-13',  'pin' => '122029', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-14',  'pin' => '129001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-15',  'pin' => '129001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-16',  'pin' => '122022', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-17',  'pin' => '129001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-18',  'pin' => '122029', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-19',  'pin' => '122001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-20',  'pin' => '122001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-21',  'pin' => '122016', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-22',  'pin' => '122015', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-23',  'pin' => '122017', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-24',  'pin' => '122001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-25',  'pin' => '122022', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-26',  'pin' => '122022', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-27',  'pin' => '122002', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-28',  'pin' => '122001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-29',  'pin' => '122002', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-30',  'pin' => '122022', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-31',  'pin' => '122022', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-32',  'pin' => '122022', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-33',  'pin' => '122001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-34',  'pin' => '122004', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-35',  'pin' => '122004', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-36',  'pin' => '122001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-37',  'pin' => '122004', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-38',  'pin' => '122001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-39',  'pin' => '122001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-40',  'pin' => '122001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-41',  'pin' => '122022', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-42',  'pin' => '122002', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-43',  'pin' => '122002', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-44',  'pin' => '122003', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-45',  'pin' => '122003', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-46',  'pin' => '122018', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-47',  'pin' => '122018', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-48',  'pin' => '122001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-49',  'pin' => '122018', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-50',  'pin' => '122003', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-51',  'pin' => '122003', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-52',  'pin' => '122022', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-53',  'pin' => '122022', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-54',  'pin' => '122011', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-55',  'pin' => '122011', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-56',  'pin' => '122011', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-57',  'pin' => '122003', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-58',  'pin' => '122102', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-59',  'pin' => '122011', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-60',  'pin' => '122002', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-61',  'pin' => '122102', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-62',  'pin' => '122102', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-63',  'pin' => '122002', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-64',  'pin' => '122102', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-65',  'pin' => '122102', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-66',  'pin' => '122102', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-67',  'pin' => '122102', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-68',  'pin' => '122101', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-69',  'pin' => '122101', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-70',  'pin' => '122001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-71',  'pin' => '122004', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-72',  'pin' => '122004', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-73',  'pin' => '122004', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-74',  'pin' => '122004', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-75',  'pin' => '122004', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-76',  'pin' => '122004', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-77',  'pin' => '122001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-78',  'pin' => '122004', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-79',  'pin' => '122001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-80',  'pin' => '122001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-81',  'pin' => '122004', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-83',  'pin' => '122004', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-84',  'pin' => '122004', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-85',  'pin' => '122004', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-86',  'pin' => '122004', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-87',  'pin' => '122001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-88',  'pin' => '122004', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-89',  'pin' => '122004', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-90',  'pin' => '122505', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-91',  'pin' => '122505', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-92',  'pin' => '122505', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-93',  'pin' => '122505', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-94',  'pin' => '122505', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-95',  'pin' => '122505', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-96',  'pin' => '122505', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-97',  'pin' => '122505', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-98',  'pin' => '122505', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-99',  'pin' => '122001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-100', 'pin' => '122001', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-101', 'pin' => '122006', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-102', 'pin' => '122006', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-103', 'pin' => '122006', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-104', 'pin' => '122006', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-105', 'pin' => '122006', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-106', 'pin' => '122006', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-107', 'pin' => '122505', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-108', 'pin' => '122017', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
            ['sector' => 'Sec-109', 'pin' => '122017', 'b_time' => '2 hours', 't_time' => '9:00pm-10:00pm'],
        ];

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
