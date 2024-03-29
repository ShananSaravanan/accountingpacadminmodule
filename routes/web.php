<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\AdminLogin;
use App\Http\Controllers\LoginAuthentication;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/", function () { 
    return view("Pages.adminLogin");
})->name('toLogin');

Route::post("/Pages/adminlogin",[ActionController::class,'LoginService'])->name('login.getservice');
Route::get("/logout",[ActionController::class,'LogoutService'])->name('logout.getservice');
Route::get('/Pages/adminindex',[ActionController::class,'LoadPage'])->name('toindex');

Route::get('/Pages/Admin-Settings/adminprofile',[ActionController::class,'LoadProfile'])->name('toprofile');
Route::get('/Pages/Admin-Settings/admin-editprofile',[ActionController::class,'EditProfile'])->name('toEditProfile');
Route::post('/Pages/Admin-Settings/admin-editprofile',[ActionController::class,'UpdateProfile'])->name('toUpdateProfile');


Route::get('/Pages/Admin-Settings/adminsettings',[ActionController::class,'CheckLogin'])->name('toSettings');

Route::get('Pages/Admin-User/displayusers/{searchType}', [ActionController::class,'DisplayUsers'])->name('displayUsers');
Route::post('Pages/Admin-User/displayusers/{actionType?}', [ActionController::class,'UserProfileActions'])->name('toUserActions');

Route::get('Pages/addfirmuser',[ActionController::class,'ShowAddFirmUser'])->name('toShowAddFirmUser');

Route::get('Pages/Admin-Business/displaybusiness',[ActionController::class,'ShowRegBusiness']) -> name ('toShowRegBusiness');
Route::post('Pages/Admin-Business/displaybusiness/{actionType?}', [ActionController::class,'BusinessActions'])->name('toBusinessActions');

Route::get('Pages/Admin-Roles/roles',[ActionController::class,'DisplayRoles'])->name('toShowRoles');
Route::post('Pages/Admin-Roles/roles/{actionType?}', [ActionController::class,'RoleActions'])->name('toRoleActions');

Route::get('Pages/Admin-Hcode/displayhcode',[ActionController::class,'DisplayHCodes'])->name('toShowHcodes');
Route::post('Pages/Admin-Hcode/displayhcode/{actionType?}', [ActionController::class,'HcodeActions'])->name('toHcodeActions');

Route::get('Pages/Admin-Business/displaybtype',[ActionController::class,'DisplayBusinessType'])->name('toDisplayBusinessType');
Route::post('Pages/Admin-Business/displaybtype/{actionType?}', [ActionController::class,'BusinessTypeActions'])->name('toBusinessTypeActions');

Route::get('Pages/Admin-Address/displayaddresstypes',[ActionController::class,'DisplayAddressType'])->name('toShowAddressTypes');
Route::post('Pages/Admin-Address/displayaddresstypes/{actionType?}', [ActionController::class,'AddressTypeActions'])->name('toAddressTypeActions');

Route::get('Pages/Admin-Address/displaypostoffices',[ActionController::class,'DisplayPostOffices'])->name('toShowPostOffice');
Route::post('Pages/Admin-Address/displaypostoffices/{actionType?}', [ActionController::class,'PostOfficeActions'])->name('toPostOfficeActions');

Route::get('Pages/Admin-Address/displaystatecodes',[ActionController::class,'DisplayStateCode'])->name('toShowStateCode');
Route::post('Pages/Admin-Address/displaystatecodes/{actionType?}', [ActionController::class,'StateCodeActions'])->name('toStateCodeActions');

Route::get('Pages/Admin-Address/displaypostcode',[ActionController::class,'DisplayPostCode'])->name('toShowPostCode');
Route::post('Pages/Admin-Address/displaypostcode/{actionType?}', [ActionController::class,'PostCodeActions'])->name('toPostCodeActions');


Route::get('Pages/Admin-Firms/displayfirmtypes',[ActionController::class,'DisplayFirmType'])->name('toShowFirmTypes');
Route::post('Pages/Admin-Firms/displayfirmtypes/{actionType?}', [ActionController::class,'FirmTypeActions'])->name('toFirmTypeActions');

Route::get('Pages/Admin-Firms/displayfirm',[ActionController::class,'DisplayFirm'])->name('toShowFirm');
Route::post('Pages/Admin-Firms/displayfirm/{actionType?}', [ActionController::class,'FirmActions'])->name('toFirmActions');

Route::get('Pages/Admin-Firms/displayfirmuser',[ActionController::class,'DisplayFirmUser'])->name('toShowFirmUser');
Route::post('Pages/Admin-Firms/displayfirmuser/{actionType?}', [ActionController::class,'FirmUserActions'])->name('toFirmUserActions');


Route::get('Pages/Admin-Package/displaypackage',[ActionController::class,'DisplayPackage'])->name('toShowPackage');
Route::post('Pages/Admin-Package/displaypackage/{actionType?}', [ActionController::class,'PackageActions'])->name('toPackageActions');

Route::get('Pages/Admin-Package/displaypackagebase',[ActionController::class,'DisplayPackageBase'])->name('toShowPackageBase');
Route::post('Pages/Admin-Package/displaypackagebase/{actionType?}', [ActionController::class,'PackageBaseActions'])->name('toPackageBaseActions');

Route::get('Pages/Admin-Business/displaybusinessuser',[ActionController::class,'DisplayBusinessUser'])->name('toShowBusinessUser');
Route::post('Pages/Admin-Business/displaybusinessuser/{actionType?}', [ActionController::class,'BusinessUserActions'])->name('toBusinessUserActions');

Route::get('Pages/Admin-Address/displayaddress',[ActionController::class,'DisplayAddress'])->name('toShowAddress');
Route::post('Pages/Admin-Address/displayaddress/{actionType?}', [ActionController::class,'AddressActions'])->name('toAddressActions');

Route::get('/get-post-offices/{statename}', [ActionController::class, 'getPostOffices']) -> name('getPostOffices');

Route::get('/get-post-code/{postofficename}', [ActionController::class, 'getPostCode']) -> name('getPostCode');

Route::get('Pages/Admin-Subscription/displaytransaction',[ActionController::class,'DisplayTransaction'])->name('toShowTransaction');
Route::post('Pages/Admin-Subscription/displaytransaction/{actionType?}', [ActionController::class,'TransactionActions'])->name('toTransactionActions');

Route::get('Pages/Admin-Business/displayfinancialrecords',[ActionController::class,'DisplayFinancialRecords'])->name('toShowFinancialRecords');
Route::post('Pages/Admin-Business/displayfinancialrecords/{actionType?}', [ActionController::class,'FinancialRecordsActions'])->name('toFinancialRecordsActions');



Route::get('Pages/Admin-Subscription/displaysubscription',[ActionController::class,'DisplaySubscription'])->name('toShowSubscription');
Route::post('Pages/Admin-Subscription/displaysubscription{actionType?}', [ActionController::class,'SubscriptionActions'])->name('toSubscriptionActions');
Route::get('/get-packages/{userid}', [ActionController::class, 'getPackages']) -> name('getPackages');
Route::get('/get-code/{packagename}', [ActionController::class, 'getCode']) -> name('getCode');
Route::get('/get-transactiondetails/{transactionid}', [ActionController::class, 'getTransactionDetails']) -> name('getTransactionDetails');

Route::get('Pages/Admin-Assignment/displayassignments',[ActionController::class,'DisplayAssignments'])->name('toShowAssignment');
Route::post('Pages/Admin-Assignment/displayassignments{actionType?}', [ActionController::class,'AssignmentsActions'])->name('toAssignmentsActions');
Route::get('/get-subscriptiondetails/{userid}', [ActionController::class, 'getSubscriptionDetails']) -> name('getSubscriptionDetails');

Route::get('Pages/Admin-Settings/displayrecyclebin',[ActionController::class,'DisplayBin'])->name('toShowRecycleBin');
Route::post('/restore-soft-deleted-item/{modelName}/{id}', [ActionController::class,'RestoreDeletedItems'])->name('restoreDeletedItems');

Route::get('/export-to-excel', [ActionController::class, 'exportToExcel'])->name('exportToExcel');

Route::get('/get-stats/{modelname}', [ActionController::class, 'getStats']) -> name('getStats');

Route::get('/get-transaction-trend', [ActionController::class, 'getTransactionTrends']) -> name('getTransactionTrends');

Route::get('/get-specific-record-details/{modelname}', [ActionController::class, 'getSpecificRecordDetails']) -> name('getSpecificRecordDetails');

Route::get('Pages//serverlog',[ActionController::class,'showLog'])->name('showLog');