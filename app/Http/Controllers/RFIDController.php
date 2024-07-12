<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class RFIDController extends Controller
{
     //pending RFID page
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
