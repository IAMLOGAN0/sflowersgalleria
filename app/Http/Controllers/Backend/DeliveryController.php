<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\LocationDataTable;
use App\Models\Location;
use App\Models\Order;
use App\Models\User;

class DeliveryController extends Controller
{
    public function allDeliveryLocation(LocationDataTable $dataTable){

        return $dataTable->render('admin.delivery.location');
    }


    public function createDeliveryLocation(){
        return view('admin.delivery.location-create');
    }

    public function storeDeliveryLocation(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|max:20',
            'sectors' => 'required|array',
            'sectors.*.name' => 'required|string|max:255',
            'sectors.*.delivery_time' => 'nullable|string|max:255',
            'sectors.*.slots' => 'nullable|array',
            'sectors.*.slots.*' => 'nullable|string|max:255',
        ]);

        $pin = $request->pin;

        foreach ($request->sectors as $sectorData) {
            Location::create([
                'pin' => $pin,
                'sector' => $sectorData['name'],
                'b_time' => $sectorData['delivery_time'] ?? null,
                't_time' => isset($sectorData['slots']) ? json_encode($sectorData['slots']) : null,
            ]);
        }

        return redirect()->route('admin.locations')
            ->with('success', 'Delivery locations created successfully!');
    }



    public function editDeliveryLocation($id)
    {

        $locationData = Location::where('pin', $id)->get();
        if($locationData->count() == 0){
            return redirect()->route('admin.locations')
            ->with('error', 'Delivery location not found!');
        }

        $location = (object) [
            'id' => $locationData[0]?->id ?? null,
            'pin' => $locationData[0]?->pin,
            'sector' => $locationData->toArray() ?? [],
        ];

        // dd($location);
        return view('admin.delivery.location-edit', compact('location'));
    }

    public function updateDeliveryLocation(Request $request, $id)
    {
        $request->validate([
            'pin' => 'required|string|max:20',
            'sectors' => 'required|array',
            'sectors.*.name' => 'required|string|max:255',
            'sectors.*.delivery_time' => 'nullable|string|max:255',
            'sectors.*.slots' => 'nullable|array',
            'sectors.*.slots.*' => 'nullable|string|max:255',
        ]);

        $location = Location::findOrFail($id);

        // Delete existing sectors for this pincode (if stored as separate records)
        // If using a normalized table with pincode_id, adjust accordingly
        Location::where('pin', $location->pin)->delete();

        // Insert each sector as a separate record
        foreach ($request->sectors as $sectorData) {
            Location::create([
                'pin' => $request->pin,
                'sector' => $sectorData['name'],
                'b_time' => $sectorData['delivery_time'] ?? null,
                't_time' => isset($sectorData['slots']) ? json_encode($sectorData['slots']) : null,
            ]);
        }

        return redirect()->route('admin.locations')
            ->with('success', 'Delivery locations updated successfully.');
    }



    /**
     * Delete location.
     */
    public function deleteDeliveryLocation($id)
    {
        $location = Location::findOrFail($id);
        $location->delete();

        return response()->json(['status' => 'success', 'message' => 'Location deleted successfully.']);
    }

    public function assignOrdersToDeliveryBoy(){

        $orders  = Order::with('user')->where('delivery_boy_id', null)->get();
        $delivery_boys = User::where('role', 'delivery-boy')->get();

        return view('admin.delivery.assign-delivery-boy', compact('orders', 'delivery_boys'));
    }

    public function storeAssignedOrders(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'delivery_boy_id' => 'required|exists:users,id'
        ]);

        Order::whereIn('id', $request->order_ids)
            ->update(['delivery_boy_id' => $request->delivery_boy_id]);

        return response()->json(['status' => 'success', 'message' => 'Orders assigned successfully!']);
    }

    public function getSectorsByPincode(Request $request)
    {
        $pincode = $request->input('pincode');
        $sectors = Location::where('pin', $pincode)->get();
        return response()->json(['sectors' => $sectors]);
    }


}
