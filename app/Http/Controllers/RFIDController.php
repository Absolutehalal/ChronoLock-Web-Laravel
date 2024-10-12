<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Rfid_temp;
use App\Models\RfidAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
class RFIDController extends Controller
{
    // START pending RFID page
    public function pendingRFID()
    {
        $pendingRFID = DB::table('rfid_temps')
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.admin-pendingRFID', ['pendingRFID' => $pendingRFID]);
    }
    public function processPendingRFID($id)
    {
        $pendingRFID = Rfid_temp::find($id);
        if ($pendingRFID) {
            return response()->json([
                'status' => 200,
                'pendingRFID' => $pendingRFID,
            ]);
        } else {
            return response()->json([
                'status' => 404,
            ]);
        }
    }
    public function activatePendingRFID(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'Rfid_Code' => 'required',
                'userId' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->messages()
                ]);
            } else {
                // $tempRFIDCode=$request->get('Rfid_Code');
                $code = $request->get('Rfid_Code');
                $id = $request->get('userId');
                $user = User::where('idNumber', $id)->first();
                $tempRFIDCode = Rfid_temp::where('RFID_Code', $code)->first();
                // $user = User::find()->where('idNumber',  $id);
                $updatedID = DB::table('users')->where('idNumber', $id)->value('id');
                $idNumber = DB::table('users')->where('id', $updatedID)->value('idNumber');
                $firstName = DB::table('users')->where('id', $updatedID)->value('firstName');
                $lastName = DB::table('users')->where('id', $updatedID)->value('lastName');
                $RFID_Code = DB::table('users')->where('idNumber', $id)->value('RFID_Code');
                if ($user && $RFID_Code == Null) {
                    $user->RFID_Code = $request->input('Rfid_Code');
                    $user->update();
                    $RFID_Account = new RfidAccount;
                    $RFID_Account->RFID_Code = $request->input('Rfid_Code');
                    $RFID_Account->RFID_Status = 'Activated';
                    $RFID_Account->save();
                    $tempRFIDCode->delete();
                    // Start Logs
                    $id = Auth::id();
                    $userID = DB::table('users')->where('id', $id)->value('idNumber');
                    date_default_timezone_set("Asia/Manila");
                    $date = date("Y-m-d");
                    $time = date("H:i:s");
                    $action = "Activated $idNumber-$firstName $lastName RFID";
                    DB::table('user_logs')->insert([
                        'userID' => $userID,
                        'action' => $action,
                        'date' => $date,
                        'time' => $time,
                    ]);
                    // END Logs
                    return response()->json([
                        'status' => 200,
                    ]);
                } else if ($RFID_Code != "") {
                    return response()->json([
                        'status' => 500,
                    ]);
                }
            }
        } catch (\Exception $e) {
            Alert::error('Error', 'Something went wrong. Please try again later.')
                ->autoClose(5000)
                ->showCloseButton();
            return redirect()->back();
        }
    }
    public function deletePendingRFID($id)
    {
        try {

            $pendingRFID = Rfid_temp::find($id);

            if ($pendingRFID) {
                $pendingRFID->delete();
                // Start Logs
                $ID = Auth::id();
                $userID = DB::table('users')->where('id', $ID)->value('idNumber');
                date_default_timezone_set("Asia/Manila");
                $date = date("Y-m-d");
                $time = date("H:i:s");
                $action = "Deleted pending RFID";
                DB::table('user_logs')->insert([
                    'userID' => $userID,
                    'action' => $action,
                    'date' => $date,
                    'time' => $time,
                ]);
                // END Logs
                return response()->json([
                    'status' => 200,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                ]);
            }
        } catch (\Exception $e) {
            Alert::error('Error', 'Something went wrong. Please try again later.')
                ->autoClose(5000)
                ->showCloseButton();
            return redirect()->back();
        }
    }
    // End pending RFID page
    // Start RFID Account Management
    public function RFIDManagement()
    {
        $RFID_Accounts = DB::table('rfid_accounts')
            ->join('users', 'rfid_accounts.RFID_Code', '=', 'users.RFID_Code')
            // ->where('userType', '!=', 'Admin')
            ->select('users.idNumber', 'users.firstName', 'users.lastName', 'rfid_accounts.RFID_Code', 'users.userType', 'rfid_accounts.RFID_Status', 'rfid_accounts.id')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.admin-RFIDAccount', ['RFID_Accounts' => $RFID_Accounts]);
    }
    public function deactivateRFID($id)
    {
        try {
            $RFID_Account = RfidAccount::find($id);
            if ($RFID_Account) {
                $RFID_Account->RFID_Status = 'Deactivated';
                $RFID_Account->update();
                // Start Logs
                $ID = Auth::id();
                $userID = DB::table('users')->where('id', $ID)->value('idNumber');
                date_default_timezone_set("Asia/Manila");
                $date = date("Y-m-d");
                $time = date("H:i:s");
                $action = "Deactivated an RFID Account";
                DB::table('user_logs')->insert([
                    'userID' => $userID,
                    'action' => $action,
                    'date' => $date,
                    'time' => $time,
                ]);
                // END Logs
                return response()->json([
                    'status' => 200,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                ]);
            }
        } catch (\Exception $e) {
            Alert::error('Error', 'Something went wrong. Please try again later.')
                ->autoClose(5000)
                ->showCloseButton();
            return redirect()->back();
        }
    }
    public function activateRFID($id)
    {
        try {
            $RFID_Account = RfidAccount::find($id);
            if ($RFID_Account) {
                $RFID_Account->RFID_Status = 'Activated';
                $RFID_Account->update();
                // Start Logs
                $ID = Auth::id();
                $userID = DB::table('users')->where('id', $ID)->value('idNumber');
                date_default_timezone_set("Asia/Manila");
                $date = date("Y-m-d");
                $time = date("H:i:s");
                $action = "Activated an RFID Account";
                DB::table('user_logs')->insert([
                    'userID' => $userID,
                    'action' => $action,
                    'date' => $date,
                    'time' => $time,
                ]);
                // END Logs
                return response()->json([
                    'status' => 200,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                ]);
            }
        } catch (\Exception $e) {
            Alert::error('Error', 'Something went wrong. Please try again later.')
                ->autoClose(5000)
                ->showCloseButton();
            return redirect()->back();
        }
    }

    public function deleteUserRFID($id)
    {
        try {
            $activate_RFID = RfidAccount::find($id);


            if ($activate_RFID) {
                // Get the RFID_Code to nullify in the users table
                $rfidCode = $activate_RFID->RFID_Code;
                $activate_RFID->delete();

                // Set RFID_Code to null in the users table based on RFID_Code
                DB::table('users')->where('RFID_Code', $rfidCode)->update(['RFID_Code' => null]);

                // Start Logs
                $ID = Auth::id();
                $userID = DB::table('users')->where('id', $ID)->value('idNumber');
                date_default_timezone_set("Asia/Manila");
                $date = date("Y-m-d");
                $time = date("H:i:s");
                $action = "Deleted an RFID Account";
                DB::table('user_logs')->insert([
                    'userID' => $userID,
                    'action' => $action,
                    'date' => $date,
                    'time' => $time,
                ]);
                // END Logs
                return response()->json([
                    'status' => 200,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                ]);
            }
        } catch (\Exception $e) {

            Alert::error('Error', 'Something went wrong. Please try again later.')
                ->autoClose(5000)
                ->showCloseButton();
            return redirect()->back();
        }
    }
    // END RFID Account Management

    public function autocomplete(Request $request)
    {
        $query = $request->get('query');
        // Modify the query to exclude users where RFID_Code is not null
        $number = User::where('idNumber', 'LIKE', "%{$query}%")
            ->whereNull('RFID_Code')
            ->get(['idNumber']);

        if ($number->isNotEmpty()) {
            return response()->json(['number' => $number]);
        } else {
            return response()->json(['status' => 400]);
        }
    }
}