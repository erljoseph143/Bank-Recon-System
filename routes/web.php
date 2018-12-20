<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Functions\Bsfunction;
use Illuminate\Http\Request;
use App\BankNo;

Route::get('test',function (Bsfunction $bs)
{
//    session()->flush();
//    $bs->addtoerror("naay error");
//    for($x=0;$x<=10;$x++)
//    {
//       // $bs->addtoerror("naay error$x");
//    }
//    echo session()->get('mgaerrors');
//   // $html = "<button>hahhah</button>";
//    //return view('layouts.test',compact('html'));
    $number = 22000;
    echo substr_replace($number,".",-2,0);
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('removedir','AjaxController@removeDir');

Route::get('read_bank_error/{filename}/{col}/{row}','AjaxController@readExcel');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/admin/users/roles',['middleware'=>'login',function(){

    return "Middleware Role";

}]);

Route::get('/dashboard','AjaxController@dashboard');

Route::get('/dashboard-acct','AjaxController@dashboardAcct');
Route::get('/check_voucher','AjaxController@CheckVoucher');
Route::post('/check_voucher','CheckVoucherController@store');

Route::get('/bankstatement','AjaxController@company');
Route::post('/bankstatement','BankStatementController@store');

Route::get('/combu/{id}','AjaxController@company_bu');

Route::get('/loadBu/{id}','AjaxController@loadBu');

Route::get('/bankact/{comid}/{buid}','AjaxController@bankAct');

Route::get('excelme','ReportsController@ExcelFile');

Route::get('bankno/{id}','AjaxController@getBankNo');

Route::post('getresult','AjaxController@getresult');

Route::get('data',function(){
	$com = App\Company::pluck('company','company_code')->all();
	return view('data.home',compact('com'));
});
//getcheck/{{$bd->datein}}/{{$bankno}}/{{$com}}/{{$bu}}


/*-----------------------------------------------------------------------------
 *  Ajax Route Reports
 *-----------------------------------------------------------------------------
 */
Route::get('report_dis_summary','ViewController@dis_summary');

Route::get('month_bank_recon/{bankno}/{type}','ViewController@monthBank');

Route::get('getcheck/{bDate}/{bankno}/{com}/{bu}/{bankname}/{accountno}','ReportsController@loadDis');

/*---------------------------------------------------------------------------------------
 * Ajax Disbursement
 *---------------------------------------------------------------------------------------
*/
Route::get('matchCheck','Disbursement\DisbursementController@matchCheck');

Route::get('monthlyCheck/{bankno}/{type}','Disbursement\DisbursementController@MonthlyCheck');

Route::get('showMacthCheck/{data}','Disbursement\DisbursementController@showMacthCheck');

Route::get('unMatchCheck','Disbursement\DisbursementController@unMatchCheck');

Route::get('monthlyUnmatch/{bankno}/{type}','Disbursement\DisbursementController@monthlyUnmatch');

Route::get('showMonthlyUnmatched/{data}','Disbursement\DisbursementController@showMonthlyUnmatched');

Route::get('ocCheck','Disbursement\DisbursementController@ocCheck');

Route::get('monthlyOC/{bankno}/{type}','Disbursement\DisbursementController@monthlyOC');

Route::get('showMonthlyOC/{data}','Disbursement\DisbursementController@showMonthlyOC');

Route::get('dmCheck','Disbursement\DisbursementController@dmCheck');

Route::get('monthlyDM/{bankno}/{type}','Disbursement\DisbursementController@monthlyDM');

Route::get('showMonthlyDM/{data}','Disbursement\DisbursementController@showMonthlyDM');

Route::get('PDC_DMCheck','Disbursement\DisbursementController@PDC_DMCheck');

Route::get('monthlyPDC_DC/{bankno}/{type}','Disbursement\DisbursementController@monthlyPDC_DC');

Route::get('showMonthlyPDC_DC/{data}','Disbursement\DisbursementController@showMonthlyPDC_DC');

Route::get('CancelledCheck','Disbursement\DisbursementController@CancelledCheck');

Route::get('monthlyCancellded/{bankno}/{type}','Disbursement\DisbursementController@monthlyCancellded');

Route::get('showMonthlyCancelled/{data}','Disbursement\DisbursementController@showMonthlyCancelled');

Route::get('PostedCheck','Disbursement\DisbursementController@PostedCheck');

Route::get('monthlyPosted/{bankno}/{type}','Disbursement\DisbursementController@monthlyPosted');

Route::get('showMonthlyPosted/{data}','Disbursement\DisbursementController@showMonthlyPosted');

Route::get('staleCheck','Disbursement\DisbursementController@staleCheck');

Route::get('monthlyStale/{bankno}/{type}','Disbursement\DisbursementController@monthlyStale');
//
Route::get('showMonthlyStale/{data}','Disbursement\DisbursementController@showMonthlyStale');
/*---------------------------------------------------------------------------------------
 * Ajax Options
 *---------------------------------------------------------------------------------------
*/
Route::get('viewDis','ViewController@viewDis');

Route::get('monthlyDis/{bankno}/{type}','ViewController@monthlyDis');

Route::get('showDataDis/{data}','ViewController@showDataDis');
/*---------------------------------------------------------------------------------------
 * Ajax Find Checks
 *---------------------------------------------------------------------------------------
*/

Route::get('findCheck','ViewController@findCheck');

Route::get('getCheck/{checkno}','ViewController@getCheck');

Route::get('manualBS/','ViewController@manualBS');

Route::get('inputBS/','ViewController@inputBS');

Route::get('checkTheCheck/{checkno}','ViewController@checkTheCheck');

Route::get('monthlyBS/{bankno}/{type}','ViewController@monthlyBS');

Route::get('showData/{data}','ViewController@showData');

/*---------------------------------------------------------------------------------------
 * Ajax Find Checks
 *---------------------------------------------------------------------------------------
*/

Route::get('dXcheck_voucher','ViewController@show');
Route::post('dXcheck_voucher','Designex\CVController@store');













//Route::get("baccount/{id}",function($id){
//
//    //$act = \App\Company::find($id)->bankAccounts;
////    $act = \App\BankAccount::findOrFail($id)->company;
////    $act = \App\Company::findOrFail($id)->BU;
//    $act = \App\Businessunit::findOrFail($id);
//    //dd($act->all());
//   // echo $act->company;
////    foreach ($act as $b) {
////       echo $b->bank ."</br>";
////    }
////    foreach($act as $b)
////    {
////        echo $b->bname ."</br>";
////    }
//    echo $act->company->company ." => ". $act->bname;
//});

/*---------------------------------------------------------------------------------------
 * Recon Items Viewing
 *---------------------------------------------------------------------------------------
*/
    Route::get('ReconItems','ReconItems\ReconItemsController@reconItems');
    Route::get('monthlyReconItem/{bankno}','ReconItems\ReconItemsController@monthReconItems');
    Route::get('reconItemsList/{data}','ReconItems\ReconItemsController@reconItemsList');

    Route::get('reconExcel/{data}','ReconItems\ReconItemsController@reconItemExcel');


/*---------------------------------------------------------------------------------------
 * Save Manual BS Data
 *---------------------------------------------------------------------------------------
*/

Route::post('saveBS','ManualBsController@store');

Route::get('testsse','SeverSentEventController@loadSSE');

Route::post('bsmanualupdate','ManualBsController@update');

/*---------------------------------------------------------------------------------------
 * Download Excel Result from Manual BS Data
 *---------------------------------------------------------------------------------------
*/

Route::get('manual_dis_summary','ManualBS\ReportsController@manualBS_summary');

Route::get('month_manual_recon/{bankno}/{type}','ManualBS\ReportsController@monthBank');

Route::get('getManualBS/{bDate}/{bankno}/{com}/{bu}/{bankname}/{accountno}','ManualBS\ReportsController@ManualBSExcel');

/*----------------------------------------------------------------------------------------------------------------------
 * RMS Bank Statment Uploaded Viewing
 *----------------------------------------------------------------------------------------------------------------------
*/
Route::get('BSperCom','RMS\ViewBSUploadedController@BSperCom');

Route::get('BUlist/{comID}','RMS\ViewBSUploadedController@BUlist');

Route::get('BankList/{data}','RMS\ViewBSUploadedController@BankList');

Route::get('BMonthly/{data}','RMS\ViewBSUploadedController@BMonthly');

Route::get('showMonthlyBS/{data}','RMS\ViewBSUploadedController@showMonthlyBS');

Route::post('updatebsdata','RMS\ViewBSUploadedController@updateBSData');

Route::get('downloadBS/{bDate}/{bankno}/{com}/{bu}/{bankname}/{accountno}','RMS\DownloadBSController@getBS');

Route::get('checkBalancePerBank/{data}','RMS\ViewBSUploadedController@checkpermonth');

Route::get('bsError/{data}','RMS\ViewBSUploadedController@bsError');

Route::get('bsinsertingdata/{key}','RMS\ViewBSUploadedController@bsinsertingdata');
Route::post('insertbs','RMS\ViewBSUploadedController@insertbs');

Route::get('reorder/{id}','RMS\ViewBSUploadedController@reorder');

Route::get('ordering/{key}','RMS\ViewBSUploadedController@ordering');

Route::post('checkdataBS','RMS\ViewBSUploadedController@checkBalancePerBank');

Route::get('showErroBS/{date}/{details}/{id}','RMS\ViewBSUploadedController@showErroBS');

Route::get('BankAccountMonitoring/{bankId}','RMS\ViewBSUploadedController@BankAccountMonitoring');
/*----------------------------------------------------------------------------------------------------------------------
 * Accounting View Uploaded Bank Statement
 *----------------------------------------------------------------------------------------------------------------------
*/

Route::get('viewUpBS','AjaxController@viewUpBS');

Route::get('BSmonthly/{data}','AjaxController@BSmonthly');
Route::get('showMonthlyBS_ACCTG/{data}','AjaxController@showMonthlyBS_ACCTG');

Route::get('view-profile','ViewController@profilePic');
Route::post('change-pic','ViewController@changePic');


/*----------------------------------------------------------------------------------------------------------------------
 * Administrator Routes
 *----------------------------------------------------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function ($e) {
	
	Route::delete('bankcodes/selected', 'Admin\BankCodesController@selectedAction')->name('selectedcodes');
    Route::post('bankcodes/store', 'Admin\BankCodesController@store')->name('storebankcodes');
    Route::resource('bankcodes', 'Admin\BankCodesController', ['except'=>['store', 'create', 'show']]);

    Route::get('users/select', 'Admin\UsersController@select')->name('adminselect');
    Route::post('users/selected', 'Admin\UsersController@selected')->name('adminselected');
    Route::get('users/resetpass', 'Admin\UsersController@resetPassword')->name('adminresetpass');
    Route::resource('users', 'Admin\UsersController');
	
	Route::get('bank-statements/bu/{id}/{account}/{code}/{year}/{month}', 'Admin\BankstatementController@listBankStatements')->name('bsview');
    Route::get('bank-statements/bu/{id}/{account}/{code}', 'Admin\BankstatementController@monthlistBankStatements')->name('bsmonths');
    Route::get('bank-statements/bu/{id}', 'Admin\BankstatementController@listAccounts')->name('bsaccounts');
//    Route::get('bank-statements/bu/{id}', 'Admin\BankstatementController@listUsers')->name('bsusers');
    Route::delete('bank-statements/deletemonth','Admin\BankstatementController@deletemonth')->name('bstrashmonth');
    Route::post('bank-statements/datatableajax','Admin\BankstatementController@viewAjax')->name('bsviewajax');
    Route::resource('bank-statements','Admin\BankstatementController');
	
	Route::get('dtr/bu/{id}/{account}/{code}/{year}/{month}', 'Admin\DtrController@listDTR')->name('admindtrview');
    Route::get('dtr/bu/{id}/{account}/{code}', 'Admin\DtrController@monthlist')->name('admindtrmonths');
    Route::get('dtr/bu/{id}', 'Admin\DtrController@listAccounts')->name('admindtraccounts');
	Route::delete('dtr/deletemonth','Admin\DtrController@deletemonth')->name('admindtrtrashmonth');
    Route::post('dtr/datatableajax','Admin\DtrController@viewAjax')->name('admindtrviewajax');
    Route::resource('dtr', 'Admin\DtrController');
	
	Route::post('settings/scanningbs', 'Admin\SettingController@scanningbs')->name('adminscanningbs');
    Route::get('settings/scanedduplicatebs', 'Admin\SettingController@scanedduplicatebs')->name('adminscanedduplicatebs');
    Route::post('settings/viewduplicatebs', 'Admin\SettingController@viewduplicatebs')->name('adminviewduplicatebs');
    Route::post('settings/viewprevbs', 'Admin\SettingController@viewprevbs')->name('adminviewprevbs');
    Route::post('settings/viewnextbs', 'Admin\SettingController@viewnextbs')->name('adminviewnextbs');
    Route::post('settings/trashduplicatebs', 'Admin\SettingController@trashduplicatebs')->name('admintrashduplicatebs');
	
	Route::post('settings/count-bsduplicate', 'Admin\SettingBankStatementController@getBankStatementDuplicateCount')->name('admin.bsdupcount.index');
    Route::post('settings/count-no-bu', 'Admin\SettingBankStatementController@getBankStatementsnoBUCount')->name('admin.bsnobucount.index');
    Route::post('settings/bs-no-bu/view-bank-accounts', 'Admin\SettingBankStatementController@getBankAccountLists')->name('admin.bsbankaccountlist.view');
    Route::post('settings/bs-no-bu/trash', 'Admin\SettingBankStatementController@trashBankStatementsnoBU')->name('admin.trashbsnobu.index');
    Route::get('settings/bs-no-bu', 'Admin\SettingBankStatementController@getBankStatementsnoBU')->name('admin.bsnobu.index');

});

Route::get('admin/home','Admin\HomeController@dashboard')->name('adminhome');

Route::get('admin/archives','Admin\ArchiveController@allArchives');

Route::delete('admin/bankaccounts/selected', 'Admin\BankAccountsController@selectedAction')->name('selectedaccounts');
Route::post('admin/bankaccounts/store', 'Admin\BankAccountsController@store')->name('storeaccounts');
Route::resource('admin/bankaccounts', 'Admin\BankAccountsController', ['except'=>['store','show']]);

Route::get('admin/usertypes/trash','Admin\UsertypeController@allTrash');

Route::post('admin/usertypes/save','Admin\UsertypeController@saveUsertype');

Route::get('admin/usertypes/get','Admin\UsertypeController@getData');

Route::delete('admin/usertypes/{id}', 'Admin\UsertypeController@trashUsertype');

Route::put('admin/usertypes/{id}','Admin\UsertypeController@updateUsertype');

Route::get('admin/usertypes/{id}','Admin\UsertypeController@getUsertype');

Route::get('admin/usertypes','Admin\UsertypeController@viewusertypes');

Route::get('admin/companies/delete-selected/{data}', 'Admin\CompanyController@trashSelectedCompany');

Route::post('admin/companies/save','Admin\CompanyController@saveCompany');

Route::get('admin/companies/trash','Admin\CompanyController@allTrash');

Route::put('admin/companies/{id}','Admin\CompanyController@updateCompany');

Route::delete('admin/companies/{id}', 'Admin\CompanyController@trashCompany');

Route::get('admin/companies/{id}','Admin\CompanyController@getCompany');

Route::get('admin/businessunits/delete-selected/{data}', 'Admin\BusinessunitController@trashSelectedBU');

Route::post('admin/businessunits/save','Admin\BusinessunitController@saveBU');

Route::put('admin/businessunits/{id}','Admin\BusinessunitController@updateBU');

Route::delete('admin/businessunits/{id}', 'Admin\BusinessunitController@trashBU');

Route::get('admin/businessunits/{id}','Admin\BusinessunitController@getBU');

Route::get('admin/company/{id}/businessunits/trash','Admin\BusinessunitController@allTrash');

Route::get('admin/company/{id}/businessunits','Admin\BusinessunitController@allBU');

Route::get('admin/companies','Admin\CompanyController@allCompany');

//Route::get('admin/checking-accounts/{id}/{year}/{month}','Admin\CheckingaccountController@in_month_r');

Route::get('admin/checking-accounts/bu/{id}/{userid}/{account}/{code}/{year}/{month}/trash', 'Admin\CheckingaccountController@listChecksTrash');

Route::get('admin/checking-accounts/bu/{id}/{userid}/{account}/{code}/{year}/{month}', 'Admin\CheckingaccountController@listChecks');

Route::get('admin/checking-accounts/bu/{id}/{userid}/{account}/{code}', 'Admin\CheckingaccountController@monthlistChecks');

Route::get('admin/checking-accounts/bu/{id}/{userid}', 'Admin\CheckingaccountController@listAccounts');

Route::get('admin/checking-accounts/bu/{id}', 'Admin\CheckingaccountController@listUsers');

Route::get('admin/checking-accounts/{id}','Admin\CheckingaccountController@getChecks');

Route::put('admin/checking-accounts/{id}','Admin\CheckingaccountController@updateChecks');

Route::delete('admin/checking-accounts/{id}', 'Admin\CheckingaccountController@trashChecks');

Route::get('admin/checking-accounts','Admin\CheckingaccountController@bank_r');

Route::get('admin/disbursements/bu/{id}/{userid}/{account}/{code}/{year}/{month}', 'Admin\DisbursementController@listDis')->name('disburselists');
Route::get('admin/disbursements/bu/{id}/{userid}/{account}/{code}', 'Admin\DisbursementController@monthlistDis')->name('disbursemonth');
Route::get('admin/disbursements/bu/{id}/{userid}', 'Admin\DisbursementController@listAccounts')->name('disburselistaccounts');
Route::get('admin/disbursements/bu/{id}', 'Admin\DisbursementController@listUsers')->name('disburselistusers');
Route::post('admin/disbursements/deletemonth','Admin\DisbursementController@deletemonth')->name('deletemonth');
Route::get('admin/disbursements','Admin\DisbursementController@listBU')->name('admindisburse');
Route::post('admin/disbursementAjax','Admin\DisbursementsController@viewAjax')->name('listdisburseajax');
Route::resource('admin/disbursementlists','Admin\DisbursementsController', ['except'=>['create','store', 'index','update', 'show', 'edit']]);

Route::get('admin/about','Admin\AboutController@about');

Route::get('admin/settings', 'Admin\SettingController@index');

Route::get('admin/uploaddb', 'Admin\BackupController@uploaddb');

Route::post('admin/backup/download-small-data-tables', 'Admin\BackupController@downloadJSONsTable');

Route::post('admin/backup/extract-json', 'Admin\BackupController@extractJSON');

Route::post('admin/backup/displaycodes', 'Admin\BackupController@backupCodeLists');

Route::post('admin/backup/displaybu', 'Admin\BackupController@backupBULists');

Route::post('admin/backup/displaytable', 'Admin\BackupController@backupMonthLists');

Route::get('admin/backup', 'Admin\BackupController@downloaddb');

Route::post('admin/cashlogs/savecashlogs', 'Admin\CashLogsController@postAjax');

Route::post('admin/cashlogs/postLogs', 'Admin\CashLogsController@postLogs');

//Route::get('admin/cashlogs', 'Admin\CashLogsController@index');

Route::resource('admin/cashlogs', 'Admin\CashLogsController');

Route::get('admin/checklogs', 'Admin\CheckLogsController@index');

Route::post('admin/checklogs', 'Admin\CheckLogsController@postReq');

Route::post('admin/adjustmentlogs/postLogs', 'Admin\AdjustmentLogsController@postLogs');

Route::get('admin/adjustmentlogs', 'Admin\AdjustmentLogsController@index');

Route::get('admin/branchcodes', 'Admin\BranchcodeController@index');

Route::post('admin/branchcodes', 'Admin\BranchcodeController@postAjax');

Route::resource('admin/cashpullouts', 'Admin\CashpulloutController');

Route::resource('admin/settings', 'Admin\SettingController');

//Route::get('admin/counts', 'Admin\ServersenteventController@counts')->name('adminssecounts');

/*----------------------------------------------------------------------------------------------------------------------
 * Accounting Colonnade Routes
 *----------------------------------------------------------------------------------------------------------------------
*/

Route::get('colacct/checking_accounts',function(){
	        
			$login_user = Auth::user();

            $login_user_firstname = $login_user->firstname;
            $login_user_lastname = $login_user->lastname;

            $login_user_type = Usertype::select('user_type_name')
                ->where('user_type_id', $login_user->privilege)
                ->first();

            $title = "Bank Reconciliation System - Colonnade Accounting";
            $pagetitle = "Dashboard";
            $userid = 1;

            $created = $login_user->created_at;

            return view('colacct.home', compact('title', 'pagetitle', 'users', 'users_percent', 'bs', 'bs_percent', 'dis', 'dis_percent', 'check', 'check_percent', 'login_user', 'userid', 'login_user_type', 'created'));
});

Route::get('colacct/checking_accounts/month_checks/{code}/{date}', 'ColAcct\CheckingaccountController@monthChecks');
Route::get('colacct/checking_accounts/match_checks/{code}/{date}', 'ColAcct\CheckingaccountController@matchChecks');

Route::get('colacct/reports/disbursement_summary/excel_report/{nav_setup_no}/{datein}/{bank}/{accountno}/{accountname}', 'ColAcct\ReportsController@generateExcel');
Route::get('colacct/reports/disbursement_summary/categories/{id}/{bankno}/{bank}/{accountno}/{accountname}', 'ColAcct\ReportsController@reportCategories');
Route::get('colacct/reports/disbursement_summary', 'ColAcct\ReportsController@listReports');

Route::get('colacct/checking_accounts/{code}/{date}', 'ColAcct\CheckingaccountController@listsChecks');
Route::get('colacct/checking_accounts/{code}', 'ColAcct\CheckingaccountController@categories');
//Route::get('colacct/checking_accounts/all_checks_match', 'ColAcct\CheckingaccountController@matchChecks');
Route::get('colacct/checking_accounts', 'ColAcct\CheckingaccountController@listAcc');

Route::get('colacct/upload_error', 'ColAcct\HomeController@uploadError');
Route::post('colacct/upload_progress', 'ColAcct\HomeController@uploadProgress');

Route::get('colacct/upload', 'ColAcct\HomeController@upload');

/*---------------------------------------------------------------------------------------------------------------------
 * CV Middleware
 *---------------------------------------------------------------------------------------------------------------------
 */
Route::get('dataCV','ViewController@dataCV');
Route::post('dataCVProcess','ViewController@dataCVProcess');

/*---------------------------------------------------------------------------------------------------------------------
 * CV Uploader
 *---------------------------------------------------------------------------------------------------------------------
 */
Route::get('CvDashboard',function(){
	return view('CV.dashboard');
});

Route::get('/cvUpload','AjaxController@companyUploader');

Route::post('cvUploader','ViewController@cvUploader');

/*----------------------------------------------------------------------------------------------------------------------
 * CV Excel File Uploaded Viewing
 *----------------------------------------------------------------------------------------------------------------------
*/
Route::get('CVperCom','ViewController@CVperCom');

Route::get('CVBUlist/{comID}','ViewController@BUlist');

Route::get('CVBankList/{data}','ViewController@BankList');

Route::get('CVMonthly/{data}','ViewController@cvList');

Route::get('CVviewing/{file}','ViewController@viewExcel');

Route::get('viewExcelDep/{file}','ViewController@viewExcelDep');


/*---------------------------------------------------------------------------------------------------------------------
 * Accounting CV Excel File Uploaded Viewing
 *---------------------------------------------------------------------------------------------------------------------
 */

Route::get('acctCVBankList/{com}/{bu}','ViewController@acctBankList');

Route::get('acctCVList/{data}','ViewController@acctCVList');

Route::post('/CVtoBS','CV\CheckVoucherController@store');



Route::get('data','Data\DataController@index');

/*
 * Treasury Routes
 */
Route::prefix('treasury')->group(function(){
	Route::get('forDeposit','Treasury\TreasuryController@forDeposit');
	Route::get('dailyDep/{date}','Treasury\TreasuryController@dailyDep');
	Route::get('viewDep/{date}','Treasury\TreasuryController@viewDep');
	
	Route::get('checkDetails/{checkClass}/{date}','Treasury\TreasuryController@checkdetails');
	Route::get('pdc/{checkClass}/{date}','Treasury\TreasuryController@pdc');
	Route::get('due/{checkClass}/{date}','Treasury\TreasuryController@due');
	
	Route::get('posting/{salesdate}/{depositdate}','Treasury\TreasuryController@postingLogs');
	Route::get('logPdf/{salesdate}/{depositdate}','Treasury\ReportPrintingController@logPdf');
	
	Route::get('addjustment/{date}','Treasury\TreasuryController@addAdjustment');
	Route::post('saveAdjustment','Treasury\TreasuryController@saveAdjustment');
	Route::get('editAdjustment/{id}','Treasury\TreasuryController@editAdjustment');
	Route::post('saveEditAdjustment/{id}','Treasury\TreasuryController@saveEditAdjustment');
	
	Route::post('saveEdit','Treasury\TreasuryController@saveEdit');
	
	Route::get('monDeposited','Treasury\TreasuryController@monDeposited');
	Route::get('dailyDeposited/{date}','Treasury\TreasuryController@dailyDeposited');
	Route::get('deposited/{date}','Treasury\TreasuryController@deposited');
	Route::get('viewSMDetails/{id}/{date}','Treasury\TreasuryController@viewSMDetails');
	
	Route::get('cashRelease','Treasury\TreasuryController@cashRelease');
	Route::get('release/{id}','Treasury\TreasuryController@release');
	
	Route::get('printAll/{date}','Treasury\TreasuryController@printAll');
});

Route::prefix('liquidation')->group(function(){
	Route::get('addCash','Liquidation\LiquidationController@addCash');
	Route::post('saveCash','Liquidation\LiquidationController@saveCash');
	Route::get('monthlyCash','Liquidation\LiquidationController@monthlyCash');
	Route::get('dailyCash/{date}','Liquidation\LiquidationController@dailyCash');
	Route::get('viewCash/{date}','Liquidation\LiquidationController@viewCash');
	Route::post('saveEdit','Liquidation\LiquidationController@saveEdit');
	Route::get('postingData/{date}','Liquidation\LiquidationController@postingData');
});

Route::prefix('cashpullout')->group(function(){
	
	Route::get('PrintData/{id}','CashPullOut\CPOController@PrintData');
	//borrower routes
	Route::get('CPOform','CashPullOut\CPOController@CPOform');
	Route::post('saveCashPullOut','CashPullOut\CPOController@saveCashPullOut');
	
	Route::get('viewRequest','CashPullOut\CPOController@viewRequest');
	Route::get('viewPending','CashPullOut\CPOController@viewPending');
	Route::get('viewApprove','CashPullOut\CPOController@viewApprove');
	Route::get('viewRelease','CashPullOut\CPOController@viewRelease');
	
	Route::get('viewPaid','CashPullOut\CPOController@viewPaid');
	Route::get('viewledger/{id}','CashPullOut\CPOController@viewledger');
	
	//approver routes
	Route::get('requestedCash','CashPullOut\CPOController@requestedCash');
	Route::get('approve/{id}','CashPullOut\CPOController@approve');
	Route::post('approveRequest','CashPullOut\CPOController@approveRequest');
	Route::get('approveRequestedCash','CashPullOut\CPOController@approveRequestedCash');
	Route::get('cpoReplenished','CashPullOut\CPOController@cpoReplenished');
	
	//payment routes
	Route::get('cpoList','CashPullOut\CPOController@cpoList');
	Route::get('payment/{id}','CashPullOut\CPOController@payment');
	Route::post('paymentsave','CashPullOut\CPOController@paymentsave');
});


Route::prefix('dtr')->group(function(){
	//finance
	// Route::get('dashboard','DTR\DTRController@dashboard');
	// Route::get('uploadDTR','DTR\DTRController@uploadDTR');
	// Route::get('bu/{com}','DTR\DTRController@bu');
	// Route::get('bankAcct/{com}/{bu}','DTR\DTRController@bankAcct');
	// Route::post('DTRsaving','DTR\DTRController@DTRsaving');
	
	// Route::get('allBanks/{buid}','DTR\DTRController@dtrBankAct');
	// Route::get('getBStable/{buid}/{bankID}','DTR\DTRController@bsTable');
	
	// Route::get('calendar/{bankID}/{buid}/{dateYear}/{dateMonth}','DTR\DTRController@monthsAndYearData');
	// Route::get('tabular/{bankID}/{buid}/{dateYear}/{dateMonth}','DTR\DTRController@tabular');
	// Route::get('daily/{bankID}/{buid}/{date}','DTR\DTRController@daily');
	
	
	Route::get('allbanks/{bank}','DTR\DTRController@allbanks');
	Route::get('getbankAcct/{bankId}','Api\DTRController@getbankAcct');
	Route::get('getListYear','Api\DTRController@getListYear');
	Route::post('DTRsaving','DTR\DTRController@DTRsaving');
	
	Route::get('errorbalance/{data}','DTR\DTRController@notBalance');
	Route::get('invalid/{message}','DTR\DTRController@invalidFormat');
	Route::post('showErrors','DTR\DTRController@showErrors');
	Route::get('read_bank_error/{filename}/{col}/{row}','DTR\DTRController@readExcel');
	
	Route::get('dataBank/{bankacct}/{com}/{bu}/{year}/{month}','DTR\DTRController@dataBank');
	Route::get('dataBankPerDate/{bankacct}/{com}/{bu}/{date}','DTR\DTRController@dataBankPerDate');
	
	Route::get('getBStable/{buid}/{bankID}','DTR\DTRController@bsTable');
	Route::get('calendar/{bankID}/{buid}/{dateYear}/{dateMonth}','DTR\DTRController@monthsAndYearData');
	
	//$baseUrl+"/dtr/excel/"+bankacct+"/"+com+"/"+bu+"/"+month+"/"+year;
	
	Route::get('excel/{bankacct}/{com}/{bu}/{month}/{year}','DTR\DTRController@getDTRExcel');
	
	Route::get('accountingDTR','DTR\DTRController@accountingDTR');
	Route::get('accountingBank/{bank}','DTR\DTRController@allbanksAccounting');
});

Route::prefix('designex')->group(function ($e) {
    Route::get('accounting','Designex\MainController@index')->name('designex.dashboard');
    Route::get('accounting/upload','Designex\UploadController@index')->name('xupload');
    Route::get('accounting/uploadui','Designex\UploadController@uploadui')->name('xuploadui');
    Route::post('accounting/upload','Designex\MainController@postReq')->name('xuploadtask');
    Route::get('accounting/disbursements','Designex\DisbursementController@index')->name('xdisburse');
    Route::post('accounting/disbursements','Designex\DisbursementController@all');
    Route::get('accounting/userprofile','Designex\UserController@index')->name('xprofile');
    Route::post('accounting/userprofile','Designex\UserController@update')->name('xprofileupdate');
    Route::get('accounting/prooflists','Designex\ProoflistController@index')->name('xprooflist');
    Route::post('accounting/prooflists','Designex\ProoflistController@post')->name('xgetprooflist');
    Route::post('accounting/prooflists/search','Designex\ProoflistController@search')->name('xsearchprooflist');
    Route::resource('accounting/general-settings','Designex\GeneralSettingController');
    Route::resource('accounting/sl','Designex\SlController');
    Route::resource('accounting/file-settings/transaction-types','Designex\FileSettingsController');
    Route::resource('accounting/file-settings/ledgers','Designex\LedgerController');
    Route::resource('accounting/file-settings/accounts','Designex\AccountController');
    Route::resource('accounting/reports','Designex\ReportController');
    Route::resource('accounting/file-data','Designex\FiledataController');
});


Route::prefix('deposit')->group(function(){
	Route::get('deplist','Deposit\DepMatchingController@deposit');
	Route::post('DepMatch','Deposit\DepMatchingController@depMatching');
	Route::get('monthlist/{bankno}','Deposit\DepMatchingController@loadMonthlist');
	Route::get('fileList/{bankID}','Deposit\DepMatchingController@fileList');
	Route::get('viewExcel/{file}','Deposit\DepMatchingController@viewExcel');
	Route::post('saveExcel','Deposit\DepositController@saveExcel');
	
	Route::post('countBS','Deposit\DepMatchingController@countBS');
	Route::post('depMatching','Deposit\DepMatchingController@depMatching');
	
	Route::get('dupEntry','Deposit\DepMatchingController@duplicateEntry');
	Route::get('plus5','Deposit\DepMatchingController@plus5days');
	Route::get('minus5','Deposit\DepMatchingController@minus5days');
	Route::get('dsnumber','Deposit\DepMatchingController@externalDoc');
	Route::get('branchCode','Deposit\DepMatchingController@branchCode');
	Route::get('unmatchBS','Deposit\DepMatchingController@unMatchBS');
	Route::get('unmatchBK','Deposit\DepMatchingController@unMatchBK');
	Route::get('depExcel','Deposit\DepMatchingController@depExcel');
	
});


Route::prefix('depExcel')->group(function(){
	Route::get('/deposit','Deposit\DepositExcelController@Deposit');
	Route::post('/depUploader','Deposit\DepositExcelController@depUploader')->name('depUploader');
});
/*---------------------------------------------------------------------------------------------------------------------
 * Testing
 *---------------------------------------------------------------------------------------------------------------------
 */
 
function loadDate($date,$format)
	{
		$d = \DateTime::createFromFormat($format, $date);
		// The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
		return $d && $d->format($format) === $date;
	}
 
 Route::get('testing',function(){
	//$departments = App\Department::all();
	$departments = \Illuminate\Support\Facades\DB::table('tab_data')->get();
	 dd($departments);
	 // $dtr = App\DTR::all();
	 // dd($dtr);
//	 "cs_deno_id" => 26
//        "emp_id" => "05438-2017"
//        "1k_q" => 1
//        "5h_q" => 2
//        "2h_q" => 3
//        "1h_q" => 4
//        "50p_q" => 5
//        "20p_q" => 2
//        "10p_q" => 1
//        "coins" => 22.0
//        "date_shrt" => "2018-09-21"
 	// $date  = date("Y-m-d");
	// $denno = App\Denomination::where('date_shrt',$date)->get();
	// return view('test',compact('denno'));

 	
// 	$br = App\BranchCode::find(5);
// 	echo "ñ";
// 	echo utf8_decode(utf8_encode('Ñ'));
 	
// 	$com  = App\Company::all();
// 	unset($com[0]);
// 	dd($com);
 //	echo 4%7;
// 	$checkNo = "BPI MAIN CHK#0000192129adere00";
//	 $varCheck    = preg_match('/\\d/', $checkNo);
//
//	 if($varCheck > 0)
//	 {
//		 $output = preg_replace( '/[^0-9]/', '', $checkNo );
//
//		 $checkNo = ltrim($output,'0');
//	 }
//	 echo $checkNo;
	 //echo loadDate('4/1/16','n/j/y') ."</br>";
//	 echo loadDate('01/31/2018','m/d/Y') ."</br>";
//	 echo "dfdf";
//	 $d = "11";
//	 if(strlen($d)==1)
//	 {
//		echo $d = '0'.$d;
//	 }
//	 else
//	 {
//		 echo $d;
//	 }
	// echo date("d",strtotime('8'));
 });

Route::get('testpdf/{salesdate}/{depositdate}','Treasury\ReportPrintingController@logPdf');
