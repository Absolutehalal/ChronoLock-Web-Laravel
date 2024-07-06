<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RfidController extends Controller
{
    public function pendingRFID()
    {
        return view('admin.admin-pendingRFID');
    }

    public function RFIDManagement()
    {
        return view('admin.admin-RFIDAccount');
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('query');
        $results = User::where('accountName', 'LIKE', "%{$query}%")->get();

        return response()->json($results);
    }
}
