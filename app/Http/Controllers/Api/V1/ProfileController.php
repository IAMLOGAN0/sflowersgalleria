<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    use ImageUploadTrait;
    /**
     * Get logged-in user profile
     */
    public function getProfile(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    }

    /**
     * Update profile details (name, email, phone, etc.)
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:51200',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->messages()
            ], 422);
        }

        if($request->hasFile('image')){
            if(File::exists(public_path($user->image))){
                File::delete(public_path($user->image));
            }

            $image = $request->image;
            $imageName = rand().'_'.$image->getClientOriginalName();
            $image->move(public_path('uploads'), $imageName);

            $path = 'uploads/'.$imageName;

            $user->image = $path;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    public function getAddresses(Request $request)
    {
        $addresses = UserAddress::where('user_id', $request->user()->id)->get();

        return response()->json([
            'success' => true,
            'addresses' => $addresses
        ]);
    }

    public function getAddress(Request $request, $id)
    {
        $address = UserAddress::where('id', $id)->where('user_id', $request->user()->id)->first();

        return response()->json([
            'success' => true,
            'address' => $address
        ]);
    }

    public function addAddress(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'pincode' => 'required|string|max:20',
            'sector' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'type' => 'required',
            'alt_phone' => 'nullable|string|max:20',
            ]);

            if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->messages()
            ], 422);
            }

            $address = UserAddress::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'pincode' => $request->pincode,
            'sector' => $request->sector,
            'landmark' => $request->landmark,
            'city' => $request->city,
            'country' => $request->country,
            'type' => $request->type,
            'alt_phone' => $request->alt_phone,
            ]);

            return response()->json([
            'success' => true,
            'message' => 'Address added successfully',
            'address' => $address
            ]);
        } catch (\Exception $e) {
            return response()->json([
            'success' => false,
            'message' => 'Failed to add address',
            'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateAddress(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->messages()
            ], 422);
        }

        $address = UserAddress::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        $address->update($request->only([
            'name', 'email', 'phone', 'alt_phone', 'address', 'pincode', 'sector', 'landmark', 'city', 'country', 'type'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Address updated successfully',
            'address' => $address
        ]);
    }

    public function deleteAddress(Request $request, $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:user_addresses,id,user_id,'.$request->user()->id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->messages()
            ], 422);
        }

        UserAddress::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Address deleted successfully'
        ]);
    }


}


