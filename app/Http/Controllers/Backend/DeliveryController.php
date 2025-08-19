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
            'sector'   => 'required|string|max:255',
            'pin'      => 'required|string|max:20',
            'b_time'   => 'nullable|string|max:255',
            't_time'   => 'nullable|string|max:255',
        ]);

        Location::create([
            'sector' => $request->sector,
            'pin'    => $request->pin,
            'b_time' => $request->b_time,
            't_time' => $request->t_time,
        ]);

        return redirect()->route('admin.locations')->with('success', 'Delivery location created successfully!');
    }

    public function editDeliveryLocation($id)
    {
        $location = Location::findOrFail($id);
        return view('admin.delivery.location-edit', compact('location'));
    }

    public function updateDeliveryLocation(Request $request, $id)
    {
        $request->validate([
            'sector' => 'required|string|max:255',
            'pin'    => 'required|string|max:20',
            'b_time' => 'nullable|string|max:100',
            't_time' => 'nullable|string|max:100',
        ]);

        $location = Location::findOrFail($id);
        $location->update($request->only('sector', 'pin', 'b_time', 't_time'));

        return redirect()->route('admin.locations')
            ->with('success', 'Delivery Location updated successfully.');
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


}
