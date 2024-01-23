<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Address;
use App\Models\AddressType;
use App\Models\Assignee;
use App\Models\BusinessUser;
use App\Models\FinancialRecord;
use App\Models\Firm;
use App\Models\FirmType;
use App\Models\FirmUser;
use App\Models\HonorificCode;

use App\Models\Package;
use App\Models\PackagePrice;
use App\Models\PostCode;
use App\Models\PostOffice;
use App\Models\Role;
use App\Models\BusinessType;
use App\Models\Business;
use App\Models\StateCode;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Exception;
use Hash;
use Schema;
use Str;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExcelExport;

class ActionController extends Controller
{
    public function storeImage($file)
    {
        if ($file) {
            $path = $file->store('public/logos');
            $imgName = str_replace('public/logos', '', $path);
            return $imgName;
        }
    }
    public function gethonorificcodeStats()
    {
        $stats = User::selectRaw('COUNT(users.id) as count, honorificcode.CodeName as name')
            ->leftJoin('honorificcode', 'users.HonorificCodeID', '=', 'honorificcode.id')
            ->groupBy('honorificcode.id', 'honorificcode.CodeName') // Group by honorific code ID and name
            ->get();
        return response()->json($stats);
    }
    public function getbusinesstypeStats()
    {
        $stats = Business::selectRaw('COUNT(business.id) as count, businesstype.businessTypeName as name')
            ->leftJoin('businesstype', 'business.businessType', '=', 'businesstype.id')
            ->groupBy('businesstype.id', 'businesstype.businessTypeName') // Group by business type ID and name
            ->get();

        return response()->json($stats);
    }
    public function getfirmtypeStats()
    {
        $stats = Firm::selectRaw('COUNT(firm.id) as count, firmtype.name as name')
            ->leftJoin('firmtype', 'firm.firmTypeID', '=', 'firmtype.id')
            ->groupBy('firmtype.id', 'firmtype.name') // Group by firm type ID and name
            ->get();

        return response()->json($stats);
    }
    public function getpackageStats()
    {
        $stats = Subscription::selectRaw('COUNT(subscription.id) as count, CONCAT(package.name, " for ", packageprice.duration, " days") as name')
            ->leftJoin('packageprice', 'subscription.packagepriceID', '=', 'packageprice.id')
            ->leftJoin('package', 'packageprice.PackageID', '=', 'package.id')
            ->groupBy('package.id', 'packageprice.duration') // Group by package ID and duration
            ->get();

        return response()->json($stats);
    }


    public function getaddresstypeStats()
    {
        $stats = Address::selectRaw('COUNT(address.id) as count, addresstype.name as name')
            ->leftJoin('addresstype', 'address.addressTypeID', '=', 'addresstype.id')
            ->groupBy('addresstype.id', 'addresstype.name') // Group by address type ID and name
            ->get();
        return response()->json($stats);
    }
   public function getTransactionTrends()
{
    $stats = Transaction::selectRaw('
        CONCAT(YEAR(paymentDateTime), " Q", QUARTER(paymentDateTime)) as yearQuarter,
        SUM(amount) as totalAmount,
        MONTHNAME(paymentDateTime) as monthName,
        YEAR(paymentDateTime) as year
    ')
    ->whereNull('deleted_at') // Exclude soft-deleted records
    ->groupBy('yearQuarter', 'monthName', 'year') // Include non-aggregated columns in GROUP BY
    ->get();

    // Calculate min and max months and years
    $maxRecord = Transaction::whereNull('deleted_at')->orderBy('amount', 'desc')->first(['paymentDateTime']);
    $maxMonthName = $maxRecord ? date('F', strtotime($maxRecord->paymentDateTime)) : null;
    $maxYear = $maxRecord ? date('Y', strtotime($maxRecord->paymentDateTime)) : null;

    $minRecord = Transaction::whereNull('deleted_at')->orderBy('amount', 'asc')->first(['paymentDateTime']);
    $minMonthName = $minRecord ? date('F', strtotime($minRecord->paymentDateTime)) : null;
    $minYear = $minRecord ? date('Y', strtotime($minRecord->paymentDateTime)) : null;

    return response()->json([
        'stats' => $stats,
        'maxMonth' => $maxMonthName,
        'maxYear' => $maxYear,
        'minMonth' => $minMonthName,
        'minYear' => $minYear,
    ]);
}

    
    

    public function getfirmStats()
    {
        $stats = FirmUser::selectRaw('COUNT(assignee.id) as count, GROUP_CONCAT(DISTINCT firm.firmName) as name')
            ->leftJoin('assignee', 'firmuser.id', '=', 'assignee.AssigneeID')
            ->leftJoin('firm', 'firmuser.firmID', '=', 'firm.id')
            ->where('assignee.Status', '=', 'Completed')
            ->groupBy('firm.id') // Group by firm ID to get individual assignment counts for each firm
            ->get();
        return response()->json($stats);
    }
    public function getuserStats()
    {
        $stats = User::selectRaw('COUNT(users.id) as count, role.name as name')
            ->leftJoin('role', 'users.RoleID', '=', 'role.id')
            ->groupBy('role.id', 'role.name') // Group by role ID and role name
            ->get();
        return response()->json($stats);
    }
    public function getStats($modelname)
    {
        $functionname = "get" . $modelname . "Stats";
        return $this->$functionname();
    }
    public function getSpecificRecordDetails($modelName)
{
    // Check if the model exists
    $modelInstance = app('\\App\\Models\\' . $modelName);

    // Initialize counts
    $counts = [
        'total' => 0,
        'creates' => [], // Created counts for each month and year
        'updates' => [], // Updated counts for each month and year
        'softDeletes' => [], // Soft Deleted counts for each month and year
    ];

    // Count total records
    $counts['total'] = $modelInstance->count();

    // Loop through records and count based on months and years
    $records = $modelInstance->withTrashed()->get(); // Include soft-deleted records

    foreach ($records as $record) {
        // Count created_at
        if (!is_null($record->created_at)) {
            $createdMonthYear = $record->created_at->format('F Y');
            $counts['creates'][$createdMonthYear] = ($counts['creates'][$createdMonthYear] ?? 0) + 1;
        }

        // Count updated_at
        if (!is_null($record->updated_at)) {
            $updatedMonthYear = $record->updated_at->format('F Y');
            $counts['updates'][$updatedMonthYear] = ($counts['updates'][$updatedMonthYear] ?? 0) + 1;
        }

        // Count soft deleted records
        if (!is_null($record->deleted_at)) {
            $softDeletedMonthYear = $record->deleted_at->format('F Y');
            $counts['softDeletes'][$softDeletedMonthYear] = ($counts['softDeletes'][$softDeletedMonthYear] ?? 0) + 1;
        }
    }

    return response()->json($counts);
}

    
public function showLog(Request $request)
    {
        $path = $request->getPathInfo();
        // Read the log file content
        $logContent = file_get_contents(storage_path('logs/laravel.log'));

        // Parse the log entries
        $logEntries = $this->parseLogContent($logContent);
        
        // Pass the log entries to the view
        return view($path, compact('logContent'));
    }

    private function parseLogContent($logContent)
    {
        // Decode the JSON log content
        $logEntries = json_decode($logContent, true);
        
        // If decoding fails, return an empty array
        return $logEntries ?: [];
    }
    public function LoadPage(Request $request)
    {
        
         
        if (Auth::check() == true) {
            $path = $request->getPathInfo();
            $activeSubCount = $this->getActiveSubscriptions();
            $activeAssignments = $this->getActiveAssignments();
            $statesCovered = $this->getTotalStates();
            $totalUsers = $this->getTotalUsers();
            $totalbusinesses = $this->getTotalBusiness();
            $firmsengaged = $this->getAssignedFirmTotal();
            $mostpackageresult = $this->getMostSubscribedPackage();
            $packageDetail = $mostpackageresult['mostSubscribedPackageDetails'];
            $packageCount = $mostpackageresult['mostSubscribedPackageCount'];
            $totalTransaction = $this->getTransactionTotal();
            $modelNames = [];

            $tables = Schema::getAllTables();

            foreach ($tables as $table) {
                // Access the table name directly
                $tableName = $table->{'Tables_in_accountingpac'};

                // Get the model name
                $modelName = Str::studly(Str::singular($tableName));

                // Store the model name in the array
                $modelNames[] = $modelName;
            }
            return view($path, ['modelNames' => $modelNames, 'totalTransaction' => $totalTransaction, 'activeSubCount' => $activeSubCount, 'packageDetail' => $packageDetail, 'packageCount' => $packageCount, 'activeAssignments' => $activeAssignments, 'statesCovered' => $statesCovered, 'totalbusinesses' => $totalbusinesses, 'firmsengaged' => $firmsengaged, 'totalUsers' => $totalUsers]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }

    public function ShowAddBusinessUser()
    {
        if (Auth::check() == true) {

            return view('Pages.Admin-Business.addbusinessuser');
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function ShowAddFirmUser()
    {
        if (Auth::check() == true) {

            return view('Pages.addfirmuser');
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function LoginService(Request $request)
    {
        $usernameData = $request->input('username');
        $passwordData = $request->input('password');

        $confirmPasswordData = $request->input('confirmpassword');
        if ($passwordData != $confirmPasswordData) {
            return Redirect::back()->withErrors(['msg' => 'Passwords does not match!']);
        } else {
            $user = User::where('email', $usernameData)->first();

            if (Hash::check($passwordData, $user->password)) {
                Auth::login($user);

                return redirect()->route('toindex')->with('sessionUsername', $usernameData);
            } else {
                Auth::logout();
                session(['loginSession' => false]);
                return redirect()->route('toLogin')->withErrors(['msg' => 'Invalid admin credentials!']);
            }
        }
    }
    public function LogoutService()
    {
        Auth::logout();
        return view('Pages.adminlogin');
    }

    //User Services
    public function getTotalUsers()
    {
        $totalUsers = User::all()->count();
        return $totalUsers;
    }
    public function UserProfileActions(Request $request, $actionType = null)
    {
        
        if (Auth::check() == true) {
            $alertMessage = "";
            if ($actionType == 'Edit') {
                $editarray = json_decode($request->input('rowcount'), true);
                foreach ($editarray as $x) {
                    $fname = $request->input('fname' . $x);
                    $lname = $request->input('lname' . $x);
                    $email = $request->input('email' . $x);
                    $password = $request->input('password' . $x);
                    $contact = $request->input('contact' . $x);
            
                    try {
                        $request->validate([
                            'fname' . $x => 'required',
                            'lname' . $x => 'required',
                            'email' . $x => 'required|email',
                            'password' . $x => 'required',
                            'contact' . $x => ['required', 'regex:/^(\+6)?(01[023456789][2-9]\d{6,7}|01[1][2-9]\d{6,7})$/']
                        ]);
                    } catch (Exception $e) {
                        $alertMessage = $e->getMessage();
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                    $id = $request->input('id' . $x);
                    $userData = User::where('id', $id)->first();
                    $userData->FirstName = $fname;
                    $userData->LastName = $lname;
                    $userData->email = $email;
                    $userData->password = $password;
                    $userData->contactNo = $contact;
                    $selectedhCode = $request->input('hCode' . $x);
                    $selectedRole = $request->input('role' . $x);
                    $hCodeID = HonorificCode::where('CodeName', $selectedhCode)->first()->id;
                    $roleID = Role::where('name', $selectedRole)->first()->id;
                    $userData->HonorificCodeID = $hCodeID;
                    $userData->RoleID = $roleID;
                    $userData->updated_at = Carbon::now()->toDateTimeString();
                    $userData->update();
                }
                $alertMessage = "User data has been successfully updated";
            } else if ($actionType == "Delete") {
                $delID = $request->get('deletebtn');
                $userToDelete = User::where('id', $delID)->first();

                $userToDelete->delete();
                $alertMessage = "User data has been successfully deleted";
            } else if ($actionType == "Add") {
                try {
                    $this->AddNewUser($request);
                    $alertMessage = "User data has been successfully added";
                } catch (Exception $e) {
                    $alertMessage = "Error adding user";
                }
            } else if ($actionType == null) {
                try {

                    return $this->DisplayUsers($request, 'allusers');

                } catch (Exception $e) {
                    $alertMessage = $e->getMessage();
                    return redirect()->back()->with('alertMessage', $alertMessage);
                }
            } else {
                $alertMessage = "An error has occurred";
                return redirect()->back()->with('updateError', $alertMessage);
            }
            return redirect()->back()->with('alertMessage', $alertMessage);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }

    }
    public function DisplayUsers(Request $request, $searchType)
    {
        if (Auth::check() == true) {
            $rowcount = 0;
            $usersData = User::all();
            $lastRecord = User::withTrashed()->latest('id')->first();
            $newUserID = $lastRecord ? $lastRecord->id + 1 : 1;
            $searchquery = $request->input('search');

            if ($searchType == 'onlineusers') {
                session_start();
                $_SESSION["latestUsersData"] = $usersData = User::where('Status', 'Online')->get();
            } else if ($searchType == 'allusers') {
                $_SESSION["latestUsersData"] = $usersData = User::all();
            } else if ($searchType == 'offlineusers') {
                $_SESSION["latestUsersData"] = $usersData = User::where('Status', 'Offline')->get();
            }
            if ($searchquery != null) {
                $usersData = $this->searchRecords(new User, $searchquery);

            }
            $usersData = $usersData->where('RoleID', '!=', '1');
            $roleNames = Role::where('id', '!=', '1')->get('name');
            $hCodeNames = HonorificCode::where('id', '!=', '1')->get('CodeName');
            return view(
                'Pages.Admin-User.displayusers',
                ['usersData' => $usersData, 'roleNames' => $roleNames, 'hCodeNames' => $hCodeNames, 'rowcount' => $rowcount, 'newUserID' => $newUserID]
            );
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function AddNewUser(Request $request)
    {
        if (Auth::check() == true) {
            try {
                $request->validate([
                    'newuser-id' => 'required',
                    'newuser-fname' => 'required',
                    'newuser-lname' => 'required',
                    'newuser-email' => 'required|email',
                    'newuser-password' => 'required',
                    'newuser-contact' => ['required', 'regex:/^(\+6)?(01[023456789][2-9]\d{6,7}|01[1][2-9]\d{6,7})$/']
                ]);
                $userData = new User;
                $userData->id = $request->input('newuser-id');
                $userData->FirstName = $request->input('newuser-fname');
                $userData->LastName = $request->input('newuser-lname');
                $userData->email = $request->input('newuser-email');
                $userData->password = $request->input('newuser-password');
                $userData->contactNo = $request->input('newuser-contact');
                $selectedhCode = $request->hCodeChoice2;
                $selectedRole = $request->roleChoice2;
                $newHCode = $request->input('newHcode');
                if ($newHCode != null) {
                    $this->AddHcode($request);
                    $selectedhCode = $newHCode;
                }
                $newRole = $request->input('newRole');
                if ($newRole != null) {
                    $this->AddRoles($request);
                    $selectedRole = $newRole;
                }
                $hCodeID = HonorificCode::where('CodeName', $selectedhCode)->first()->id;
                $roleID = Role::where('name', $selectedRole)->first()->id;
                $userData->HonorificCodeID = $hCodeID;
                $userData->Status = "Offline";
                $userData->RoleID = $roleID;
                
                $userData->save();
                return redirect()->back()->with('alertMessage', "User data saved successfully");
            } catch (Exception $e) {
                return redirect()->back()->with('alertMessage', $e);
            }

        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }

    //Admin Services
    public function UpdateProfile(Request $request)
    {
        $adminData = User::where('id', Auth::user()->id)->first();
        $adminData->FirstName = $request->input('fname');
        $adminData->LastName = $request->input('lname');
        $adminData->email = $request->input('email');
        $adminData->password = $request->input('password');
        $adminData->contactNo = $request->input('contactNo');
        $adminData->updated_at = Carbon::now()->toDateTimeString();
        $adminData->update();
        return redirect()->route('toprofile');
    }
    public function LoadProfile(Request $request)
    {
        if (Auth::check() == true) {
            $userData = User::where('id', Auth::user()->id)->first();
            $path = $request->getPathInfo();
            return view($path, ['userData' => $userData]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function EditProfile(Request $request)
    {
        if (Auth::check() == true) {
            $userData = User::where('id', Auth::user()->id)->first();
            $path = $request->getPathInfo();
            return view($path, ['userData' => $userData]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    //Business Types Services


    public function DisplayBusinessType(Request $request)
    {
        if (Auth::check() == true) {
            $path = $request->getPathInfo();
            $rowcount = 0;
            $lastRecord = BusinessType::withTrashed()->latest('id')->first();
            $bTypeID = $lastRecord ? $lastRecord->id + 1 : 1;
            $searchquery = $request->input('search');
            if ($searchquery != null) {
                $bTypes = $this->searchRecords(new BusinessType, $searchquery);

            } else {
                $bTypes = BusinessType::all();
            }

            return view($path, ['bTypes' => $bTypes, 'rowcount' => $rowcount, 'bTypeID' => $bTypeID]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function EditBusinessType($request)
    {
        try {
            $editarray = json_decode($request->input('rowcount'), true);
            foreach ($editarray as $i) {
                try {
                    $request->validate([
                        'bTypeID' . $i => 'required',
                        'bTypeName' . $i => 'required'
                    ]);
                } catch (Exception $e) {
                    return $alertMessage = "All fields must be filled in";
                }
                $id = $request->input('bTypeID' . $i);
                $bTypeName = $request->input('bTypeName' . $i);
                $bTypeEdit = BusinessType::where('id', $id)->first();
                $bTypeEdit->businessTypeName = $bTypeName;
                $bTypeEdit->update();
            }
            return $alertMessage = "Business Type data successfully updated";
        } catch (Exception $e) {
            ////dd($e);
            return $alertMessage = "Error editing business type data";
        }
    }
    public function DeleteBusinessType(Request $request)
    {
        try {
            $delID = $request->get('deletebtn');
            $BusinessTypeToDelete = BusinessType::where('id', $delID)->first();
            $BusinessTypeToDelete->delete();
            return $alertMessage = "Business Type data deleted successfully";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function AddBusinessType(Request $request)
    {
        try {
            $lastRecord = BusinessType::withTrashed()->latest('id')->first();
            $bTypeID = $lastRecord ? $lastRecord->id + 1 : 1;
            $newBusinessType = new BusinessType;
            $newBusinessType->id = $bTypeID;
            $newBusinessType->businessTypeName = $request->input('new-business-type');
            $newBusinessType->save();
            return $alertMessage = "New business type data successfully added";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function BusinessTypeActions(Request $request, $actionType = null)
    {
        if (Auth::check() == true) {
            $alertMessage = "";
            try {
                if ($actionType == "Edit") {
                    try {
                        $alertMessage = $this->EditBusinessType($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in editing business type data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == "Add") {
                    try {
                        $alertMessage = $this->AddBusinessType($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in adding business type data";
                        return redirect()->back()->with('alertMessage', $e);
                    }
                } else if ($actionType == "Delete") {
                    try {
                        $alertMessage = $this->DeleteBusinessType($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in deleting business type data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == null) {
                    try {

                        return $this->DisplayBusinessType($request);

                    } catch (Exception $e) {
                        $alertMessage = $e->getMessage();
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                }
            } catch (Exception $e) {
                ////dd($e);
                $alertMessage = "An error has occured";
                return redirect()->back()->with('alertMessage', $alertMessage);
            }
            return redirect()->back()->with('alertMessage', $alertMessage);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }


    //Business Services
    public function getTotalBusiness()
    {
        $totalBusiness = Business::all()->count();
        return $totalBusiness;
    }
    public function ShowRegBusiness(Request $request)
    {
        if (Auth::check() == true) {
            $path = $request->getPathInfo();
            $rowcount = 0;
            $lastRecord = Business::withTrashed()->latest('id')->first();
            $newBusinessData = $lastRecord ? $lastRecord->id + 1 : 1;
            $searchquery = $request->input('search');
            if ($searchquery != null) {
                $businessData = $this->searchRecords(new Business, $searchquery);

            } else {
                $businessData = Business::all();
            }
            $lastRecord = BusinessType::withTrashed()->latest('id')->first();
            $newBusinessTypeData = $lastRecord ? $lastRecord->id + 1 : 1;
            $businessTypes = BusinessType::all();
            
            return view($path, ['businessData' => $businessData, 'businessTypes' => $businessTypes, 'newBusinessData' => $newBusinessData, 'newBusinessTypeData' => $newBusinessTypeData, 'rowcount' => $rowcount]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function editBusiness(Request $request)
    {
        try {

            $editarray = json_decode($request->input('rowcount'), true);
            foreach ($editarray as $i) {
                try {
                    $request->validate([
                        'business-id' . $i => 'required',
                        'businessTypes' . $i => 'required',
                        'business-name' . $i => 'required',
                        'business-contact' . $i => 'required',
                        'business-email' . $i => 'required|email'
                    ]);
                } catch (Exception $e) {
                    dd($e);
                    return $alertMessage = "All fields must be filled in";
                }
                $id = $request->input('business-id' . $i);
                $bTypeID = $request->input('businessTypes' . $i);
                $businessType = BusinessType::where('businessTypeName', $bTypeID)->first()->id;
                $businessName = $request->input('business-name' . $i);
                $Contact = $request->input('business-contact' . $i);
                $email = $request->input('business-email' . $i);
                $editedLogo = $request->file('edited-logo'.$i);
                $bEdit = Business::where('id', $id)->first();
                $bEdit->businessName = $businessName;
                $bEdit->businessType = $businessType;
                $bEdit->Contact = $Contact;
                $bEdit->email = $email;
                if ($editedLogo) {
                    $logo = $this->storeImage($editedLogo);
                    $oldLogo = $request->input('oldlogosrc' . $i);
                    Storage::delete($oldLogo);
                    $bEdit->logo = $logo;
                }
                $bEdit->update();
            }
            return $alertMessage = "Business data successfully updated";
        } catch (Exception $e) {
            ////dd($e);
            return $alertMessage = "Error editing business data";
        }
    }
    public function deleteBusiness(Request $request)
    {
        $delID = $request->get('deletebtn');
        $userToDelete = Business::where('id', $delID)->first();
        $userToDelete->delete();
        return $alertMessage = "Business data deleted successfully";
    }
    public function BusinessActions(Request $request, $actionType = null)
    {
        if (Auth::check() == true) {
            $alertMessage = "";
            try {
                if ($actionType == "Edit") {
                    try {
                        $alertMessage = $this->editBusiness($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in editing business data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == "Add") {
                    try {
                        $alertMessage = $this->addBusiness($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in adding business data";
                        return redirect()->back()->with('alertMessage', $e);
                    }
                } else if ($actionType == "Delete") {
                    try {
                        $alertMessage = $this->deleteBusiness($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in deleting business data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == null) {
                    try {

                        return $this->ShowRegBusiness($request);

                    } catch (Exception $e) {
                        $alertMessage = $e->getMessage();
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                }
            } catch (Exception $e) {
                ////dd($e);
                $alertMessage = "An error has occured";
                return redirect()->back()->with('alertMessage', $alertMessage);
            }
            return redirect()->back()->with('alertMessage', $alertMessage);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function addBusiness(Request $request)
    {
        if (Auth::check() == true) {
            $id = $request->input('business-id');
            $bTypeName = $request->input('businessTypeChoice');
            $bTypeName2 = $request->input('new-business-type');
            if ($bTypeName2) {
                $this->AddBusinessType($request);
                $bTypeName = $bTypeName2;
            }
            $businessType = BusinessType::where('businessTypeName', $bTypeName)->first()->id;
            $businessName = $request->input('business-name');
            $Contact = $request->input('business-contact');
            $email = $request->input('business-email');
            $image = $request->file('business-logo');
            $logo = "";
            if ($image) {
                $logo = $this->storeImage($image);
            }
            $newBusiness = new Business;
            $newBusiness->id = $id;
            $newBusiness->businessType = $businessType;
            $newBusiness->businessName = $businessName;
            $newBusiness->Contact = $Contact;
            $newBusiness->email = $email;
            $newBusiness->logo = $logo;
            $newBusiness->save();
            return $alertMessage = "New Business Data added successfully";
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }


    }

    //Firm User Actions
    public function DisplayFirmUser(Request $request)
    {
        if (Auth::check() == true) {
            $path = $request->getPathInfo();
            $rowcount = 0;
            $lastRecord = FirmUser::withTrashed()->latest('id')->first();
            $searchquery = $request->input('search');
            if ($searchquery != null) {
                $firmusers = $this->searchRecords(new FirmUser, $searchquery);

            } else {
                $firmusers = FirmUser::all();
            }
            $newFirmUserID = $lastRecord ? $lastRecord->id + 1 : 1;
            $users = User::where('RoleID', '!=', '1')->get();
            $firms = Firm::all();
            return view($path, ['firms' => $firms, 'firmusers' => $firmusers, 'rowcount' => $rowcount, 'newFirmUserID' => $newFirmUserID, 'users' => $users]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function EditFirmUser($request)
    {
        try {
            $editarray = json_decode($request->input('rowcount'), true);
            foreach ($editarray as $i) {
                try {
                    $request->validate([
                        'firmuserid' . $i => 'required',
                        'miano' . $i => 'required',
                        'pcno' . $i => 'required'

                    ]);
                } catch (Exception $e) {
                    return $alertMessage = "All fields must be filled in";
                }

                $id = $request->input('firmuserid' . $i);
                $firmID = $request->input('firmname' . $i);

                $userID = $request->input('user' . $i);
                $MIA_NO = $request->input('miano' . $i);
                $PC_NO = $request->input('pcno' . $i);
                $firmUserEdit = FirmUser::where('id', $id)->first();
                $checkuser = Firm::where('firmOwnerID', $id)->first();

                if ($checkuser != null) {
                    $ownerfirmname = Firm::where('firmOwnerID', $id)->first()->firmName;
                    try {
                        $selectedfirmname = Firm::where('id', $firmID)->first()->firmName;
                        if ($ownerfirmname != $selectedfirmname) {
                            return $alertMessage = "Unable to make changes to ownership as user owns a firm, " . $ownerfirmname;
                        }
                    } catch (Exception $e) {
                        return $alertMessage = "Unable to make empty ownership as user owns a firm, " . $ownerfirmname;
                    }

                } else {
                    if ($firmID != "nofirm") {
                        $firmUserEdit->firmID = $firmID;
                    }
                    $firmUserEdit->userID = $userID;
                    $firmUserEdit->MIA_NO = $MIA_NO;
                    $firmUserEdit->PC_NO = $PC_NO;
                    $firmUserEdit->update();
                }
            }
            return $alertMessage = "Firm user data successfully updated";
        } catch (Exception $e) {
            ////dd($e);
            return $alertMessage = "Error editing firm user data";
        }
    }
    public function DeleteFirmUser(Request $request)
    {
        try {
            $delID = $request->get('deletebtn');
            $FirmUserToDelete = FirmUser::where('id', $delID)->first();

            $FirmUserToDelete->delete();
            return $alertMessage = "Firm user data deleted successfully";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function AddFirmUser(Request $request)
    {
        try {
            $newFirmUser = new FirmUser;
            $newFirmUser->id = $request->input('new-firmuser-id');
            $firmID = $request->input('new-firm-id');
            $userID = $request->input('new-user');
            $MIA_NO = $request->input('new-miano');
            $PC_NO = $request->input('new-pcno');
            if ($firmID != "empty") {
                $newFirmUser->firmID = $firmID;
            }
            $newFirmUser->userID = $userID;
            $newFirmUser->MIA_NO = $MIA_NO;
            $newFirmUser->PC_NO = $PC_NO;
            $newFirmUser->save();
            return $alertMessage = "New firm user data successfully added";
        } catch (Exception $e) {
            ////dd($e);
            return $e->getMessage();
        }
    }
    public function FirmUserActions(Request $request, $actionType = null)
    {
        if (Auth::check() == true) {
            $alertMessage = "";
            try {
                if ($actionType == "Edit") {
                    try {
                        $alertMessage = $this->EditFirmUser($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in editing firm user data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == "Add") {
                    try {
                        $alertMessage = $this->AddFirmUser($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in adding firm user data";
                        return redirect()->back()->with('alertMessage', $e);
                    }
                } else if ($actionType == "Delete") {
                    try {
                        $alertMessage = $this->DeleteFirmUser($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in deleting package base data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == null) {
                    try {

                        return $this->DisplayFirmUser($request);

                    } catch (Exception $e) {
                        $alertMessage = $e->getMessage();
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                }
            } catch (Exception $e) {
                ////dd($e);
                $alertMessage = "An error has occured";
                return redirect()->back()->with('alertMessage', $alertMessage);
            }
            return redirect()->back()->with('alertMessage', $alertMessage);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }

    //Firm Actions
    public function DisplayFirm(Request $request)
    {
        if (Auth::check() == true) {
            $path = $request->getPathInfo();
            $rowcount = 0;
            $lastRecord = Firm::withTrashed()->latest('id')->first();
            $newFirmID = $lastRecord ? $lastRecord->id + 1 : 1;
            $searchquery = $request->input('search');
            if ($searchquery != null) {
                $firms = $this->searchRecords(new Firm, $searchquery);

            } else {
                $firms = Firm::all();
            }
            $firms = Firm::all();
            $firmTypes = FirmType::all();
            $firmusers = FirmUser::all();
            $addresses = Address::whereHas('addressType', function ($query) {
                $query->where('name', 'Firm');
            })->get();
            return view($path, ['addresses' => $addresses, 'firms' => $firms, 'firmTypes' => $firmTypes, 'newFirmID' => $newFirmID, 'firmusers' => $firmusers, 'rowcount' => $rowcount]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function EditFirm(Request $request)
    {
        try {

            $editarray = json_decode($request->input('rowcount'), true);
            foreach ($editarray as $i) {
                try {
                    $request->validate([
                        'firm-id' . $i => 'required',
                        'firm-name' . $i => 'required',
                        'firmOwner' . $i => 'required',
                        'firmTypes' . $i => 'required',
                        'afno' . $i => 'required',
                        'ssmno' . $i => 'required',
                        'firm-contact' . $i => ['required', 'regex:/^(\+6)?(01[023456789][2-9]\d{6,7}|01[1][2-9]\d{6,7})$/'],
                        'firm-email' . $i => 'required|email',
                        'userlimit' . $i => 'required',
                        'status' . $i => 'required'
                    ]);
                } catch (Exception $e) {
                    return $alertMessage = "All fields must be filled in";
                }
                $id = $request->input('firm-id' . $i);
                $firmTypeName = $request->input('firmTypes' . $i);
                $firmTypeID = FirmType::where('name', $firmTypeName)->first()->id;
                $addressID = $request->input('firmAddress' . $i);
                $ownerName = $request->input('firmOwner' . $i);
                $userID = User::where('email', $ownerName)->first()->id;
                $firmuserID = FirmUser::where('userID', $userID)->first()->id;
                $firmName = $request->input('firm-name' . $i);
                $contactNo = $request->input('firm-contact' . $i);
                $emailAddress = $request->input('firm-email' . $i);
                $afno = $request->input('afno' . $i);
                $ssmno = $request->input('ssmno' . $i);
                $userlimit = $request->input('userlimit' . $i);
                $status = $request->input('status' . $i);
                $editedLogo = $request->file('edited-logo' . $i);

                $checkFirmUser = Firm::where('firmOwnerID', $firmuserID)->first();
                if ($checkFirmUser != null) {
                    $newFirmOwner = Firm::where('firmOwnerID', $firmuserID)->first()->id;
                    if ($newFirmOwner != $id) {
                        return $alertMessage = "Cannot change ownership as the user is an owner to another firm";
                    }
                }

                $firmEdit = Firm::where('id', $id)->first();
                $firmEdit->firmName = $firmName;
                $firmEdit->firmTypeID = $firmTypeID;
                $firmEdit->contactNo = $contactNo;
                $firmEdit->AF_NO = $afno;
                $firmEdit->SSM_NO = $ssmno;
                $firmEdit->status = $status;
                $firmEdit->userLimit = $userlimit;
                $firmEdit->emailAddress = $emailAddress;
                $firmEdit->firmOwnerID = $firmuserID;
                $firmEdit->addressID = $addressID;
                if ($editedLogo) {
                    $logo = $this->storeImage($editedLogo);
                    $oldLogo = $request->input('oldlogosrc' . $i);
                    Storage::delete($oldLogo);
                    $firmEdit->logo = $logo;
                }
                $firmEdit->update();
                $checkFirmID = FirmUser::where('id', $firmuserID)->first()->firmID;

                if ($checkFirmID == null) {

                    $firmuserEdit = FirmUser::where('id', $firmuserID)->first();
                    $firmuserEdit->firmID = $id;
                    $firmuserEdit->update();
                }
            }
            return $alertMessage = "Firm data successfully updated";
        } catch (Exception $e) {
            //dd($e);
            return $alertMessage = "Error editing firm data";
        }
    }
    public function DeleteFirm(Request $request)
    {
        $delID = $request->get('deletebtn');
        $firmToDelete = Firm::where('id', $delID)->first();
        $firmToDelete->delete();
        return $alertMessage = "Firm data deleted successfully";
    }

    public function AddFirm(Request $request)
    {
        if (Auth::check() == true) {
            $id = $request->input('new-firm-id');
            $fTypeName = $request->input('firmTypeChoice');
            $fTypeName2 = $request->input('new-firm-type2');
            if ($fTypeName2) {
                $this->AddFirmType($request);
                $fTypeName = $fTypeName2;
            }
            
            $firmType = FirmType::where('name', "Accounting")->first()->id;
            
            $firmName = $request->input('new-firm-name');
            $contactNo = $request->input('new-firm-contact');
            $afno = $request->input('new-af-no');
            $ssmno = $request->input('new-ssm-no');
            $email = $request->input('new-firm-email');
            $status = $request->input('new-status');
            $limit = $request->input('new-limit');
            $ownerName = $request->input('new-firm-owner');
            $addressID = $request->input('new-firm-address');
            $userID = User::where('email', $ownerName)->first()->id;
            $firmuserID = FirmUser::where('userID', $userID)->first()->id;

            
            $image = $request->file('new-firm-logo');
            
            $logo = "";
            if ($image) {
                $logo = $this->storeImage($image);
            }
            $newFirm = new Firm;
            $newFirm->id = $id;
            $newFirm->firmTypeID = $firmType;
            $newFirm->firmName = $firmName;
            $newFirm->contactNo = $contactNo;
            $newFirm->AF_NO = $afno;
            $newFirm->emailAddress = $email;
            $newFirm->SSM_NO = $ssmno;
            $newFirm->status = $status;
            $newFirm->userLimit = $limit;
            $newFirm->firmOwnerID = $firmuserID;
            $newFirm->logo = $logo;
            $newFirm->addressID = $addressID;
            $newFirm->save();
            $checkFirmID = FirmUser::where('id', $firmuserID)->first()->firmID;

            if ($checkFirmID == null) {

                $firmuserEdit = FirmUser::where('id', $firmuserID)->first();
                $firmuserEdit->firmID = $id;
                $firmuserEdit->update();
            }
            return $alertMessage = "New Firm Data added successfully";
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }


    }
    public function FirmActions(Request $request, $actionType = null)
    {
        if (Auth::check() == true) {
            $alertMessage = "";
            try {
                if ($actionType == "Edit") {
                    try {
                        $alertMessage = $this->EditFirm($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in editing firm data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == "Add") {
                    try {
                        $alertMessage = $this->AddFirm($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in adding firm data";
                        return redirect()->back()->with('alertMessage', $e);
                    }
                } else if ($actionType == "Delete") {
                    try {
                        $alertMessage = $this->DeleteFirm($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in deleting firm data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == null) {
                    try {

                        return $this->DisplayFirm($request);

                    } catch (Exception $e) {
                        $alertMessage = $e->getMessage();
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                }
            } catch (Exception $e) {
                //dd($e);
                $alertMessage = "An error has occured";
                return redirect()->back()->with('alertMessage', $alertMessage);
            }
            return redirect()->back()->with('alertMessage', $alertMessage);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }

    //Role Services
    public function DisplayRoles(Request $request)
    {
        if (Auth::check() == true) {
            $path = $request->getPathInfo();
            $rowcount = 0;
            $lastRecord = Role::withTrashed()->latest('id')->first();
            $newRoleID = $lastRecord ? $lastRecord->id + 1 : 1;
            $searchquery = $request->input('search');
            if ($searchquery != null) {
                $roles = $this->searchRecords(new Role, $searchquery);

            } else {
                $roles = Role::all();
            }

            return view($path, ['roles' => $roles, 'rowcount' => $rowcount, 'newRoleID' => $newRoleID]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function EditRole($request)
    {
        
        try {
            $editarray = json_decode($request->input('rowcount'), true);
            foreach ($editarray as $i) {
                try {
                    $request->validate([
                        'roleid' . $i => 'required',
                        'rolename' . $i => 'required'
                    ]);
                } catch (Exception $e) {
                    return $alertMessage = "All fields must be filled in";
                }
                $id = $request->input('roleid' . $i);
                $roleName = $request->input('rolename' . $i);
                $roleEdit = Role::where('id', $id)->first();
                $roleEdit->name = $roleName;
                $roleEdit->update();
            }
            return $alertMessage = "Role data successfully updated";
        } catch (Exception $e) {
            //dd($e);
            return $alertMessage = "Error editing role data";
        }
    }
    public function DeleteRole(Request $request)
    {
        try {
            $delID = $request->get('deletebtn');
            $roleToDelete = Role::where('id', $delID)->first();
            $roleToDelete->delete();
            return $alertMessage = "Role data deleted successfully";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function AddRoles(Request $request)
    {
        $newRole = new Role;
        $newRole->id = Role::get()->last()->id + 1;
        $newRole->name = $request->input('newRole');
        $newRole->save();
    }
    public function RoleActions(Request $request, $actionType = null)
    {
        if (Auth::check() == true) {
            $alertMessage = "";
            if ($actionType == "Edit") {
                
                
                    $alertMessage = $this->EditRole($request);
                    return redirect()->back()->with('alertMessage', $alertMessage);
                
            } else if ($actionType == "Delete") {
                $alertMessage = $this->DeleteRole($request);
                return redirect()->back()->with('alertMessage', $alertMessage);
            } else if ($actionType == "Add") {
                try {
                    $request->validate([
                        'newRole' => 'required',
                    ]);
                    $this->AddRoles($request);
                    $alertMessage = "New role data successfully added";
                    return redirect()->back()->with('alertMessage', $alertMessage);
                } catch (Exception $e) {
                    $alertMessage = $e->getMessage();
                    return redirect()->back()->with('alertMessage', $alertMessage);
                }
            } else if ($actionType == null) {
                try {

                    return $this->DisplayRoles($request);

                } catch (Exception $e) {
                    $alertMessage = $e->getMessage();
                    return redirect()->back()->with('alertMessage', $alertMessage);
                }
            }
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }

    // Honorific Code Services
    public function DisplayHCodes(Request $request)
    {
        if (Auth::check() == true) {
            $path = $request->getPathInfo();
            $rowcount = 0;
            $lastRecord = HonorificCode::withTrashed()->latest('id')->first();
            $newHcodeID = $lastRecord ? $lastRecord->id + 1 : 1;
            $searchquery = $request->input('search');
            if ($searchquery != null) {
                $hcodes = $this->searchRecords(new HonorificCode, $searchquery);

            } else {
                $hcodes = HonorificCode::all();
            }
            $hcodes = HonorificCode::where('id', '!=', '1')->get();
            return view($path, ['hcodes' => $hcodes, 'rowcount' => $rowcount, 'newHcodeID' => $newHcodeID]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function EditHCode($request)
    {
        try {
            $editarray = json_decode($request->input('rowcount'), true);
            foreach ($editarray as $i) {
                try {
                    $request->validate([
                        'hcodeID' . $i => 'required',
                        'hCodeName' . $i => 'required'
                    ]);
                } catch (Exception $e) {
                    return $alertMessage = "All fields must be filled in";
                }
                $id = $request->input('hcodeID' . $i);
                $HonorificCodeName = $request->input('hCodeName' . $i);
                $HonorificCodeEdit = HonorificCode::where('id', $id)->first();
                $HonorificCodeEdit->CodeName = $HonorificCodeName;
                $HonorificCodeEdit->update();
            }
            return $alertMessage = "Honorific code data successfully updated";
        } catch (Exception $e) {
            ////dd($e);
            return $alertMessage = "Error editing honorific code data";
        }
    }
    public function DeleteHonorificCode(Request $request)
    {
        try {
            $delID = $request->get('deletebtn');
            $HonorificCodeToDelete = HonorificCode::where('id', $delID)->first();
            $HonorificCodeToDelete->delete();
            return $alertMessage = "Honorific Code data deleted successfully";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function AddHcode(Request $request)
    {
        try {
            $newHCode = new HonorificCode;
            $newHCode->id = HonorificCode::get()->last()->id + 1;
            $newHCode->CodeName = $request->input('newHcode');
            $newHCode->save();
            return $alertMessage = "New honorific code data successfully added";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function HcodeActions(Request $request, $actionType = null)
    {
        if (Auth::check() == true) {
            $alertMessage = "";
            try {
                if ($actionType == "Edit") {
                    try {
                        $alertMessage = $this->EditHCode($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in editing honorific code data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == "Add") {
                    try {
                        $alertMessage = $this->AddHcode($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in adding honorific code data";
                        return redirect()->back()->with('alertMessage', $e);
                    }
                } else if ($actionType == "Delete") {
                    try {
                        $alertMessage = $this->DeleteHonorificCode($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in deleting honorific code data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == null) {
                    try {

                        return $this->DisplayHCodes($request);

                    } catch (Exception $e) {
                        $alertMessage = $e->getMessage();
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                }
            } catch (Exception $e) {
                ////dd($e);
                $alertMessage = "An error has occured";
                return redirect()->back()->with('alertMessage', $alertMessage);
            }
            return redirect()->back()->with('alertMessage', $alertMessage);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }

    //Address Type Services
    public function DisplayAddressType(Request $request)
    {
        if (Auth::check() == true) {
            $path = $request->getPathInfo();
            $rowcount = 0;
            $lastRecord = AddressType::withTrashed()->latest('id')->first();
            $AddressTypeID = $lastRecord ? $lastRecord->id + 1 : 1;
            $searchquery = $request->input('search');
            if ($searchquery != null) {
                $AddressTypes = $this->searchRecords(new AddressType, $searchquery);

            } else {
                $AddressTypes = AddressType::all();
            }

            return view($path, ['AddressTypes' => $AddressTypes, 'rowcount' => $rowcount, 'AddressTypeID' => $AddressTypeID]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function EditAddressType($request)
    {
        try {
            $editarray = json_decode($request->input('rowcount'), true);
            foreach ($editarray as $i) {
                try {
                    $request->validate([
                        'addressTypeID' . $i => 'required',
                        'addressTypeName' . $i => 'required'
                    ]);
                } catch (Exception $e) {
                    return $alertMessage = "All fields must be filled in";
                }
                $id = $request->input('addressTypeID' . $i);
                $addressTypeName = $request->input('addressTypeName' . $i);
                $addressTypeEdit = AddressType::where('id', $id)->first();
                $addressTypeEdit->name = $addressTypeName;
                $addressTypeEdit->update();
            }
            return $alertMessage = "Address Type data successfully updated";
        } catch (Exception $e) {
            ////dd($e);
            return $alertMessage = "Error editing address type data";
        }
    }
    public function DeleteAddressType(Request $request)
    {
        try {
            $delID = $request->get('deletebtn');
            $addressTypeToDelete = AddressType::where('id', $delID)->first();
            $addressTypeToDelete->delete();
            return $alertMessage = "Address Type data deleted successfully";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function AddAddressType(Request $request)
    {
        try {
            $newAddressType = new AddressType;
            $lastRecord = AddressType::withTrashed()->latest('id')->first();
            $newAddressType->id = $lastRecord ? $lastRecord->id + 1 : 1;
            $newAddressType->name = $request->input('new-address-type');
            $newAddressType->save();
            return $alertMessage = "New address type data successfully added";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function AddressTypeActions(Request $request, $actionType = null)
    {
        if (Auth::check() == true) {
            $alertMessage = "";
            try {
                if ($actionType == "Edit") {
                    try {
                        
                        $alertMessage = $this->EditAddressType($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in editing business type data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == "Add") {
                    try {
                        $alertMessage = $this->AddAddressType($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in adding business type data";
                        return redirect()->back()->with('alertMessage', $e);
                    }
                } else if ($actionType == "Delete") {
                    try {
                        $alertMessage = $this->DeleteAddressType($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in deleting business type data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == null) {
                    try {

                        return $this->DisplayAddressType($request);

                    } catch (Exception $e) {
                        $alertMessage = $e->getMessage();
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                }
            } catch (Exception $e) {
                ////dd($e);
                $alertMessage = "An error has occured";
                return redirect()->back()->with('alertMessage', $alertMessage);
            }
            return redirect()->back()->with('alertMessage', $alertMessage);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }

    //Firm Type Actions
    public function DisplayFirmType(Request $request)
    {
        if (Auth::check() == true) {
            $path = $request->getPathInfo();
            $rowcount = 0;
            $lastRecord = FirmType::withTrashed()->latest('id')->first();
            $FirmTypeID = $lastRecord ? $lastRecord->id + 1 : 1;
            $searchquery = $request->input('search');
            if ($searchquery != null) {
                $FirmTypes = $this->searchRecords(new FirmType, $searchquery);

            } else {
                $FirmTypes = FirmType::all();
            }

            return view($path, ['FirmTypes' => $FirmTypes, 'rowcount' => $rowcount, 'FirmTypeID' => $FirmTypeID]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function EditFirmType($request)
    {
        try {
            $editarray = json_decode($request->input('rowcount'), true);
            foreach ($editarray as $i) {
                try {
                    $request->validate([
                        'firmTypeID' . $i => 'required',
                        'firmTypeName' . $i => 'required'
                    ]);
                } catch (Exception $e) {
                    return $alertMessage = "All fields must be filled in";
                }
                $id = $request->input('firmTypeID' . $i);
                $firmTypeName = $request->input('firmTypeName' . $i);
                $firmTypeEdit = FirmType::where('id', $id)->first();
                $firmTypeEdit->name = $firmTypeName;
                $firmTypeEdit->update();
            }
            return $alertMessage = "Firm Type data successfully updated";
        } catch (Exception $e) {
            ////dd($e);
            return $alertMessage = "Error editing firm type data";
        }
    }
    public function DeleteFirmType(Request $request)
    {
        try {
            $delID = $request->get('deletebtn');
            $FirmTypeToDelete = FirmType::where('id', $delID)->first();
            $FirmTypeToDelete->delete();
            return $alertMessage = "Firm Type data deleted successfully";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function AddFirmType(Request $request)
    {
        try {
            $newFirmType = new FirmType;
            $lastRecord = FirmType::withTrashed()->latest('id')->first();
            $newFirmType->id = $lastRecord ? $lastRecord->id + 1 : 1;
            $newFirmType->name = $request->input('new-firm-type');
            $newFirmType->save();
            return $alertMessage = "New firm type data successfully added";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function FirmTypeActions(Request $request, $actionType = null)
    {
        if (Auth::check() == true) {
            $alertMessage = "";
            try {
                if ($actionType == "Edit") {
                    try {
                        $alertMessage = $this->EditFirmType($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in editing firm type data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == "Add") {
                    try {
                        $alertMessage = $this->AddFirmType($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in adding firm type data";
                        return redirect()->back()->with('alertMessage', $e);
                    }
                } else if ($actionType == "Delete") {
                    try {
                        $alertMessage = $this->DeleteFirmType($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in deleting firm type data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == null) {
                    try {

                        return $this->DisplayFirmType($request);

                    } catch (Exception $e) {
                        $alertMessage = $e->getMessage();
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                }
            } catch (Exception $e) {
                ////dd($e);
                $alertMessage = "An error has occured";
                return redirect()->back()->with('alertMessage', $alertMessage);
            }
            return redirect()->back()->with('alertMessage', $alertMessage);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }

    //Package Actions
    public function getPackages($user)
    {
        $roleID = User::where('id', $user)->first()->RoleID;
        $roleName = Role::where('id', $roleID)->first()->name;

        $packagesBase = PackagePrice::select('id', 'PackageID', 'duration', 'baseprice')
            ->whereHas('package', function ($query) use ($roleName) {
                $query->where('name', 'LIKE', '%' . $roleName . '%');
            })
            ->with([
                'package' => function ($query) {
                    $query->select('id', 'name', 'PackageCode'); // Specify the columns you want from the Package table
                }
            ])
            ->get();

        return response()->json(['packages' => $packagesBase]);
    }
    public function getCode($id)
    {
        $packageID = PackagePrice::where('id', $id)->first()->PackageID;
        $code = Package::where('id', $packageID)->first()->PackageCode;
        $duration = PackagePrice::where('id', $id)->first()->duration;
        return response()->json(['code' => $code, 'duration' => $duration]);
    }
    public function DisplayPackage(Request $request)
    {
        if (Auth::check() == true) {
            $path = $request->getPathInfo();
            $rowcount = 0;
            $searchquery = $request->input('search');
            if ($searchquery != null) {
                $packages = $this->searchRecords(new Package, $searchquery);

            } else {
                $packages = Package::all();
            }

            $lastRecord = Package::withTrashed()->latest('id')->first();
            $newPackageID = $lastRecord ? $lastRecord->id + 1 : 1;
            return view($path, ['packages' => $packages, 'rowcount' => $rowcount, 'newPackageID' => $newPackageID]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function EditPackage($request)
    {
        try {
            $editarray = json_decode($request->input('rowcount'), true);
            foreach ($editarray as $i) {
                try {
                    $request->validate([
                        'packageid' . $i => 'required',
                        'packagecode' . $i => 'required',
                        'packagename' . $i => 'required',
                        'userlimit' . $i => 'required'
                    ]);
                } catch (Exception $e) {
                    return $alertMessage = "All fields must be filled in";
                }
                $id = $request->input('packageid' . $i);
                $packageCode = $request->input('packagecode' . $i);
                $packagename = $request->input('packagename' . $i);
                $userlimit = $request->input('userlimit' . $i);
                $packageEdit = Package::where('id', $id)->first();
                $packageEdit->PackageCode = $packageCode;
                $packageEdit->name = $packagename;
                $packageEdit->userlimit = $userlimit;
                $packageEdit->update();
            }
            return $alertMessage = "Package data successfully updated";
        } catch (Exception $e) {
            ////dd($e);
            return $alertMessage = "Error editing package data";
        }
    }
    public function DeletePackage(Request $request)
    {
        try {
            $delID = $request->get('deletebtn');
            $PackageToDelete = Package::where('id', $delID)->first();
            $PackageToDelete->delete();
            return $alertMessage = "Package data deleted successfully";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function AddPackage(Request $request)
    {
        try {
            $newPackage = new Package;
            $newPackage->id = $request->input('new-package-id');
            $newPackage->PackageCode = $request->input('new-package-PackageCode');
            $newPackage->name = $request->input('new-package-name');
            $newPackage->userlimit = $request->input('new-package-userlimit');
            $newPackage->save();
            return $alertMessage = "New package data successfully added";
        } catch (Exception $e) {
            ////dd($e);
            return $e->getMessage();
        }
    }
    public function PackageActions(Request $request, $actionType = null)
    {
        if (Auth::check() == true) {
            $alertMessage = "";
            try {
                if ($actionType == "Edit") {
                    try {
                        $alertMessage = $this->EditPackage($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in editing package data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == "Add") {
                    try {
                        $alertMessage = $this->AddPackage($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in adding package data";
                        return redirect()->back()->with('alertMessage', $e);
                    }
                } else if ($actionType == "Delete") {
                    try {
                        $alertMessage = $this->DeletePackage($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in deleting package data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == null) {
                    try {

                        return $this->DisplayPackage($request);

                    } catch (Exception $e) {
                        $alertMessage = $e->getMessage();
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                }
            } catch (Exception $e) {
                ////dd($e);
                $alertMessage = "An error has occured";
                return redirect()->back()->with('alertMessage', $alertMessage);
            }
            return redirect()->back()->with('alertMessage', $alertMessage);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }

    //Package Base Actions
    public function DisplayPackageBase(Request $request)
    {
        if (Auth::check() == true) {
            $path = $request->getPathInfo();
            $rowcount = 0;
            $basepackages = PackagePrice::all();
            $lastRecord = PackagePrice::withTrashed()->latest('id')->first();
            $newBasePackageID = $lastRecord ? $lastRecord->id + 1 : 1;
            $searchquery = $request->input('search');
            if ($searchquery != null) {
                $basepackages = $this->searchRecords(new PackagePrice, $searchquery);

            } else {
                $basepackages = PackagePrice::all();
            }
            $packageNames = Package::all();
            return view($path, ['basepackages' => $basepackages, 'rowcount' => $rowcount, 'newBasePackageID' => $newBasePackageID, 'packageNames' => $packageNames]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function EditPackageBase($request)
    {
        try {
            $editarray = json_decode($request->input('rowcount'), true);
            foreach ($editarray as $i) {
                try {
                    $request->validate([
                        'packagebaseid' . $i => 'required',
                        'packagename' . $i => 'required',
                        'duration' . $i => 'required',
                        'baseprice' . $i => 'required'
                    ]);
                } catch (Exception $e) {
                    return $alertMessage = "All fields must be filled in";
                }
                $id = $request->input('packagebaseid' . $i);
                $baseprice = $request->input('baseprice' . $i);
                $packagename = $request->input('packagename' . $i);
                $packageid = Package::where('name', $packagename)->first()->id;
                $duration = $request->input('duration' . $i);
                $packageBaseEdit = PackagePrice::where('id', $id)->first();
                $packageBaseEdit->baseprice = $baseprice;
                $packageBaseEdit->PackageID = $packageid;
                $packageBaseEdit->duration = $duration;
                $packageBaseEdit->update();
            }
            return $alertMessage = "Package base data successfully updated";
        } catch (Exception $e) {
            ////dd($e);
            return $alertMessage = "Error editing package base data";
        }
    }
    public function DeletePackageBase(Request $request)
    {
        try {
            $delID = $request->get('deletebtn');
            $PackageBaseToDelete = PackagePrice::where('id', $delID)->first();
            $PackageBaseToDelete->delete();
            return $alertMessage = "Package Base data deleted successfully";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function AddPackageBase(Request $request)
    {
        try {
            $newPackageBase = new PackagePrice;
            $newPackageBase->id = $request->input('new-basepackage-id');
            $packagename = $request->selectedpackagename;
            $packageid = Package::where('name', $packagename)->first()->id;
            $newPackageBase->PackageID = $packageid;
            $newPackageBase->duration = $request->input('new-basepackage-duration');
            $newPackageBase->baseprice = $request->input('new-basepackage-price');
            $newPackageBase->save();
            return $alertMessage = "New base package data successfully added";
        } catch (Exception $e) {
            ////dd($e);
            return $e->getMessage();
        }
    }
    public function PackageBaseActions(Request $request, $actionType = null)
    {
        if (Auth::check() == true) {
            $alertMessage = "";

            try {
                if ($actionType == "Edit") {
                    try {
                        $alertMessage = $this->EditPackageBase($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in editing package base data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == "Add") {
                    try {
                        $alertMessage = $this->AddPackageBase($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in adding package base data";
                        return redirect()->back()->with('alertMessage', $e);
                    }
                } else if ($actionType == "Delete") {
                    try {

                        $alertMessage = $this->DeletePackageBase($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in deleting package base data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == "excel") {
                    try {

                        $alertMessage = $this->exportToExcel();
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in deleting package base data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == null) {
                    try {

                        return $this->DisplayPackageBase($request);

                    } catch (Exception $e) {
                        $alertMessage = $e->getMessage();
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                }
            } catch (Exception $e) {
                ////dd($e);
                $alertMessage = "An error has occured";
                return redirect()->back()->with('alertMessage', $alertMessage);
            }
            return redirect()->back()->with('alertMessage', $alertMessage);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }

    //Business User Actions
    public function DisplayBusinessUser(Request $request)
    {
        if (Auth::check() == true) {
            $path = $request->getPathInfo();
            $rowcount = 0;
            $searchquery = $request->input('search');
            if ($searchquery != null) {
                $busers = $this->searchRecords(new BusinessUser, $searchquery);

            } else {
                $busers = BusinessUser::all();
            }

            $businesses = Business::all();
            $users = User::where('RoleID', '!=', '1')->get();

            $lastRecord = BusinessUser::withTrashed()->latest('id')->first();
            $newbUserID = $lastRecord ? $lastRecord->id + 1 : 1;
            $packageNames = Package::all();
            return view($path, ['busers' => $busers, 'rowcount' => $rowcount, 'newbUserID' => $newbUserID, 'users' => $users, 'businesses' => $businesses]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function EditBusinessUser($request)
    {
        try {
            $editarray = json_decode($request->input('rowcount'), true);
            foreach ($editarray as $i) {
                try {
                    $request->validate([
                        'businessuserid' . $i => 'required',
                        'businessname' . $i => 'required',
                        'useremail' . $i => 'required'
                    ]);
                } catch (Exception $e) {
                    ////dd($e);
                    return $alertMessage = "All fields must be filled in";
                }
                $id = $request->input('businessuserid' . $i);
                $businessName = $request->input('businessname' . $i);
                $businessid = Business::where('businessName', $businessName)->first()->id;
                $userEmail = $request->input('useremail' . $i);
                $userid = User::where('Email', $userEmail)->first()->id;
                $businessUserEdit = BusinessUser::where('id', $id)->first();
                $businessUserEdit->businessID = $businessid;
                $businessUserEdit->userID = $userid;
                $businessUserEdit->update();
            }
            return $alertMessage = "Business user data successfully updated";
        } catch (Exception $e) {
            ////dd($e);
            return $alertMessage = "Error editing business user data";
        }
    }
    public function DeleteBusinessUser(Request $request)
    {
        try {
            $delID = $request->get('deletebtn');
            $BusinessUserToDelete = BusinessUser::where('id', $delID)->first();
            $BusinessUserToDelete->delete();
            return $alertMessage = "Business user data deleted successfully";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function AddBusinessUser(Request $request)
    {
        try {
            $newBusinessUser = new BusinessUser;
            $newBusinessUser->id = $request->input('new-buser-id');
            $businessName = $request->selectedBusiness;
            $businessID = Business::where('businessName', $businessName)->first()->id;
            $newBusinessUser->businessID = $businessID;
            $businessUserEmail = $request->selectedUser;
            $userID = User::where('email', $businessUserEmail)->first()->id;
            $newBusinessUser->userID = $userID;

            $newBusinessUser->save();
            return $alertMessage = "New business user data successfully added";
        } catch (Exception $e) {
            ////dd($e);
            return $e->getMessage();
        }
    }
    public function BusinessUserActions(Request $request, $actionType = null)
    {
        if (Auth::check() == true) {
            $alertMessage = "";
            try {
                if ($actionType == "Edit") {
                    try {
                        $alertMessage = $this->EditBusinessUser($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in editing business user data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == "Add") {
                    try {
                        $alertMessage = $this->AddBusinessUser($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in adding business user data";
                        return redirect()->back()->with('alertMessage', $e);
                    }
                } else if ($actionType == "Delete") {
                    try {
                        $alertMessage = $this->DeleteBusinessUser($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in deleting business user data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == null) {
                    try {

                        return $this->DisplayBusinessUser($request);

                    } catch (Exception $e) {
                        $alertMessage = $e->getMessage();
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                }
            } catch (Exception $e) {
                ////dd($e);
                $alertMessage = "An error has occured";
                return redirect()->back()->with('alertMessage', $alertMessage);
            }
            return redirect()->back()->with('alertMessage', $alertMessage);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }

    //Post Office Actions
    public function DisplayPostOffices(Request $request)
    {
        if (Auth::check() == true) {
            $path = $request->getPathInfo();
            $rowcount = 0;
            $checkNull = PostOffice::get()->last();
            $lastRecord = PostOffice::withTrashed()->latest('id')->first();
            $newPostofficeID = $lastRecord ? $lastRecord->id + 1 : 1;
            $searchquery = $request->input('search');
            if ($searchquery != null) {
                $postoffices = $this->searchRecords(new PostOffice, $searchquery);

            } else {
                $postoffices = PostOffice::all();
            }

            return view($path, ['postoffices' => $postoffices, 'rowcount' => $rowcount, 'newPostofficeID' => $newPostofficeID]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function EditPostOffice($request)
    {
        try {
            $editarray = json_decode($request->input('rowcount'), true);
            foreach ($editarray as $i) {
                try {
                    $request->validate([
                        'postofficeID' . $i => 'required',
                        'postofficeName' . $i => 'required'
                    ]);
                } catch (Exception $e) {
                    return $alertMessage = "All fields must be filled in";
                }
                $id = $request->input('postofficeID' . $i);
                $postOfficeName = $request->input('postofficeName' . $i);
                $postOfficeEdit = PostOffice::where('id', $id)->first();
                $postOfficeEdit->name = $postOfficeName;
                $postOfficeEdit->update();
            }
            return $alertMessage = "Post office data successfully updated";
        } catch (Exception $e) {
            ////dd($e);
            return $alertMessage = "Error editing post office data";
        }
    }
    public function DeletePostOffice(Request $request)
    {
        try {
            $delID = $request->get('deletebtn');
            $PostOfficeToDelete = PostOffice::where('id', $delID)->first();
            $PostOfficeToDelete->delete();
            return $alertMessage = "Post office data deleted successfully";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function AddPostOffice(Request $request)
    {
        try {
            $newPostOffice = new PostOffice;
            $lastRecord = PostOffice::withTrashed()->latest('id')->first();
            $postOfficeID = $lastRecord ? $lastRecord->id + 1 : 1;
            $newPostOffice->id = $postOfficeID;
            $newPostOffice->name = $request->input('new-postoffice-name');
            $newPostOffice->save();
            return $alertMessage = "New post office data successfully added";
        } catch (Exception $e) {
            ////dd($e);
            return $e->getMessage();
        }
    }
    public function PostOfficeActions(Request $request, $actionType = null)
    {
        if (Auth::check() == true) {
            $alertMessage = "";

            try {
                if ($actionType == "Edit") {
                    try {
                        $alertMessage = $this->EditPostOffice($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in editing post office data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == "Add") {
                    try {
                        $alertMessage = $this->AddPostOffice($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in adding post office data";
                        return redirect()->back()->with('alertMessage', $e);
                    }
                } else if ($actionType == "Delete") {
                    try {
                        $alertMessage = $this->DeletePostOffice($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in deleting firm type data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == null) {
                    try {

                        return $this->DisplayPostOffices($request);

                    } catch (Exception $e) {
                        $alertMessage = $e->getMessage();
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                }
            } catch (Exception $e) {
                ////dd($e);
                $alertMessage = "An error has occured";
                return redirect()->back()->with('alertMessage', $alertMessage);
            }
            return redirect()->back()->with('alertMessage', $alertMessage);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }

    //State Code Actions
    public function getTotalStates()
    {
        $numberOfStates = Address::join('postcode', 'address.postCodeID', '=', 'postcode.id')
            ->join('statecode', 'postcode.stateCodeID', '=', 'statecode.id')
            ->distinct('statecode.id')
            ->count('statecode.id');
        return $numberOfStates;
    }
    public function DisplayStateCode(Request $request)
    {
        if (Auth::check() == true) {
            $path = $request->getPathInfo();
            $rowcount = 0;
            $checkNull = StateCode::get()->last();
            $lastRecord = StateCode::withTrashed()->latest('id')->first();
            $newStateCodeID = $lastRecord ? $lastRecord->id + 1 : 1;
            $searchquery = $request->input('search');
            if ($searchquery != null) {
                $statecodes = $this->searchRecords(new StateCode, $searchquery);

            } else {
                $statecodes = StateCode::all();
            }

            return view($path, ['statecodes' => $statecodes, 'rowcount' => $rowcount, 'newStateCodeID' => $newStateCodeID]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function EditStateCode($request)
    {
        try {
            $editarray = json_decode($request->input('rowcount'), true);
            foreach ($editarray as $i) {
                try {
                    $request->validate([
                        'stateCodeID' . $i => 'required',
                        'stateName' . $i => 'required'
                    ]);
                } catch (Exception $e) {
                    return $alertMessage = "All fields must be filled in";
                }
                $id = $request->input('stateCodeID' . $i);
                $stateCodeName = $request->input('stateName' . $i);
                $stateCodeEdit = StateCode::where('id', $id)->first();
                $stateCodeEdit->name = $stateCodeName;
                $stateCodeEdit->update();
            }
            return $alertMessage = "State code data successfully updated";
        } catch (Exception $e) {
            ////dd($e);
            return $alertMessage = "Error editing state code data";
        }
    }
    public function DeleteStateCode(Request $request)
    {
        try {
            $delID = $request->get('deletebtn');
            $StateCodeToDelete = StateCode::where('id', $delID)->first();
            $StateCodeToDelete->delete();
            return $alertMessage = "State code data deleted successfully";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function AddStateCode(Request $request)
    {
        try {
            $newStateCode = new StateCode;
            $lastRecord = StateCode::withTrashed()->latest('id')->first();
            $stateID = $lastRecord ? $lastRecord->id + 1 : 1;
            $newStateCode->id = $stateID;
            $newStateCode->name = $request->input('new-statecode-name');
            $newStateCode->save();
            return $alertMessage = "New state code data successfully added";
        } catch (Exception $e) {
            ////dd($e);
            return $e->getMessage();
        }
    }
    public function StateCodeActions(Request $request, $actionType = null)
    {
        if (Auth::check() == true) {
            $alertMessage = "";

            try {
                if ($actionType == "Edit") {
                    try {
                        $alertMessage = $this->EditStateCode($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in editing state code data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == "Add") {
                    try {
                        $alertMessage = $this->AddStateCode($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in adding state code data";
                        return redirect()->back()->with('alertMessage', $e);
                    }
                } else if ($actionType == "Delete") {
                    try {
                        $alertMessage = $this->DeleteStateCode($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in deleting state code data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == null) {
                    try {

                        return $this->DisplayStateCode($request);

                    } catch (Exception $e) {
                        $alertMessage = $e->getMessage();
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                }
            } catch (Exception $e) {
                ////dd($e);
                $alertMessage = "An error has occured";
                return redirect()->back()->with('alertMessage', $alertMessage);
            }
            return redirect()->back()->with('alertMessage', $alertMessage);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }

    //Post Code Actions
    public function DisplayPostCode(Request $request)
    {
        if (Auth::check() == true) {
            $path = $request->getPathInfo();
            $rowcount = 0;
            $checkNull = PostCode::get()->last();
            $lastRecord = PostCode::withTrashed()->latest('id')->first();
            $newpostCodeID = $lastRecord ? $lastRecord->id + 1 : 1;
            $searchquery = $request->input('search');
            if ($searchquery != null) {
                $postcodes = $this->searchRecords(new PostCode, $searchquery);

            } else {
                $postcodes = PostCode::all();
            }

            $postoffices = PostOffice::all();
            $statecodes = StateCode::all();
            return view($path, ['postcodes' => $postcodes, 'rowcount' => $rowcount, 'newpostCodeID' => $newpostCodeID, 'postoffices' => $postoffices, 'statecodes' => $statecodes]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function EditPostCode($request)
    {
        try {
            $editarray = json_decode($request->input('rowcount'), true);
            foreach ($editarray as $i) {
                try {
                    $request->validate([
                        'postcodeid' . $i => 'required',
                        'postcode' . $i => 'required',
                        'location' . $i => 'required',
                    ]);
                } catch (Exception $e) {
                    return $alertMessage = "All fields must be filled in";
                }
                $id = $request->input('postcodeid' . $i);
                $postCode = $request->input('postcode' . $i);
                $location = $request->input('location' . $i);
                $officeName = $request->input('officename' . $i);
                $postOfficeID = PostOffice::where('name', $officeName)->first()->id;
                $stateName = $request->input('statename' . $i);
                $stateID = StateCode::where('name', $stateName)->first()->id;
                $postCodeEdit = PostCode::where('id', $id)->first();
                $postCodeEdit->postcode = $postCode;
                $postCodeEdit->location = $location;
                $postCodeEdit->postOfficeID = $postOfficeID;
                $postCodeEdit->stateCodeID = $stateID;
                $postCodeEdit->update();
            }
            return $alertMessage = "Post code data successfully updated";
        } catch (Exception $e) {
            ////dd($e);
            return $alertMessage = "Error editing post code data";
        }
    }
    public function DeletePostCode(Request $request)
    {
        try {
            $delID = $request->get('deletebtn');
            $PostCodeToDelete = PostCode::where('id', $delID)->first();
            $PostCodeToDelete->delete();
            return $alertMessage = "Post code data deleted successfully";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function AddPostCode(Request $request)
    {
        try {
            $newPostCode = new PostCode;
            $newPostCode->id = $request->input('new-postcode-id');
            $newPostCode->postcode = $request->input('new-postcode');
            $newPostCode->location = $request->input('new-postcode-location');
            $postOfficeCode = $request->input('officename');
            $qaPostOfficeCode = $request->input('new-postoffice-name');
            if ($qaPostOfficeCode != null) {
                $this->AddPostOffice($request);
                $postOfficeCode = $qaPostOfficeCode;
            }
            $StateCode = $request->input('statename');
            $qaStateCode = $request->input('new-statecode-name');
            if ($qaStateCode != null) {
                $this->AddStateCode($request);
                $StateCode = $qaStateCode;
            }
            $postOfficeID = PostOffice::where('name', $postOfficeCode)->first()->id;
            $newPostCode->postOfficeID = $postOfficeID;
            $stateCodeID = StateCode::where('name', $StateCode)->first()->id;
            $newPostCode->stateCodeID = $stateCodeID;
            $newPostCode->save();
            return $alertMessage = "New post code data successfully added";
        } catch (Exception $e) {
            ////dd($e);
            return $e->getMessage();
        }
    }
    public function PostCodeActions(Request $request, $actionType = null)
    {
        if (Auth::check() == true) {
            $alertMessage = "";

            try {
                if ($actionType == "Edit") {
                    try {
                        $alertMessage = $this->EditPostCode($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in editing post code data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == "Add") {
                    try {
                        $alertMessage = $this->AddPostCode($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in adding post code data";
                        return redirect()->back()->with('alertMessage', $e);
                    }
                } else if ($actionType == "Delete") {
                    try {
                        $alertMessage = $this->DeletePostCode($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in deleting post code data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == null) {
                    try {

                        return $this->DisplayPostCode($request);

                    } catch (Exception $e) {
                        $alertMessage = $e->getMessage();
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                }
            } catch (Exception $e) {
                ////dd($e);
                $alertMessage = "An error has occured";
                return redirect()->back()->with('alertMessage', $alertMessage);
            }
            return redirect()->back()->with('alertMessage', $alertMessage);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }

    //Address actions
    public function getPostOffices($statename)
    {
        $stateID = StateCode::where('name', $statename)->first()->id;
        $postOffices = PostCode::where('stateCodeID', $stateID)->pluck('postOfficeID');
        $postOfficeNames = [];
        foreach ($postOffices as $postOffice) {
            $postOfficeNames[] = PostOffice::where('id', $postOffice)->pluck('name');
        }
        return response()->json($postOfficeNames);
    }
    public function getPostCode($postofficename)
    {
        $postofficeID = PostOffice::where('name', $postofficename)->first()->id;
        $postcode = PostCode::where('postOfficeID', $postofficeID)->first()->postcode;
        return response()->json($postcode);
    }
    public function DisplayAddress(Request $request)
    {

        if (Auth::check() == true) {
            $path = $request->getPathInfo();
            $rowcount = 0;
            $lastRecord = Address::withTrashed()->latest('id')->first();
            $newaddressID = $lastRecord ? $lastRecord->id + 1 : 1;
            $searchquery = $request->input('search');
            if ($searchquery != null) {
                $addresses = $this->searchRecords(new Address, $searchquery);

            } else {
                $addresses = Address::all();
            }

            $postcodes = PostCode::all();
            $postoffices = PostOffice::all();
            $stateCodes = StateCode::all();
            $users = User::all();
            $addressTypes = AddressType::all();
            return view($path, ['postcodes' => $postcodes, 'rowcount' => $rowcount, 'newaddressID' => $newaddressID, 'postoffices' => $postoffices, 'stateCodes' => $stateCodes, 'addresses' => $addresses, 'users' => $users, 'addressTypes' => $addressTypes]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function EditAddress($request)
    {
        try {
            $editarray = json_decode($request->input('rowcount'), true);
            foreach ($editarray as $i) {
                try {
                    $request->validate([
                        'addressid' . $i => 'required',
                        'addressline' . $i => 'required',
                        'street' . $i => 'required',
                        'country' . $i => 'required'
                    ]);
                } catch (Exception $e) {
                    return $alertMessage = "All fields must be filled in";
                }
                $id = $request->input('addressid' . $i);
                $addressline = $request->input('addressline' . $i);
                $street = $request->input('street' . $i);
                $country = $request->input('country' . $i);
                $postCode = $request->input('postcode' . $i);
                $postcodeID = PostCode::where('postcode', $postCode)->first()->id;
                $user = $request->input('selecteduseremail' . $i);
                $userID = User::where('email', $user)->first()->id;
                $addresstype = $request->input('addressType' . $i);
                $addressTypeID = AddressType::where('name', $addresstype)->first()->id;

                $addressEdit = Address::where('id', $id)->first();
                $addressEdit->userID = $userID;
                $addressEdit->addressTypeID = $addressTypeID;
                $addressEdit->addressLine1 = $addressline;
                $addressEdit->street = $street;
                $addressEdit->country = $country;
                $addressEdit->postCodeID = $postcodeID;
                $addressEdit->update();
            }
            return $alertMessage = "Address data successfully updated";
        } catch (Exception $e) {
            ////dd($e);
            return $alertMessage = "Error editing address data";
        }
    }
    public function DeleteAddress(Request $request)
    {
        try {
            $delID = $request->get('deletebtn');
            $AddressToDelete = Address::where('id', $delID)->first();
            $AddressToDelete->delete();
            return $alertMessage = "Address data deleted successfully";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function AddAddress(Request $request)
    {
        try {
            $newAddress = new Address;
            $newAddress->id = $request->input('new-address-id');
            $newAddress->addressLine1 = $request->input('new-address-line');
            $newAddress->street = $request->input('new-address-street');
            $newAddress->country = $request->input('new-address-country');
            $selectedAddressTypes = $request->input('selectedaddresstype');
            $qaAddressType = $request->input('new-address-type');
            if ($qaAddressType != null) {
                $this->AddAddressType($request);
                $selectedAddressTypes = $qaAddressType;
            }
            $addressTypeID = AddressType::where('name', $selectedAddressTypes)->first()->id;
            $email = $request->input('new-address-user');
            $userID = User::where('email', $email)->first()->id;
            $newAddress->userID = $userID;
            $postcode = $request->input('new-address-postcode');
            $postCodeID = PostCode::where('postcode', $postcode)->first()->id;
            $newAddress->postCodeID = $postCodeID;
            $newAddress->addressTypeID = $addressTypeID;
            $newAddress->save();
            return $alertMessage = "New address data successfully added";
        } catch (Exception $e) {
            ////dd($e);
            return $e->getMessage();
        }
    }
    public function AddressActions(Request $request, $actionType = null)
    {
        if (Auth::check() == true) {
            $alertMessage = "";
            try {
                if ($actionType == "Edit") {
                    try {
                        $alertMessage = $this->EditAddress($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in editing post code data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == "Add") {
                    try {
                        $alertMessage = $this->AddAddress($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in adding post code data";
                        return redirect()->back()->with('alertMessage', $e);
                    }
                } else if ($actionType == "Delete") {
                    try {
                        $alertMessage = $this->DeleteAddress($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in deleting post code data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == null) {
                    try {

                        return $this->DisplayAddress($request);

                    } catch (Exception $e) {
                        $alertMessage = $e->getMessage();
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                }
            } catch (Exception $e) {
                ////dd($e);
                $alertMessage = "An error has occured";
                return redirect()->back()->with('alertMessage', $alertMessage);
            }
            return redirect()->back()->with('alertMessage', $alertMessage);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }

    //Subscription Actions
    public function getActiveSubscriptions()
    {
        $totalActive = Subscription::where('status', 'Active')->count();
        return $totalActive;
    }
    public function getSubscriptionDetails($userID)
    {
        $packagepriceID = Subscription::where('userID', $userID)->first()->packagepriceID;
        return $this->getCode($packagepriceID);
    }

    public function getMostSubscribedPackage()
{
    $mostSubscribedPackage = Subscription::select('packagepriceID', DB::raw('COUNT(*) as subscriptionCount'))
        ->groupBy('packagepriceID')
        ->orderByDesc('subscriptionCount')
        ->first();

    // Retrieve the details of the most subscribed package
    $packagepriceID = $mostSubscribedPackage ? PackagePrice::find($mostSubscribedPackage->packagepriceID) : null;
    $packageID = $packagepriceID ? Package::find($packagepriceID->PackageID) : null;

    // Retrieve the count of subscriptions for the most subscribed package
    $mostSubscribedPackageDetails = $packageID ? $packageID->name : 'N/A';
    $mostSubscribedPackageCount = $mostSubscribedPackage ? $mostSubscribedPackage->subscriptionCount : 0;

    return [
        'mostSubscribedPackageDetails' => $mostSubscribedPackageDetails,
        'mostSubscribedPackageCount' => $mostSubscribedPackageCount,
    ];
}


    public function DisplaySubscription(Request $request)
    {

        if (Auth::check() == true) {
            $path = $request->getPathInfo();
            $rowcount = 0;
            $lastRecord = Subscription::withTrashed()->latest('id')->first();
            $newSubscriptionID = $lastRecord ? $lastRecord->id + 1 : 1;
            $searchquery = $request->input('search');
            if ($searchquery != null) {
                $subscriptions = $this->searchRecords(new Subscription, $searchquery);

            } else {
                $subscriptions = Subscription::all();
            }
            $transactions = Transaction::all();
            $users = User::where('RoleID', '!=', '1')->get();
            $currentDateTime = Carbon::now();
            $formattedDateTime = $currentDateTime->format('Y-m-d\TH:i');
            return view($path, ['formattedDateTime' => $formattedDateTime, 'transactions' => $transactions, 'rowcount' => $rowcount, 'newSubscriptionID' => $newSubscriptionID, 'subscriptions' => $subscriptions, 'users' => $users]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function EditSubscription($request)
    {
        try {
            $editarray = json_decode($request->input('rowcount'), true);
            foreach ($editarray as $i) {
                try {
                    $request->validate([
                        'subscriptionid' . $i => 'required',
                        'user' . $i => 'required',
                    ]);
                } catch (Exception $e) {
                    return $alertMessage = "All fields must be filled in";
                }
                $id = $request->input('subscriptionid' . $i);
                $userID = $request->input('user' . $i);
                $packagepriceID = $request->input('package' . $i);
                $code = $request->input('code' . $i);
                $datevalidfrom = $request->input('DateValidFrom' . $i);
                $datevalidto = $request->input('DateValidTo' . $i);
                $transaction = $request->input('transactionOptions' . $i);
                $amount = $request->input('amount' . $i);
                $bank = $request->input('bank' . $i);
                $status = $request->input('status' . $i);
                $subEdit = Subscription::where('id', $id)->first();
                $subEdit->userID = $userID;
                $subEdit->packagepriceID = $packagepriceID;
                $subEdit->addOnID = $code;
                $subEdit->DateValidFrom = $datevalidfrom;
                $subEdit->DateValidTo = $datevalidto;
                $subEdit->PaidAmount = $amount;
                $subEdit->TransactionID = $transaction;
                $subEdit->approvedBankName = $bank;
                $subEdit->status = $status;
                $cancelledDate = $request->input('cancelledDate' . $i);
                if ($cancelledDate != null) {
                    $subEdit->cancelledDate = $cancelledDate;
                }
                $subEdit->update();
            }
            return $alertMessage = "Subscription data successfully updated";
        } catch (Exception $e) {
            ////dd($e);
            return $alertMessage = "Error editing address data";
        }
    }
    public function DeleteSubscription(Request $request)
    {
        try {
            $delID = $request->get('deletebtn');
            $subscriptionToDelete = Subscription::where('id', $delID)->first();
            $subscriptionToDelete->delete();
            return $alertMessage = "Subscription data deleted successfully";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function AddSubscription(Request $request)
    {
        try {
            $newSubscription = new Subscription;
            $id = $request->input('new-subscription-id');
            $userID = $request->input('new-user-sub');
            $packagepriceID = $request->input('new-package');
            $code = $request->input('new-package-code');
            $datevalidfrom = $request->input('new-date-valid-from');
            $datevalidto = $request->input('new-date-valid-to');
            $transaction = $request->input('new-transaction');
            $amount = $request->input('new-amount');
            $bank = $request->input('new-bank');
            $status = $request->input('new-status');
            $newSubscription->userID = $userID;
            $newSubscription->packagepriceID = $packagepriceID;
            $newSubscription->addOnID = $code;
            $newSubscription->DateValidFrom = $datevalidfrom;
            $newSubscription->DateValidTo = $datevalidto;
            $newSubscription->PaidAmount = $amount;
            $newSubscription->TransactionID = $transaction;
            $newSubscription->approvedBankName = $bank;
            $newSubscription->status = $status;
            $cancelledDate = $request->input('new-cancelleddate');

            if ($cancelledDate != null) {
                $newSubscription->cancelledDate = $cancelledDate;
            }
            $newSubscription->save();
            return $alertMessage = "New subscription data successfully added";
        } catch (Exception $e) {
            ////dd($e);
            return $e->getMessage();
        }
    }
    public function SubscriptionActions(Request $request, $actionType = null)
    {
        if (Auth::check() == true) {
            $alertMessage = "";
            try {
                if ($actionType == "Edit") {
                    try {
                        $alertMessage = $this->EditSubscription($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in editing subscription data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == "Add") {
                    try {
                        $alertMessage = $this->AddSubscription($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in adding subscription data";
                        return redirect()->back()->with('alertMessage', $e);
                    }
                } else if ($actionType == "Delete") {
                    try {
                        $alertMessage = $this->DeleteSubscription($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in deleting subscription data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == null) {
                    try {

                        return $this->DisplaySubscription($request);

                    } catch (Exception $e) {
                        $alertMessage = $e->getMessage();
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                }
            } catch (Exception $e) {
                ////dd($e);
                $alertMessage = "An error has occured";
                return redirect()->back()->with('alertMessage', $alertMessage);
            }
            return redirect()->back()->with('alertMessage', $alertMessage);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }

    //Assignment Actions
    public function getActiveAssignments()
    {
        $activeAssignment = Assignee::where('Status', 'Active')->count();
        return $activeAssignment;
    }
    public function getAssignedFirmTotal()
    {
        $numberOfAssignedFirms = Assignee::join('firmuser', 'Assignee.assigneeID', '=', 'firmuser.id')
            ->join('firm', 'firmuser.firmID', '=', 'firm.id')
            ->where('assignee.status', 'Active') // Add a WHERE clause for the status
            ->orWhere('assignee.status', 'Completed') // Add another condition if needed
            ->distinct('firm.id')
            ->count('firm.id');
        return $numberOfAssignedFirms;
    }
    public function DisplayAssignments(Request $request)
    {

        if (Auth::check() == true) {
            $path = $request->getPathInfo();
            $rowcount = 0;
            $lastRecord = Assignee::withTrashed()->latest('id')->first();
            $newAssignmentID = $lastRecord ? $lastRecord->id + 1 : 1;
            $searchquery = $request->input('search');
            if ($searchquery != null) {
                $assignments = $this->searchRecords(new Assignee, $searchquery);

            } else {
                $assignments = Assignee::all();
            }

            $busers = BusinessUser::all();
            $firmusers = FirmUser::all();
            $currentDateTime = Carbon::now();
            $formattedDateTime = $currentDateTime->format('Y-m-d\TH:i');
            return view($path, ['formattedDateTime' => $formattedDateTime, 'busers' => $busers, 'rowcount' => $rowcount, 'newAssignmentID' => $newAssignmentID, 'assignments' => $assignments, 'firmusers' => $firmusers]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function EditAssignment($request)
    {
        try {
            $editarray = json_decode($request->input('rowcount'), true);
            foreach ($editarray as $i) {
                try {
                    $request->validate([
                        'assignmentid' . $i => 'required',
                        'buser' . $i => 'required',
                    ]);
                } catch (Exception $e) {
                    return $alertMessage = "All fields must be filled in";
                }
                $id = $request->input('assignmentid' . $i);
                $buserID = $request->input('buser' . $i);

                $assignorID = BusinessUser::where('userID', $buserID)->first()->id;
                $fuserID = $request->input('firmuser' . $i);
                $assigneeID = FirmUser::where('userID', $fuserID)->first()->id;
                $apptDateValidFrom = $request->input('DateValidFrom' . $i);
                $apptDateValidTo = $request->input('DateValidTo' . $i);
                $allowedaccesscode = $request->input('accesscode' . $i);
                $Status = $request->input('status' . $i);

                $assignmentEdit = Assignee::where('id', $id)->first();
                $assignmentEdit->AssignorID = $assignorID;
                $assignmentEdit->AssigneeID = $assigneeID;
                $assignmentEdit->appointedDateValidFrom = $apptDateValidFrom;
                $assignmentEdit->appointedDateValidTo = $apptDateValidTo;
                $assignmentEdit->allowedAccessCode = $allowedaccesscode;
                $assignmentEdit->Status = $Status;

                $assignmentEdit->update();
            }
            return $alertMessage = "Assignment data successfully updated";
        } catch (Exception $e) {
            ////dd($e);
            return $alertMessage = "Error editing assignment data";
        }
    }
    public function DeleteAssignment(Request $request)
    {
        try {
            $delID = $request->get('deletebtn');
            $assignmentToDelete = Assignee::where('id', $delID)->first();
            $assignmentToDelete->delete();
            return $alertMessage = "Assignment data deleted successfully";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function AddAssignment(Request $request)
    {
        try {
            $newAssignment = new Assignee;
            $id = $request->input('new-assignment-id');
            $buserID = $request->input('new-buser');

            $assignorID = BusinessUser::where('userID', $buserID)->first()->id;
            $fuserID = $request->input('new-fuser');
            $assigneeID = FirmUser::where('userID', $fuserID)->first()->id;
            $apptDateValidFrom = $request->input('new-date-valid-from');
            $apptDateValidTo = $request->input('new-date-valid-to');
            $allowedaccesscode = $request->input('new-access-code');
            $Status = $request->input('new-status');

            $newAssignment->AssignorID = $assignorID;
            $newAssignment->AssigneeID = $assigneeID;
            $newAssignment->appointedDateValidFrom = $apptDateValidFrom;
            $newAssignment->appointedDateValidTo = $apptDateValidTo;
            $newAssignment->allowedAccessCode = $allowedaccesscode;
            $newAssignment->Status = $Status;

            $newAssignment->save();
            return $alertMessage = "New assignment data successfully added";
        } catch (Exception $e) {
            ////dd($e);
            return $alertMessage = $e->getMessage();
        }
    }
    public function AssignmentsActions(Request $request, $actionType = null)
    {
        if (Auth::check() == true) {
            $alertMessage = "";
            try {
                if ($actionType == "Edit") {
                    try {
                        $alertMessage = $this->EditAssignment($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in editing assignment data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == "Add") {
                    try {
                        $alertMessage = $this->AddAssignment($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in adding assignment data";
                        return redirect()->back()->with('alertMessage', $e);
                    }
                } else if ($actionType == "Delete") {
                    try {
                        $alertMessage = $this->DeleteAssignment($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in deleting assignment data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == null) {
                    try {

                        return $this->DisplayAssignments($request);

                    } catch (Exception $e) {
                        $alertMessage = $e->getMessage();
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                }
            } catch (Exception $e) {
                ////dd($e);
                $alertMessage = "An error has occured";
                return redirect()->back()->with('alertMessage', $alertMessage);
            }
            return redirect()->back()->with('alertMessage', $alertMessage);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }

    //Transaction Actions
    public function getTransactionTotal()
    {
        $sumOfAmount = Transaction::where('status', 'Success')->sum('amount');
        return $sumOfAmount;
    }
    public function getTransactionDetails($transid)
    {
        $amount = Transaction::where('id', $transid)->first()->amount;
        $bank = Transaction::where('id', $transid)->first()->BankID;
        return response()->json(['bank' => $bank, 'amount' => $amount]);
    }
    public function DisplayTransaction(Request $request)
    {

        if (Auth::check() == true) {
            $path = $request->getPathInfo();
            $rowcount = 0;
            $lastRecord = Transaction::withTrashed()->latest('id')->first();
            $newTransID = $lastRecord ? $lastRecord->id + 1 : 1;
            $searchquery = $request->input('search');
            if ($searchquery != null) {
                $transactions = $this->searchRecords(new Transaction, $searchquery);

            } else {
                $transactions = Transaction::all();
            }
            return view($path, ['rowcount' => $rowcount, 'newTransID' => $newTransID, 'transactions' => $transactions]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function EditTransaction($request)
    {
        try {
            $editarray = json_decode($request->input('rowcount'), true);
            foreach ($editarray as $i) {
                try {
                    $request->validate([
                        'transactionid' . $i => 'required',
                        'transactionno' . $i => 'required',
                        'transactionname' . $i => 'required',
                        'amount' . $i => 'required',
                        'fpxid' . $i => 'required',
                        'fpxchecksum' . $i => 'required',
                        'bankid' . $i => 'required',
                        'paymenttime' . $i => 'required',
                        'status' . $i => 'required',

                    ]);
                } catch (Exception $e) {
                    return $alertMessage = "All fields must be filled in";
                }
                $id = $request->input('transactionid' . $i);
                $transactionno = $request->input('transactionno' . $i);
                $transactionname = $request->input('transactionname' . $i);
                $amount = $request->input('amount' . $i);
                $fpxid = $request->input('fpxid' . $i);
                $fpxchecksum = $request->input('fpxchecksum' . $i);
                $bankid = $request->input('bankid' . $i);
                $paymenttime = $request->input('paymenttime' . $i);
                $status = $request->input('status' . $i);
                $transEdit = Transaction::where('id', $id)->first();
                $transEdit->id = $id;
                $transEdit->transactionNo = $transactionno;
                $transEdit->name = $transactionname;
                $transEdit->amount = $amount;
                $transEdit->FPX_ID = $fpxid;
                $transEdit->BankID = $bankid;
                $transEdit->FPX_CheckSum = $fpxchecksum;
                $transEdit->paymentDateTime = $paymenttime;
                $transEdit->status = $status;
                $transEdit->update();
            }
            return $alertMessage = "Transaction data successfully updated";
        } catch (Exception $e) {
            ////dd($e);
            return $alertMessage = "Error editing transaction data";
        }
    }
    public function DeleteTransaction(Request $request)
    {
        try {
            $delID = $request->get('deletebtn');
            $TransactionToDelete = Transaction::where('id', $delID)->first();
            $TransactionToDelete->delete();
            return $alertMessage = "Transaction data deleted successfully";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function AddTransaction(Request $request)
    {
        try {
            $newtransaction = new Transaction;
            $newtransaction->id = $request->input('new-trans-id');
            $newtransaction->transactionNo = $request->input('new-trans-no');
            $newtransaction->name = $request->input('new-trans-name');
            $newtransaction->amount = $request->input('new-trans-amount');
            $newtransaction->FPX_ID = $request->input('new-trans-id');
            $newtransaction->BankID = $request->input('new-trans-fpxid');
            $newtransaction->FPX_CheckSum = $request->input('new-trans-fpxchecksum');
            $newtransaction->paymentDateTime = $request->input('new-trans-bankid');
            $newtransaction->status = $request->input('new-trans-status');
            $newtransaction->paymentDateTime = Carbon::now()->toDateTimeString();
            $newtransaction->save();
            return $alertMessage = "New transaction data successfully added";
        } catch (Exception $e) {
            ////dd($e);
            return $e->getMessage();
        }
    }
    public function TransactionActions(Request $request, $actionType = null)
    {
        if (Auth::check() == true) {
            $alertMessage = "";
            try {
                if ($actionType == "Edit") {
                    try {
                        $alertMessage = $this->EditTransaction($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in editing transaction data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == "Add") {
                    try {
                        $alertMessage = $this->AddTransaction($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in adding transaction data";
                        return redirect()->back()->with('alertMessage', $e);
                    }
                } else if ($actionType == "Delete") {
                    try {
                        $alertMessage = $this->DeleteTransaction($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in deleting transaction data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == null) {
                    try {

                        return $this->DisplayTransaction($request);

                    } catch (Exception $e) {
                        $alertMessage = $e->getMessage();
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                }
            } catch (Exception $e) {
                ////dd($e);
                $alertMessage = "An error has occured";
                return redirect()->back()->with('alertMessage', $alertMessage);
            }
            return redirect()->back()->with('alertMessage', $alertMessage);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }

    //Financial Records Actions
    public function DisplayFinancialRecords(Request $request)
    {
        if (Auth::check() == true) {
            $path = $request->getPathInfo();

            $rowcount = 0;
            $checkNull = FinancialRecord::get()->last();
            $lastRecord = FinancialRecord::withTrashed()->latest('id')->first();
            $newFRecordID = $lastRecord ? $lastRecord->id + 1 : 1;
            $searchquery = $request->input('search');
            if ($searchquery != null) {
                $frecords = $this->searchRecords(new FinancialRecord, $searchquery);

            } else {
                $frecords = FinancialRecord::all();
            }

            $businesses = Business::all();
            $currentDateTime = Carbon::now();
            $formattedDateTime = $currentDateTime->format('Y-m-d\TH:i');
            return view($path, ['frecords' => $frecords, 'rowcount' => $rowcount, 'newFRecordID' => $newFRecordID, 'businesses' => $businesses, 'formattedDateTime' => $formattedDateTime]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }
    public function EditFinancialRecord($request)
    {

        try {
            $editarray = json_decode($request->input('rowcount'), true);
            foreach ($editarray as $i) {
                try {
                    $request->validate([
                        'frecordid' . $i => 'required',
                        'businessname' . $i => 'required',
                        'recordcategory' . $i => 'required',
                        'amount' . $i => 'required',
                        'description' . $i => 'required',
                        'recordtime' . $i => 'required'
                    ]);
                } catch (Exception $e) {
                    return $alertMessage = "All fields must be filled in";
                }
                $id = $request->input('frecordid' . $i);
                $amount = $request->input('amount' . $i);
                $description = $request->input('description' . $i);
                $businessname = $request->input('businessname' . $i);
                $businessID = Business::where('businessName', $businessname)->first()->id;
                $recordcategory = $request->input('recordcategory' . $i);
                $recordedtime = $request->input('recordtime' . $i);
                $fRecordEdit = FinancialRecord::where('id', $id)->first();
                $fRecordEdit->amount = $amount;
                $fRecordEdit->description = $description;
                $fRecordEdit->businessID = $businessID;
                $fRecordEdit->recordcategory = $recordcategory;
                $fRecordEdit->recordedtime = $recordedtime;
                $fRecordEdit->update();
            }
            return $alertMessage = "Financial record data successfully updated";
        } catch (Exception $e) {
            ////dd($e);
            return $alertMessage = "Error editing financial record data";
        }
    }
    public function DeleteFinancialRecord(Request $request)
    {
        try {
            $delID = $request->get('deletebtn');
            $FinancialRecordToDelete = FinancialRecord::where('id', $delID)->first();
            $FinancialRecordToDelete->delete();
            return $alertMessage = "Financial record data deleted successfully";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function AddFinancialRecord(Request $request)
    {
        try {
            $newFRecord = new FinancialRecord;
            $newFRecord->id = $request->input('new-frecord-id');
            $newFRecord->recordcategory = $request->input('new-recordcategory');
            $newFRecord->amount = $request->input('new-amount');
            $newFRecord->description = $request->input('new-description');
            $newFRecord->recordedtime = $request->input('new-recordtime');
            $businessname = $request->input('new-business-name');
            $businessID = Business::where('businessName', $businessname)->first()->id;
            $newFRecord->businessID = $businessID;
            $newFRecord->save();
            return $alertMessage = "New financial record data successfully added";
        } catch (Exception $e) {
            ////dd($e);
            return $e->getMessage();
        }
    }
    public function FinancialRecordsActions(Request $request, $actionType = null)
    {
        if (Auth::check() == true) {
            $alertMessage = "";

            try {
                if ($actionType == "Edit") {
                    try {
                        $alertMessage = $this->EditFinancialRecord($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in editing financial record data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == "Add") {
                    try {

                        $alertMessage = $this->AddFinancialRecord($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in adding financial record data";
                        return redirect()->back()->with('alertMessage', $e);
                    }
                } else if ($actionType == "Delete") {
                    try {
                        $alertMessage = $this->DeleteFinancialRecord($request);
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    } catch (Exception $e) {
                        $alertMessage = "Error in deleting financial record data";
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                } else if ($actionType == null) {
                    try {

                        return $this->DisplayFinancialRecords($request);

                    } catch (Exception $e) {
                        $alertMessage = $e->getMessage();
                        return redirect()->back()->with('alertMessage', $alertMessage);
                    }
                }
            } catch (Exception $e) {
                ////dd($e);
                $alertMessage = "An error has occured";
                return redirect()->back()->with('alertMessage', $alertMessage);
            }
            return redirect()->back()->with('alertMessage', $alertMessage);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }

    //Recycle Bin Actions
    public function DisplayBin(Request $request)
    {
        if (Auth::check() == true) {
            $path = $request->getPathInfo();
            $softDeletedItems = [];

            // Get all tables from the database
            $tables = Schema::getAllTables();

            $allAttributes = [];

            foreach ($tables as $table) {
                // Access the table name directly
                $tableName = $table->{'Tables_in_accountingpac'};

                // Get the model name
                $modelName = '\\App\\Models\\' . Str::studly(Str::singular($tableName));

                // Check if the model class exists and uses SoftDeletes
                if (class_exists($modelName) && in_array(SoftDeletes::class, class_uses($modelName))) {
                    // Retrieve soft-deleted items for the model
                    $items = $modelName::onlyTrashed()->get();

                    // Check if the collection is not empty before getting attributes
                    if (!$items->isEmpty()) {
                        $allAttributes[$modelName] = array_keys($items->first()->getAttributes());
                    }

                    $softDeletedItems[$modelName] = $items;
                }
            }

            // Remove duplicate attributes
            foreach ($allAttributes as &$attributes) {
                $attributes = array_unique($attributes);
            }

            return view($path, ['softDeletedItems' => $softDeletedItems, 'allAttributes' => $allAttributes]);
        } else {
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']);
        }
    }






    public function RestoreDeletedItems($modelName, $id)
    {
        try {
            $modelClass = $modelName;

            if (class_exists($modelClass) && in_array(SoftDeletes::class, class_uses($modelClass))) {
                $restoredItem = $modelClass::withTrashed()->findOrFail($id);

                $restoredItem->restore();

                $alertMessage = "Item restored successfully";
                return redirect()->back()->with('alertMessage', $alertMessage);
            }
        } catch (Exception $e) {
            return $alertMessage = $e->getMessage();
        }


    }

    //Search Actions



    protected function searchRecords($model, $searchQuery)
    {
        $columns = $this->getColumnsWithRelations($model);

        return $model->where(function ($query) use ($columns, $searchQuery) {
            foreach ($columns as $column) {
                // Check if the column includes a dot (.) to determine if it's from a related table
                if (strpos($column, '.') !== false) {
                    [$relation, $column] = explode('.', $column);

                    // Use whereHas to filter based on the related column
                    $query->orWhereHas($relation, function ($q) use ($column, $searchQuery) {
                        $q->where($column, 'LIKE', '%' . $searchQuery . '%');
                    });
                } else {
                    // Regular where for columns from the main table
                    $query->orWhere($column, 'LIKE', '%' . $searchQuery . '%');
                }
            }
        })->get();
    }

    // Your other methods...

    protected function getColumnsWithRelations($model)
    {
        $columns = Schema::getColumnListing($model->getTable());

        // Include columns from related tables
        $relations = $this->getRelations($model);
        foreach ($relations as $relation) {
            $relatedTable = $model->{$relation}()->getRelated()->getTable();
            $relatedColumns = Schema::getColumnListing($relatedTable);

            // Append the relation name to each related column
            $relatedColumns = array_map(function ($column) use ($relation) {
                return $relation . '.' . $column;
            }, $relatedColumns);

            $columns = array_merge($columns, $relatedColumns);
        }

        return $columns;
    }

    protected function getRelations($model)
    {
        return $model::getRelationships();
    }
}
