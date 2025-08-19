<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\DeliveryBoyListDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DeliveryBoyListController extends Controller
{
    public function index(DeliveryBoyListDataTable $dataTable)
    {
        return $dataTable->render('admin.customer-list.index');
    }

    public function changeStatus(Request $request)
    {
        $customer = User::findOrFail($request->id);
        $customer->status = $request->status == 'true' ? 'active' : 'inactive';
        $customer->save();

        return response(['message' => 'Status has been updated!']);
    }
}
