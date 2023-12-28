<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\AddressType;
use App\Models\BusinessUser;
use App\Models\FirmType;
use App\Models\HonorificCode;

use App\Models\Package;
use App\Models\PackageBase;
use App\Models\PostCode;
use App\Models\PostOffice;
use App\Models\Role;
use App\Models\BusinessType;
use App\Models\Business;
use App\Models\StateCode;
use Auth;
use Exception;
use Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Storage;

class ActionController extends Controller 
{
    public function storeImage($file){
        if ($file) {
            $path = $file->store('public/logos');
            $imgName = str_replace('public/logos','',$path);
            return $imgName;
        } 
    }
    public function LoadPage(Request $request){
        
        if(Auth::check() == true){
            $path = $request->getPathInfo();
            return view($path);
        }
        else{
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
        }
    }
    
    public function ShowAddBusinessUser(){
        if(Auth::check() == true){
            
           return view('Pages.Admin-Business.addbusinessuser');
        }
            
        
        else{
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
        }
    }
    public function ShowAddFirmUser(){
        if(Auth::check() == true){
            
           return view('Pages.addfirmuser');
        }
            
        
        else{
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
        }
    }
    public function LoginService(Request $request){
        $usernameData = $request->input('username');
        $passwordData = $request->input('password');
       
        $confirmPasswordData= $request->input('confirmpassword');
        if($passwordData != $confirmPasswordData){
            return Redirect::back()->withErrors(['msg' => 'Passwords does not match!']);
        }
        else{
            $user = User::where('email', $usernameData)->first();
            
            if(Hash::check($passwordData, $user->password)){
                Auth::login($user);
                return redirect()->route('toindex')->with('sessionUsername',$usernameData);
            }
            else{
                Auth::logout();
                session(['loginSession'=>false]);
                return redirect()->route('toLogin')->withErrors(['msg' => 'Invalid admin credentials!']); 
            }
        }
    }
    public function LogoutService(){
        Auth::logout();
        return view('Pages.adminlogin');
    }
    
    //User Services
    public function UserProfileActions(Request $request, $actionType){
        if(Auth::check() == true){    
        $alertMessage ="";
        if($actionType == 'Edit'){
        $rowcount = $request -> input('rowcount');
        for($x = 0; $x <=$rowcount; $x++){
            $fname = $request->input('fname'.$x);
            $lname = $request->input('lname'.$x);
            $email = $request->input('email'.$x);
            $password = $request->input('password'.$x);
            $contact = $request->input('contact'.$x);

        try{
        $request->validate([
        'fname'.$x => 'required',
        'lname'.$x => 'required',
        'email'.$x => 'required',
        'password'.$x => 'required',
        'contact'.$x => 'required'
        ]);
        }
        catch(Exception $e){
            $alertMessage = "All fields must be filled in";
            return redirect()->back()->with('alertMessage',$alertMessage);
        }
                
        $id = $request ->input('id'.$x);
        $userData = User::where('id',$id)-> first();
        $userData->FirstName = $fname;
        $userData->LastName = $lname;
        $userData->email = $email;
        $userData->password = $password;
        $userData->contactNo = $contact;
        $selectedhCode = $request -> input('hCode'.$x);
        $selectedRole = $request -> input('role'.$x);
        $hCodeID = HonorificCode::where('CodeName',$selectedhCode)->first()->id;
        $roleID = Role::where('name',$selectedRole)->first()->id;
        $userData -> HonorificCodeID = $hCodeID;
        $userData -> RoleID = $roleID;
        $userData->updated_at = Carbon::now()->toDateTimeString();
        $userData->update();
        }
        $alertMessage = "User data has been successfully updated";
        }
        else if($actionType == "Delete"){
        $delID = $request->get('deletebtn');
        $userToDelete = User::where('id',$delID)-> first();
        $userToDelete->delete();  
        $alertMessage = "User data has been successfully deleted";  
        }
        else if($actionType == "Add"){
            try{
            $this->AddNewUser($request);
            $alertMessage = "User data has been successfully added";
            }
            catch(Exception $e){
                $alertMessage = "Error adding user";
            }
        }
        else{
            $alertMessage = "An error has occurred";
            return redirect()->back()->with('updateError',$alertMessage);
        }
        return redirect()->back()->with('alertMessage',$alertMessage);
        }
             
         else{
             return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
         }
        
    }
    public function DisplayUsers(Request $request,$searchType){
        if(Auth::check() == true){
            $rowCount = 0;
            $usersData = User::all();
            $lastRecord = User::withTrashed()->latest('id')->first();
            $newUserID = $lastRecord ? $lastRecord->id + 1 : 1;
            if($searchType == 'onlineusers'){
            session_start();
            $_SESSION["latestUsersData"] =  $usersData =  User::where('Status','Online')->get();
            }
            else if($searchType == 'allusers'){
                $_SESSION["latestUsersData"] =  $usersData =  User::all();
            }
            else if($searchType == 'offlineusers'){
                $_SESSION["latestUsersData"] = $usersData =  User::where('Status','Offline')->get();
            }
            $usersData = $usersData->where('RoleID','!=','1');
            $roleNames =Role::where('id','!=','1')->get('name'); 
            $hCodeNames = HonorificCode::where('id','!=','1')->get('CodeName');
            return view('Pages.Admin-User.displayusers',
            ['usersData' => $usersData, 'roleNames' => $roleNames, 'hCodeNames' => $hCodeNames, 'rowcount' => $rowCount, 'newUserID' => $newUserID]);
        }
        else{
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
        }
    }
    public function AddNewUser(Request $request){
        if(Auth::check() == true){
            try{
            $request -> validate([
                'newuser-id' => 'required',
                'newuser-fname' => 'required',
                'newuser-lname' => 'required',
                'newuser-email' => 'required',
                'newuser-password' => 'required',
                'newuser-contact' => 'required'
            ]);  
            $userData = new User;
            $userData->id = $request->input('newuser-id');
            $userData->FirstName = $request->input('newuser-fname');
            $userData->LastName = $request->input('newuser-lname');
            $userData->email = $request->input('newuser-email');
            $userData->password = $request->input('newuser-password');
            $userData->contactNo = $request->input('newuser-contact');
            $selectedhCode = $request -> hCodeChoice2;
            $selectedRole = $request -> roleChoice2;
            $newHCode = $request -> input('newHcode');
            if($newHCode !=null){
                $this->AddHcode($request);
                $selectedhCode = $newHCode;
            }
            $newRole = $request-> input('newRole');
            if($newRole !=null){
                $this->AddRoles($request);
                $selectedRole = $newRole;
            }
            $hCodeID = HonorificCode::where('CodeName',$selectedhCode)->first()->id;
            $roleID = Role::where('name',$selectedRole)->first()->id;
            $userData -> HonorificCodeID = $hCodeID;
            $userData -> Status = "Offline";
            $userData -> RoleID = $roleID;
            $userData->save();
            return redirect()->back()->with('alertMessage',"User data saved successfully");
            }
            catch(Exception $e){
                return redirect()->back()->with('alertMessage',$e);
            }
           
        }
        else{
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
        }
    }

    //Admin Services
    public function UpdateProfile(Request $request){
        $adminData = User::where('id',Auth::user()->id)->first();
        $adminData->FirstName = $request->input('fname');
        $adminData->LastName = $request->input('lname');
        $adminData->email = $request->input('email');
        $adminData->password = $request->input('password');
        $adminData->contactNo = $request->input('contactNo');
        $adminData->updated_at = Carbon::now()->toDateTimeString();
        $adminData->update();
        return redirect()->route('toprofile');
    }
    public function LoadProfile(Request $request){
        if(Auth::check() == true){
            $userData = User::where('id',Auth::user()->id)->first();
            $path = $request->getPathInfo();
            return view($path,['userData' => $userData]);
        }
        else{
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
        }
    }
    public function EditProfile(Request $request){
        if(Auth::check() == true){
            $userData = User::where('id',Auth::user()->id)->first();
            $path = $request->getPathInfo();
            return view($path,['userData' => $userData]);
        }
        else{
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
        }
    }
//Business Types Services


public function DisplayBusinessType(Request $request){
    if(Auth::check() == true){
        $path = $request->getPathInfo();    
        $rowCount = 0;
        $lastRecord = BusinessType::withTrashed()->latest('id')->first();
        $bTypeID = $lastRecord ? $lastRecord->id + 1 : 1;
        $bTypes = BusinessType::all();
        return view($path,['bTypes' => $bTypes, 'rowCount' => $rowCount,'bTypeID' => $bTypeID]);
        }
        else{
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
        }
}
public function EditBusinessType($request){
    try{
        $count = $request->input('rowcount');
        for($i=0;$i<=$count;$i++){
            try{
                $request->validate([
                    'bTypeID'.$i =>'required',
                    'bTypeName'.$i =>'required'
                ]);
            }
            catch(Exception $e){
                return $alertMessage = "All fields must be filled in";
            }
            $id = $request->input('bTypeID'.$i);
            $bTypeName = $request->input('bTypeName'.$i);
            $bTypeEdit = BusinessType::where('id', $id)->first();
            $bTypeEdit->businessTypeName = $bTypeName;
            $bTypeEdit->update();
        }
        return $alertMessage = "Business Type data successfully updated";
    }
    catch(Exception $e){
        dd($e);
        return $alertMessage = "Error editing business type data";
    }
}
public function DeleteBusinessType(Request $request){
    try{
        $delID = $request->get('deletebtn');
        $BusinessTypeToDelete = BusinessType::where('id',$delID)-> first();
        $BusinessTypeToDelete->delete();
        return $alertMessage = "Business Type data deleted successfully";
        }
        catch(Exception $e){
            return $e->getMessage();
        }
}
public function AddBusinessType(Request $request){
    try{
    $lastRecord = BusinessType::withTrashed()->latest('id')->first();
    $bTypeID = $lastRecord ? $lastRecord->id + 1 : 1;
    $newBusinessType = new BusinessType;
    $newBusinessType->id =$bTypeID;
    $newBusinessType->businessTypeName = $request->input('new-business-type');
    $newBusinessType->save();
    return $alertMessage = "New business type data successfully added";
    }
    catch(Exception $e){
        return $e->getMessage();
    }
}
public function BusinessTypeActions(Request $request,$actionType){
    if(Auth::check() == true){
        $alertMessage = "";
        try{
            if($actionType == "Edit"){
                try{
                    $alertMessage = $this-> EditBusinessType($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in editing business type data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                } 
            }
            else if($actionType == "Add"){
                try{
                $alertMessage = $this-> AddBusinessType($request);
                return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in adding business type data";
                    return redirect()->back()->with('alertMessage',$e);
                }
            }
            else if($actionType = "Delete"){
                try{
                    $alertMessage = $this-> DeleteBusinessType($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in deleting business type data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
            }
        }
        
        catch(Exception $e){
            dd($e);
            $alertMessage = "An error has occured";
            return redirect()->back()->with('alertMessage',$alertMessage);
        }
        return redirect()->back()->with('alertMessage', $alertMessage);
    }
    else{
        return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
    }
}


//Business Services
    public function ShowRegBusiness(Request $request){
        if(Auth::check() == true){
        $path = $request->getPathInfo();
        $rowCount = 0;
        $lastRecord = Business::withTrashed()->latest('id')->first();
        $newBusinessData = $lastRecord ? $lastRecord->id + 1 : 1;
        $businessData = Business::all();
        $businessTypes = BusinessType::all();
        $checkNull2 = BusinessType::get()->last();
        if($checkNull2){
            $newBusinessTypeData = 1;
        }
        else{
            $newBusinessTypeData = BusinessType::get()->last()->id+1;
        }
        return view($path,['businessData' => $businessData, 'businessTypes' => $businessTypes, 'newBusinessData' => $newBusinessData,'newBusinessTypeData' => $newBusinessTypeData,'rowCount' => $rowCount]);
        }
        else{
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
        }
    }
    public function editBusiness(Request $request){
        try{
    
            $count = $request->input('rowcount');
            for($i=0;$i<=$count;$i++){
                try{
                    $request->validate([
                        'business-id'.$i => 'required',
                        'businessTypes'.$i => 'required',
                        'business-name'.$i => 'required',
                        'business-contact'.$i => 'required',
                        'business-email'.$i => 'required'
                    ]);
                }
                catch(Exception $e){
                    return $alertMessage = "All fields must be filled in";
                }
            $id = $request->input('business-id'.$i);
            $bTypeID = $request->input('businessTypes'.$i);
            $businessType = BusinessType::where('businessTypeName',$bTypeID)->first()->id;
            $businessName = $request->input('business-name'.$i);
            $Contact = $request->input('business-contact'.$i);
            $email = $request->input('business-email'.$i);
            $editedLogo = $request->file('edited-logo'.$i);
            $bEdit= Business::where('id', $id)->first();
            $bEdit->businessName = $businessName;
            $bEdit->businessType = $businessType;
            $bEdit->Contact = $Contact;
            $bEdit->email = $email;
            if($editedLogo){
                $logo = $this->storeImage($editedLogo);
                $oldLogo = $request-> input('oldlogosrc'.$i);
                Storage::delete($oldLogo);
                $bEdit->logo = $logo;
            }
            $bEdit->update();
            }
            return $alertMessage = "Business data successfully updated";
        }
        catch(Exception $e){
            dd($e);
            return $alertMessage = "Error editing business data";
        }
    }
    public function deleteBusiness(Request $request){
    $delID = $request->get('deletebtn');
    $userToDelete = Business::where('id',$delID)-> first();
    $userToDelete->delete();
    return $alertMessage = "Business data deleted successfully";
    }
    public function BusinessActions(Request $request,$actionType){
        if(Auth::check() == true){
            $alertMessage = "";
            try{
                if($actionType == "Edit"){
                    try{
                        $alertMessage = $this-> editBusiness($request);
                        return redirect()->back()->with('alertMessage',$alertMessage);
                    }
                    catch(Exception $e){
                        $alertMessage = "Error in editing business data";
                        return redirect()->back()->with('alertMessage',$alertMessage);
                    } 
                }
                else if($actionType == "Add"){
                    try{
                    $alertMessage = $this-> addBusiness($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                    }
                    catch(Exception $e){
                        $alertMessage = "Error in adding business data";
                        return redirect()->back()->with('alertMessage',$e);
                    }
                }
                else if($actionType = "Delete"){
                    try{
                        $alertMessage = $this-> deleteBusiness($request);
                        return redirect()->back()->with('alertMessage',$alertMessage);
                    }
                    catch(Exception $e){
                        $alertMessage = "Error in deleting business data";
                        return redirect()->back()->with('alertMessage',$alertMessage);
                    }
                }
            }
            
            catch(Exception $e){
                dd($e);
                $alertMessage = "An error has occured";
                return redirect()->back()->with('alertMessage',$alertMessage);
            }
            return redirect()->back()->with('alertMessage', $alertMessage);
        }
        else{
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
        }
    }
    public function addBusiness(Request $request){
        if(Auth::check() == true){
            $id = $request->input('business-id');
            $bTypeName = $request->input('businessTypeChoice');
            $bTypeName2 = $request->input('new-business-type');
            if($bTypeName2){
                $this->AddBusinessType($request);
                $bTypeName = $bTypeName2;
            }
            $businessType = BusinessType::where('businessTypeName', $bTypeName)->first()->id;
            $businessName = $request->input('business-name');
            $Contact = $request->input('business-contact');
            $email = $request->input('business-email');
            $image = $request->file('business-logo');
            $logo = "";
            if($image){
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
        }
         else{
             return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
         }
        
        
    }

//Role Services
    public function DisplayRoles(Request $request){
        if(Auth::check() == true){
        $path = $request->getPathInfo();
        $rowCount = 0;
        $lastRecord = Role::withTrashed()->latest('id')->first();
        $newRoleID = $lastRecord ? $lastRecord->id + 1 : 1;
        $roles = Role::where('id','!=','1')->get();
        return view($path,['roles' => $roles, 'rowCount' => $rowCount,'newRoleID' => $newRoleID]);
        }
        else{
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
        }
    }
    public function EditRole($request){
        try{
            $count = $request->input('rowcount');
            for($i=0;$i<=$count;$i++){
                try{
                    $request->validate([
                        'roleid'.$i =>'required',
                        'rolename'.$i =>'required'
                    ]);
                }
                catch(Exception $e){
                    return $alertMessage = "All fields must be filled in";
                }
            $id = $request->input('roleid'.$i);
            $roleName = $request->input('rolename'.$i);
            $roleEdit = Role::where('id', $id)->first();
            $roleEdit->name = $roleName;
            $roleEdit->update();
            }
            return $alertMessage = "Role data successfully updated";
        }
        catch(Exception $e){
            dd($e);
            return $alertMessage = "Error editing role data";
        }
    }
    public function DeleteRole(Request $request){
        try{
        $delID = $request->get('deletebtn');
        $roleToDelete = Role::where('id',$delID)-> first();
        $roleToDelete->delete();
        return $alertMessage = "Role data deleted successfully";
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function AddRoles(Request $request){
        $newRole = new Role;
        $newRole -> id = Role::get()->last()->id+1;
        $newRole -> name = $request-> input('newRole');
        $newRole -> save();
    }
    public function RoleActions(Request $request, $actionType){
        if(Auth::check() == true){
            $alertMessage="";
            if($actionType == "Edit"){
                try{
                    $request -> validate([
                        'rolename' => 'required',
                    ]);
                    $alertMessage = $this->EditRole($request);
                    return redirect()->back()->with('alertMessage', $alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = $e-> getMessage();
                    return redirect()->back()->with('alertMessage', $alertMessage);
                }
            }
            else if($actionType == "Delete"){
                $alertMessage = $this->DeleteRole($request);
                return redirect()->back()->with('alertMessage', $alertMessage);
            }
            else if($actionType == "Add"){
                try{
                    $request -> validate([
                        'newRole' => 'required',
                    ]);
                $this->AddRoles($request);
                $alertMessage = "New role data successfully added";
                return redirect()->back()->with('alertMessage', $alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = $e-> getMessage();
                    return redirect()->back()->with('alertMessage', $alertMessage);
                }
            }
        }
        else{
                return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
            }
    }
        
// Honorific Code Services
public function DisplayHCodes(Request $request){
    if(Auth::check() == true){
        $path = $request->getPathInfo();
        $rowCount = 0;
        $lastRecord = HonorificCode::withTrashed()->latest('id')->first();
        $newHcodeID = $lastRecord ? $lastRecord->id + 1 : 1;
        $hcodes = HonorificCode::where('id','!=','1')->get();
        return view($path,['hcodes' => $hcodes, 'rowCount' => $rowCount,'newHcodeID' => $newHcodeID]);
        }
        else{
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
        }
}
public function EditHCode($request){
    try{
        $count = $request->input('rowcount');
        for($i=0;$i<=$count;$i++){
            try{
                $request->validate([
                    'hcodeID'.$i =>'required',
                    'hCodeName'.$i =>'required'
                ]);
            }
            catch(Exception $e){
                return $alertMessage = "All fields must be filled in";
            }
        $id = $request->input('hcodeID'.$i);
        $HonorificCodeName = $request->input('hCodeName'.$i);
        $HonorificCodeEdit = HonorificCode::where('id', $id)->first();
        $HonorificCodeEdit->CodeName = $HonorificCodeName;
        $HonorificCodeEdit->update();
        }
        return $alertMessage = "Honorific code data successfully updated";
    }
    catch(Exception $e){
        dd($e);
        return $alertMessage = "Error editing honorific code data";
    }
}
public function DeleteHonorificCode(Request $request){
    try{
        $delID = $request->get('deletebtn');
        $HonorificCodeToDelete = HonorificCode::where('id',$delID)-> first();
        $HonorificCodeToDelete->delete();
        return $alertMessage = "Honorific Code data deleted successfully";
        }
        catch(Exception $e){
            return $e->getMessage();
        }
}
public function AddHcode(Request $request){
    try{
    $newHCode = new HonorificCode;
    $newHCode -> id = HonorificCode::get()->last()->id+1;
    $newHCode -> CodeName = $request-> input('newHcode');
    $newHCode -> save();
    return $alertMessage = "New honorific code data successfully added";
    }
    catch(Exception $e){
        return $e->getMessage();
    }
}
public function HcodeActions(Request $request,$actionType){
    if(Auth::check() == true){
        $alertMessage = "";
        try{
            if($actionType == "Edit"){
                try{
                    $alertMessage = $this-> EditHCode($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in editing honorific code data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                } 
            }
            else if($actionType == "Add"){
                try{
                $alertMessage = $this-> AddHcode($request);
                return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in adding honorific code data";
                    return redirect()->back()->with('alertMessage',$e);
                }
            }
            else if($actionType = "Delete"){
                try{
                    $alertMessage = $this-> DeleteHonorificCode($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in deleting honorific code data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
            }
        }
        
        catch(Exception $e){
            dd($e);
            $alertMessage = "An error has occured";
            return redirect()->back()->with('alertMessage',$alertMessage);
        }
        return redirect()->back()->with('alertMessage', $alertMessage);
    }
    else{
        return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
    }
}

//Address Type Services
public function DisplayAddressType(Request $request){
    if(Auth::check() == true){
        $path = $request->getPathInfo();
        $rowCount = 0;
        $lastRecord = AddressType::withTrashed()->latest('id')->first();
        $AddressTypeID = $lastRecord ? $lastRecord->id + 1 : 1;
        $AddressTypes = AddressType::all();
        return view($path,['AddressTypes' => $AddressTypes, 'rowCount' => $rowCount,'AddressTypeID' => $AddressTypeID]);
        }
        else{
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
        }
}
public function EditAddressType($request){
    try{
        $count = $request->input('rowcount');
        for($i=0;$i<=$count;$i++){
            try{
                $request->validate([
                    'addressTypeID'.$i =>'required',
                    'addressTypeName'.$i =>'required'
                ]);
            }
            catch(Exception $e){
                return $alertMessage = "All fields must be filled in";
            }
            $id = $request->input('addressTypeID'.$i);
            $addressTypeName = $request->input('addressTypeName'.$i);
            $addressTypeEdit = AddressType::where('id', $id)->first();
            $addressTypeEdit->name = $addressTypeName;
            $addressTypeEdit->update();
        }
        return $alertMessage = "Address Type data successfully updated";
    }
    catch(Exception $e){
        dd($e);
        return $alertMessage = "Error editing address type data";
    }
}
public function DeleteAddressType(Request $request){
    try{
        $delID = $request->get('deletebtn');
        $addressTypeToDelete = AddressType::where('id',$delID)-> first();
        $addressTypeToDelete->delete();
        return $alertMessage = "Address Type data deleted successfully";
        }
        catch(Exception $e){
            return $e->getMessage();
        }
}
public function AddAddressType(Request $request){
    try{
    $newAddressType = new AddressType;
    $newAddressType->id =AddressType::get()->last()->id+1;
    $newAddressType->name = $request->input('new-address-type');
    $newAddressType->save();
    return $alertMessage = "New address type data successfully added";
    }
    catch(Exception $e){
        return $e->getMessage();
    }
}
public function AddressTypeActions(Request $request,$actionType){
    if(Auth::check() == true){
        $alertMessage = "";
        try{
            if($actionType == "Edit"){
                try{
                    $alertMessage = $this-> EditAddressType($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in editing business type data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                } 
            }
            else if($actionType == "Add"){
                try{
                $alertMessage = $this-> AddAddressType($request);
                return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in adding business type data";
                    return redirect()->back()->with('alertMessage',$e);
                }
            }
            else if($actionType = "Delete"){
                try{
                    $alertMessage = $this-> DeleteAddressType($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in deleting business type data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
            }
        }
        
        catch(Exception $e){
            dd($e);
            $alertMessage = "An error has occured";
            return redirect()->back()->with('alertMessage',$alertMessage);
        }
        return redirect()->back()->with('alertMessage', $alertMessage);
    }
    else{
        return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
    }
}

//Firm Type Actions
public function DisplayFirmType(Request $request){
    if(Auth::check() == true){
        $path = $request->getPathInfo();
        $rowCount = 0;
        $lastRecord = FirmType::withTrashed()->latest('id')->first();
        $FirmTypeID = $lastRecord ? $lastRecord->id + 1 : 1;
        $FirmTypes = FirmType::all();
        return view($path,['FirmTypes' => $FirmTypes, 'rowCount' => $rowCount,'FirmTypeID' => $FirmTypeID]);
        }
        else{
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
        }
}
public function EditFirmType($request){
    try{
        $count = $request->input('rowcount');
        for($i=0;$i<=$count;$i++){
            try{
                $request->validate([
                    'firmTypeID'.$i =>'required',
                    'firmTypeName'.$i =>'required'
                ]);
            }
            catch(Exception $e){
                return $alertMessage = "All fields must be filled in";
            }
            $id = $request->input('firmTypeID'.$i);
            $firmTypeName = $request->input('firmTypeName'.$i);
            $firmTypeEdit = FirmType::where('id', $id)->first();
            $firmTypeEdit->name = $firmTypeName;
            $firmTypeEdit->update();
        }
        return $alertMessage = "Firm Type data successfully updated";
    }
    catch(Exception $e){
        dd($e);
        return $alertMessage = "Error editing firm type data";
    }
}
public function DeleteFirmType(Request $request){
    try{
        $delID = $request->get('deletebtn');
        $FirmTypeToDelete = FirmType::where('id',$delID)-> first();
        $FirmTypeToDelete->delete();
        return $alertMessage = "Firm Type data deleted successfully";
        }
        catch(Exception $e){
            return $e->getMessage();
        }
}
public function AddFirmType(Request $request){
    try{
    $newFirmType = new FirmType;
    $newFirmType->id =FirmType::get()->last()->id+1;
    $newFirmType->name = $request->input('new-firm-type');
    $newFirmType->save();
    return $alertMessage = "New firm type data successfully added";
    }
    catch(Exception $e){
        return $e->getMessage();
    }
}
public function FirmTypeActions(Request $request,$actionType){
    if(Auth::check() == true){
        $alertMessage = "";
        try{
            if($actionType == "Edit"){
                try{
                    $alertMessage = $this-> EditFirmType($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in editing firm type data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                } 
            }
            else if($actionType == "Add"){
                try{
                $alertMessage = $this-> AddFirmType($request);
                return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in adding firm type data";
                    return redirect()->back()->with('alertMessage',$e);
                }
            }
            else if($actionType = "Delete"){
                try{
                    $alertMessage = $this-> DeleteFirmType($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in deleting firm type data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
            }
        }
        
        catch(Exception $e){
            dd($e);
            $alertMessage = "An error has occured";
            return redirect()->back()->with('alertMessage',$alertMessage);
        }
        return redirect()->back()->with('alertMessage', $alertMessage);
    }
    else{
        return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
    }
}

//Package Actions
public function DisplayPackage(Request $request){
    if(Auth::check() == true){
        $path = $request->getPathInfo();
        $rowCount = 0;
        $packages = Package::all();
        $lastRecord = Package::withTrashed()->latest('id')->first();
        $newPackageID = $lastRecord ? $lastRecord->id + 1 : 1;
        return view($path,['packages' => $packages, 'rowCount' => $rowCount,'newPackageID' => $newPackageID]);
        }
        else{
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
        }
}
public function EditPackage($request){
    try{
        $count = $request->input('rowcount');
        for($i=0;$i<=$count;$i++){
            try{
                $request->validate([
                    'packageid'.$i =>'required',
                    'packagecode'.$i =>'required',
                    'packagename'.$i =>'required',
                    'userlimit'.$i =>'required'
                ]);
            }
            catch(Exception $e){
                return $alertMessage = "All fields must be filled in";
            }
            $id = $request->input('packageid'.$i);
            $packageCode = $request->input('packagecode'.$i);
            $packagename = $request->input('packagename'.$i);
            $userlimit = $request->input('userlimit'.$i);
            $packageEdit = Package::where('id', $id)->first();
            $packageEdit->PackageCode = $packageCode;
            $packageEdit->name = $packagename;
            $packageEdit->userlimit = $userlimit;
            $packageEdit->update();
        }
        return $alertMessage = "Package data successfully updated";
    }
    catch(Exception $e){
        dd($e);
        return $alertMessage = "Error editing package data";
    }
}
public function DeletePackage(Request $request){
    try{
        $delID = $request->get('deletebtn');
        $PackageToDelete = Package::where('id',$delID)-> first();
        $PackageToDelete->delete();
        return $alertMessage = "Package data deleted successfully";
        }
        catch(Exception $e){
            return $e->getMessage();
        }
}
public function AddPackage(Request $request){
    try{
    $newPackage = new Package;
    $newPackage -> id = $request->input('new-package-id');
    $newPackage->PackageCode = $request->input('new-package-PackageCode');
    $newPackage->name = $request->input('new-package-name');
    $newPackage->userlimit = $request->input('new-package-userlimit'); 
    $newPackage->save();
    return $alertMessage = "New package data successfully added";
    }
    catch(Exception $e){
        dd($e);
        return $e->getMessage();
    }
}
public function PackageActions(Request $request,$actionType){
    if(Auth::check() == true){
        $alertMessage = "";
        try{
            if($actionType == "Edit"){
                try{
                    $alertMessage = $this-> EditPackage($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in editing package data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                } 
            }
            else if($actionType == "Add"){
                try{
                $alertMessage = $this-> AddPackage($request);
                return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in adding package data";
                    return redirect()->back()->with('alertMessage',$e);
                }
            }
            else if($actionType = "Delete"){
                try{
                    $alertMessage = $this-> DeletePackage($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in deleting package data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
            }
        }
        
        catch(Exception $e){
            dd($e);
            $alertMessage = "An error has occured";
            return redirect()->back()->with('alertMessage',$alertMessage);
        }
        return redirect()->back()->with('alertMessage', $alertMessage);
    }
    else{
        return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
    }
}

//Package Base Actions
public function DisplayPackageBase(Request $request){
    if(Auth::check() == true){
        $path = $request->getPathInfo();
        $rowCount = 0;
        $basepackages = PackageBase::all();
        $lastRecord = PackageBase::withTrashed()->latest('id')->first();
        $newBasePackageID = $lastRecord ? $lastRecord->id + 1 : 1;
        $packageNames =Package::all(); 
        return view($path,['basepackages' => $basepackages, 'rowCount' => $rowCount,'newBasePackageID' => $newBasePackageID,'packageNames' => $packageNames]);
        }
        else{
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
        }
}
public function EditPackageBase($request){
    try{
        $count = $request->input('rowcount');
        for($i=0;$i<=$count;$i++){
            try{
                $request->validate([
                    'packagebaseid'.$i =>'required',
                    'packagename'.$i =>'required',
                    'duration'.$i =>'required',
                    'baseprice'.$i =>'required'
                ]);
            }
            catch(Exception $e){
                return $alertMessage = "All fields must be filled in";
            }
            $id = $request->input('packagebaseid'.$i);
            $baseprice = $request->input('baseprice'.$i);
            $packagename = $request->input('packagename'.$i);
            $packageid = Package::where('name',$packagename)->first()->id;
            $duration = $request->input('duration'.$i);
            $packageBaseEdit = PackageBase::where('id', $id)->first();
            $packageBaseEdit->baseprice = $baseprice;
            $packageBaseEdit->PackageID = $packageid;
            $packageBaseEdit->duration = $duration;
            $packageBaseEdit->update();
        }
        return $alertMessage = "Package base data successfully updated";
    }
    catch(Exception $e){
        dd($e);
        return $alertMessage = "Error editing package base data";
    }
}
public function DeletePackageBase(Request $request){
    try{
        $delID = $request->get('deletebtn');
        $PackageBaseToDelete = PackageBase::where('id',$delID)-> first();
        $PackageBaseToDelete->delete();
        return $alertMessage = "Package Base data deleted successfully";
        }
        catch(Exception $e){
            return $e->getMessage();
        }
}
public function AddPackageBase(Request $request){
    try{
    $newPackageBase = new PackageBase;
    $newPackageBase -> id = $request->input('new-basepackage-id');
    $packagename = $request->selectedpackagename;
    $packageid = Package::where('name',$packagename)->first()->id;
    $newPackageBase->PackageID = $packageid;
    $newPackageBase->duration = $request->input('new-basepackage-duration');
    $newPackageBase->baseprice = $request->input('new-basepackage-price'); 
    $newPackageBase->save();
    return $alertMessage = "New base package data successfully added";
    }
    catch(Exception $e){
        dd($e);
        return $e->getMessage();
    }
}
public function PackageBaseActions(Request $request,$actionType){
    if(Auth::check() == true){
        $alertMessage = "";
        try{
            if($actionType == "Edit"){
                try{
                    $alertMessage = $this-> EditPackageBase($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in editing package base data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                } 
            }
            else if($actionType == "Add"){
                try{
                $alertMessage = $this-> AddPackageBase($request);
                return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in adding package base data";
                    return redirect()->back()->with('alertMessage',$e);
                }
            }
            else if($actionType = "Delete"){
                try{
                    $alertMessage = $this-> DeletePackageBase($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in deleting package base data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
            }
        }
        
        catch(Exception $e){
            dd($e);
            $alertMessage = "An error has occured";
            return redirect()->back()->with('alertMessage',$alertMessage);
        }
        return redirect()->back()->with('alertMessage', $alertMessage);
    }
    else{
        return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
    }
}

//Business User Actions
public function DisplayBusinessUser(Request $request){
    if(Auth::check() == true){
        $path = $request->getPathInfo();
        $rowCount = 0;
        $busers = BusinessUser::all();
        $businesses = Business::all();
        $users = User::where('RoleID','!=','1')->get();
        
        $lastRecord = BusinessUser::withTrashed()->latest('id')->first();
        $newbUserID = $lastRecord ? $lastRecord->id + 1 : 1;
        $packageNames =Package::all(); 
        return view($path,['busers' => $busers, 'rowCount' => $rowCount,'newbUserID'=> $newbUserID,'users'=>$users, 'businesses'=>$businesses]);
        }
        else{
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
        }
}
public function EditBusinessUser($request){
    try{
        $count = $request->input('rowcount');
        for($i=0;$i<=$count;$i++){
            try{
                $request->validate([
                    'businessuserid'.$i =>'required',
                    'businessname'.$i =>'required',
                    'useremail'.$i =>'required'
                ]);
            }
            catch(Exception $e){
                dd($e);
                return $alertMessage = "All fields must be filled in";
            }
            $id = $request->input('businessuserid'.$i);
            $businessName = $request->input('businessname'.$i);
            $businessid = Business::where('businessName', $businessName)->first()->id;
            $userEmail = $request->input('useremail'.$i);
            $userid = User::where('Email', $userEmail)->first()->id;
            $businessUserEdit = BusinessUser::where('id', $id)->first();
            $businessUserEdit->businessID = $businessid;
            $businessUserEdit->userID = $userid;
            $businessUserEdit->update();
        }
        return $alertMessage = "Business user data successfully updated";
    }
    catch(Exception $e){
        dd($e);
        return $alertMessage = "Error editing business user data";
    }
}
public function DeleteBusinessUser(Request $request){
    try{
        $delID = $request->get('deletebtn');
        $BusinessUserToDelete = BusinessUser::where('id',$delID)-> first();
        $BusinessUserToDelete->delete();
        return $alertMessage = "Business user data deleted successfully";
        }
        catch(Exception $e){
            return $e->getMessage();
        }
}
public function AddBusinessUser(Request $request){
    try{
    $newBusinessUser = new BusinessUser;
    $newBusinessUser -> id = $request->input('new-buser-id');
    $businessName = $request->selectedBusiness;
    $businessID = Business::where('businessName',$businessName)->first()->id;
    $newBusinessUser->businessID = $businessID;
    $businessUserEmail = $request->selectedUser;
    $userID = User::where('email',$businessUserEmail)->first()->id;
    $newBusinessUser->userID = $userID;
    
    $newBusinessUser->save();
    return $alertMessage = "New business user data successfully added";
    }
    catch(Exception $e){
        dd($e);
        return $e->getMessage();
    }
}
public function BusinessUserActions(Request $request,$actionType){
    if(Auth::check() == true){
        $alertMessage = "";
        try{
            if($actionType == "Edit"){
                try{
                    $alertMessage = $this-> EditBusinessUser($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in editing business user data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                } 
            }
            else if($actionType == "Add"){
                try{
                $alertMessage = $this-> AddBusinessUser($request);
                return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in adding business user data";
                    return redirect()->back()->with('alertMessage',$e);
                }
            }
            else if($actionType = "Delete"){
                try{
                    $alertMessage = $this-> DeleteBusinessUser($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in deleting business user data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
            }
        }
        
        catch(Exception $e){
            dd($e);
            $alertMessage = "An error has occured";
            return redirect()->back()->with('alertMessage',$alertMessage);
        }
        return redirect()->back()->with('alertMessage', $alertMessage);
    }
    else{
        return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
    }
}

//Post Office Actions
public function DisplayPostOffices(Request $request){
    if(Auth::check() == true){
        $path = $request->getPathInfo();
        $rowCount = 0;
        $checkNull = PostOffice::get()->last();
        $lastRecord = PostOffice::withTrashed()->latest('id')->first();
        $newPostofficeID = $lastRecord ? $lastRecord->id + 1 : 1;
        $postoffices = PostOffice::all();
        return view($path,['postoffices' => $postoffices, 'rowCount' => $rowCount,'newPostofficeID' => $newPostofficeID]);
        }
        else{
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
        }
}
public function EditPostOffice($request){
    try{
        $count = $request->input('rowcount');
        for($i=0;$i<=$count;$i++){
            try{
                $request->validate([
                    'postofficeID'.$i =>'required',
                    'postofficeName'.$i =>'required'
                ]);
            }
            catch(Exception $e){
                return $alertMessage = "All fields must be filled in";
            }
            $id = $request->input('postofficeID'.$i);
            $postOfficeName = $request->input('postofficeName'.$i);
            $postOfficeEdit = PostOffice::where('id', $id)->first();
            $postOfficeEdit->name = $postOfficeName;
            $postOfficeEdit->update();
        }
        return $alertMessage = "Post office data successfully updated";
    }
    catch(Exception $e){
        dd($e);
        return $alertMessage = "Error editing post office data";
    }
}
public function DeletePostOffice(Request $request){
    try{
        $delID = $request->get('deletebtn');
        $PostOfficeToDelete = PostOffice::where('id',$delID)-> first();
        $PostOfficeToDelete->delete();
        return $alertMessage = "Post office data deleted successfully";
        }
        catch(Exception $e){
            return $e->getMessage();
        }
}
public function AddPostOffice(Request $request){
    try{
    $newPostOffice = new PostOffice;
    $lastRecord = PostOffice::withTrashed()->latest('id')->first();
    $postOfficeID = $lastRecord ? $lastRecord->id + 1 : 1;
    $newPostOffice->id = $postOfficeID;
    $newPostOffice->name = $request->input('new-postoffice-name');
    $newPostOffice->save();
    return $alertMessage = "New post office data successfully added";
    }
    catch(Exception $e){
        dd($e);
        return $e->getMessage();
    }
}
public function PostOfficeActions(Request $request,$actionType){
    if(Auth::check() == true){
        $alertMessage = "";
        
        try{
            if($actionType == "Edit"){
                try{
                    $alertMessage = $this-> EditPostOffice($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in editing post office data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                } 
            }
            else if($actionType == "Add"){
                try{
                $alertMessage = $this-> AddPostOffice($request);
                return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in adding post office data";
                    return redirect()->back()->with('alertMessage',$e);
                }
            }
            else if($actionType == "Delete"){
                try{
                    $alertMessage = $this-> DeletePostOffice($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in deleting firm type data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
            }
        }
        
        catch(Exception $e){
            dd($e);
            $alertMessage = "An error has occured";
            return redirect()->back()->with('alertMessage',$alertMessage);
        }
        return redirect()->back()->with('alertMessage', $alertMessage);
    }
    else{
        return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
    }
}

//State Code Actions
public function DisplayStateCode(Request $request){
    if(Auth::check() == true){
        $path = $request->getPathInfo();
        $rowCount = 0;
        $checkNull = StateCode::get()->last();
        $lastRecord = StateCode::withTrashed()->latest('id')->first();
        $newStateCodeID = $lastRecord ? $lastRecord->id + 1 : 1;
        $statecodes = StateCode::all();
        return view($path,['statecodes' => $statecodes, 'rowCount' => $rowCount,'newStateCodeID' => $newStateCodeID]);
        }
        else{
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
        }
}
public function EditStateCode($request){
    try{
        $count = $request->input('rowcount');
        for($i=0;$i<=$count;$i++){
            try{
                $request->validate([
                    'stateCodeID'.$i =>'required',
                    'stateName'.$i =>'required'
                ]);
            }
            catch(Exception $e){
                return $alertMessage = "All fields must be filled in";
            }
            $id = $request->input('stateCodeID'.$i);
            $stateCodeName = $request->input('stateName'.$i);
            $stateCodeEdit = StateCode::where('id', $id)->first();
            $stateCodeEdit->name = $stateCodeName;
            $stateCodeEdit->update();
        }
        return $alertMessage = "State code data successfully updated";
    }
    catch(Exception $e){
        dd($e);
        return $alertMessage = "Error editing state code data";
    }
}
public function DeleteStateCode(Request $request){
    try{
        $delID = $request->get('deletebtn');
        $StateCodeToDelete = StateCode::where('id',$delID)-> first();
        $StateCodeToDelete->delete();
        return $alertMessage = "State code data deleted successfully";
        }
        catch(Exception $e){
            return $e->getMessage();
        }
}
public function AddStateCode(Request $request){
    try{
    $newStateCode = new StateCode;
    $lastRecord = StateCode::withTrashed()->latest('id')->first();
    $stateID = $lastRecord ? $lastRecord->id + 1 : 1;
    $newStateCode->id = $stateID;
    $newStateCode->name = $request->input('new-statecode-name');
    $newStateCode->save();
    return $alertMessage = "New state code data successfully added";
    }
    catch(Exception $e){
        dd($e);
        return $e->getMessage();
    }
}
public function StateCodeActions(Request $request,$actionType){
    if(Auth::check() == true){
        $alertMessage = "";
        
        try{
            if($actionType == "Edit"){
                try{
                    $alertMessage = $this-> EditStateCode($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in editing state code data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                } 
            }
            else if($actionType == "Add"){
                try{
                $alertMessage = $this-> AddStateCode($request);
                return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in adding state code data";
                    return redirect()->back()->with('alertMessage',$e);
                }
            }
            else if($actionType == "Delete"){
                try{
                    $alertMessage = $this-> DeleteStateCode($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in deleting state code data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
            }
        }
        
        catch(Exception $e){
            dd($e);
            $alertMessage = "An error has occured";
            return redirect()->back()->with('alertMessage',$alertMessage);
        }
        return redirect()->back()->with('alertMessage', $alertMessage);
    }
    else{
        return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
    }
}

//Post Code Actions
public function DisplayPostCode(Request $request){
    if(Auth::check() == true){
        $path = $request->getPathInfo();
        $rowcount = 0;
        $checkNull = PostCode::get()->last();
        $lastRecord = PostCode::withTrashed()->latest('id')->first();
        $newpostCodeID = $lastRecord ? $lastRecord->id + 1 : 1;
        $postcodes = PostCode::all();
        $postoffices = PostOffice::all();
        $statecodes = StateCode::all();
        return view($path,['postcodes' => $postcodes, 'rowcount' => $rowcount,'newpostCodeID' => $newpostCodeID,'postoffices' => $postoffices, 'statecodes' => $statecodes]);
        }
        else{
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
        }
}
public function EditPostCode($request){
    try{
        $count = $request->input('rowcount');
        for($i=0;$i<=$count;$i++){
            try{
                $request->validate([
                    'postcodeid'.$i =>'required',
                    'postcode'.$i =>'required',
                    'location'.$i =>'required',
                ]);
            }
            catch(Exception $e){
                return $alertMessage = "All fields must be filled in";
            }
            $id = $request->input('postcodeid'.$i);
            $postCode = $request->input('postcode'.$i);
            $location = $request->input('location'.$i);
            $officeName = $request->input('officename'.$i);
            $postOfficeID = PostOffice::where('name', $officeName)->first()->id;
            $stateName = $request->input('statename'.$i);
            $stateID = StateCode::where('name', $stateName)->first()->id;
            $postCodeEdit = PostCode::where('id', $id)->first();
            $postCodeEdit->postcode = $postCode;
            $postCodeEdit->location = $location;
            $postCodeEdit->postOfficeID = $postOfficeID;
            $postCodeEdit->stateCodeID = $stateID;
            $postCodeEdit->update();
        }
        return $alertMessage = "Post code data successfully updated";
    }
    catch(Exception $e){
        dd($e);
        return $alertMessage = "Error editing post code data";
    }
}
public function DeletePostCode(Request $request){
    try{
        $delID = $request->get('deletebtn');
        $PostCodeToDelete = PostCode::where('id',$delID)-> first();
        $PostCodeToDelete->delete();
        return $alertMessage = "Post code data deleted successfully";
        }
        catch(Exception $e){
            return $e->getMessage();
        }
}
public function AddPostCode(Request $request){
    try{
    $newPostCode = new PostCode;
    $newPostCode->id = $request->input('new-postcode-id');
    $newPostCode->postcode = $request->input('new-postcode');
    $newPostCode->location = $request->input('new-postcode-location');
    $postOfficeCode = $request->input('officename');
    $qaPostOfficeCode = $request->input('new-postoffice-name');
    if($qaPostOfficeCode!=null){
        $this->AddPostOffice($request);
        $postOfficeCode = $qaPostOfficeCode;
    }
    $StateCode = $request->input('statename');
    $qaStateCode = $request->input('new-statecode-name');
    if($qaStateCode!=null){
        $this->AddStateCode($request);
        $StateCode = $qaStateCode;
    }
    $postOfficeID = PostOffice::where('name',$postOfficeCode)->first()->id;
    $newPostCode->postOfficeID = $postOfficeID;
    $stateCodeID = StateCode::where('name',$StateCode)->first()->id;
    $newPostCode->stateCodeID = $stateCodeID;
    $newPostCode->save();
    return $alertMessage = "New post code data successfully added";
    }
    catch(Exception $e){
        dd($e);
        return $e->getMessage();
    }
}
public function PostCodeActions(Request $request,$actionType){
    if(Auth::check() == true){
        $alertMessage = "";
        
        try{
            if($actionType == "Edit"){
                try{
                    $alertMessage = $this-> EditPostCode($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in editing post code data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                } 
            }
            else if($actionType == "Add"){
                try{
                $alertMessage = $this-> AddPostCode($request);
                return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in adding post code data";
                    return redirect()->back()->with('alertMessage',$e);
                }
            }
            else if($actionType == "Delete"){
                try{
                    $alertMessage = $this-> DeletePostCode($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in deleting post code data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
            }
        }
        
        catch(Exception $e){
            dd($e);
            $alertMessage = "An error has occured";
            return redirect()->back()->with('alertMessage',$alertMessage);
        }
        return redirect()->back()->with('alertMessage', $alertMessage);
    }
    else{
        return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
    }
}

//Address actions
public function getPostOffices($statename){
    $stateID = StateCode::where('name',$statename)->first()->id;
    $postOffices = PostCode::where('stateCodeID',$stateID)->pluck('postOfficeID');
    $postOfficeNames = [];
    foreach($postOffices as $postOffice){
        $postOfficeNames[] = PostOffice::where('id',$postOffice)->pluck('name');
    }
    return response()->json($postOfficeNames);
}
public function getPostCode($postofficename){
    $postofficeID = PostOffice::where('name',$postofficename)->first()->id;
    $postcode = PostCode::where('postOfficeID',$postofficeID)->first()->postcode;
    return response()->json($postcode);
}
public function DisplayAddress(Request $request){
    
    if(Auth::check() == true){
        $path = $request->getPathInfo();
        $rowcount = 0;
        $lastRecord = Address::withTrashed()->latest('id')->first();
        $newaddressID = $lastRecord ? $lastRecord->id + 1 : 1;
        $addresses = Address::all();
        $postcodes = PostCode::all();
        $postoffices = PostOffice::all();
        $stateCodes = StateCode::all();
        $users = User::all();
        $addressTypes = AddressType::all();
        return view($path,['postcodes' => $postcodes, 'rowcount' => $rowcount,'newaddressID' => $newaddressID,'postoffices' => $postoffices, 'stateCodes' => $stateCodes, 'addresses' => $addresses, 'users' => $users , 'addressTypes' => $addressTypes]);
        }
        else{
            return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
        }
}
public function EditAddress($request){
    try{
        $count = $request->input('rowcount');
        for($i=0;$i<=$count;$i++){
            try{
                $request->validate([
                    'addressid'.$i =>'required',
                    'addressline'.$i =>'required',
                    'street'.$i =>'required',
                    'country'.$i =>'required'
                ]);
            }
            catch(Exception $e){
                return $alertMessage = "All fields must be filled in";
            }
            $id = $request->input('addressid'.$i);
            $addressline = $request->input('addressline'.$i);
            $street = $request->input('street'.$i);
            $country = $request->input('country'.$i);
            $postCode = $request->input('postcode'.$i);
            $postcodeID= PostCode::where('postcode', $postCode)->first()->id;
            $user = $request->input('selecteduseremail'.$i);
            $userID= User::where('email', $user)->first()->id;
            $addresstype = $request->input('addressType'.$i);
            $addressTypeID= AddressType::where('name', $addresstype)->first()->id;

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
    }
    catch(Exception $e){
        dd($e);
        return $alertMessage = "Error editing address data";
    }
}
public function DeleteAddress(Request $request){
    try{
        $delID = $request->get('deletebtn');
        $AddressToDelete = Address::where('id',$delID)-> first();
        $AddressToDelete->delete();
        return $alertMessage = "Address data deleted successfully";
        }
        catch(Exception $e){
            return $e->getMessage();
        }
}
public function AddAddress(Request $request){
    try{
    $newAddress = new Address;
    $newAddress->id = $request->input('new-address-id');
    $newAddress->addressLine1 = $request->input('new-address-line');
    $newAddress->street = $request->input('new-address-street');
    $newAddress -> country = $request->input('new-address-country');
    $selectedAddressTypes = $request->input('selectedaddresstype');
    $qaAddressType = $request->input('new-address-type');
    if($qaAddressType!=null){
        $this->AddAddressType($request);
        $selectedAddressTypes = $qaAddressType;
    }
    $addressTypeID = AddressType::where('name', $selectedAddressTypes)->first()->id;
    $email = $request->input('new-address-user');
    $userID = User::where('email',$email)->first()->id;
    $newAddress->userID = $userID;
    $postcode = $request -> input('new-address-postcode');
    $postCodeID = PostCode::where('postcode',$postcode)->first()->id;
    $newAddress->postCodeID = $postCodeID;
    $newAddress ->addressTypeID = $addressTypeID;
    $newAddress->save();
    return $alertMessage = "New address data successfully added";
    }
    catch(Exception $e){
        dd($e);
        return $e->getMessage();
    }
}
public function AddressActions(Request $request,$actionType){
    if(Auth::check() == true){
        $alertMessage = "";
        try{
            if($actionType == "Edit"){
                try{
                    $alertMessage = $this-> EditAddress($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in editing post code data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                } 
            }
            else if($actionType == "Add"){
                try{
                $alertMessage = $this-> AddAddress($request);
                return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in adding post code data";
                    return redirect()->back()->with('alertMessage',$e);
                }
            }
            else if($actionType == "Delete"){
                try{
                    $alertMessage = $this-> DeleteAddress($request);
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
                catch(Exception $e){
                    $alertMessage = "Error in deleting post code data";
                    return redirect()->back()->with('alertMessage',$alertMessage);
                }
            }
        }
        
        catch(Exception $e){
            dd($e);
            $alertMessage = "An error has occured";
            return redirect()->back()->with('alertMessage',$alertMessage);
        }
        return redirect()->back()->with('alertMessage', $alertMessage);
    }
    else{
        return Redirect::route('toLogin')->withErrors(['msg' => 'You have to login to access the dashboard!']); 
    }
}
}
