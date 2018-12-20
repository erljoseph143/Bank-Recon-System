<?php

namespace App\Http\Controllers\Admin;

use App\BankAccount;
use App\BankNo;
use App\Businessunit;
use App\Company;
use App\Money;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class BankaccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function allBankaccounts()
    {
        $accounts = BankAccount::all();

        //dd($accounts);

        $banks = BankAccount::distinct()->get(['bank']);

        $title = "Bank Reconciliation System - Bank Accounts";

        $pagetitle = "Bank Accounts";

        $doctitle = "Bank accounts";

        $template = 'all';

        $countall = BankAccount::all()->count();

        $counttrash = BankAccount::onlyTrashed()->count();

        return view('admin.bankaccount.index', compact('accounts', 'title', 'pagetitle', 'banks', 'template', 'countall', 'counttrash', 'doctitle'));
    }

    public  function allTrashAccounts() {

        $accounts = BankAccount::onlyTrashed()
            ->get();

        $banks = BankAccount::distinct()->get(['bank']);

        $countall = BankAccount::all()->count();

        $counttrash = BankAccount::onlyTrashed()->count();

//        $query = "
//            SELECT CONCAT(MONTHNAME(created_at), ' ', YEAR(created_at)) as created_at
//            FROM bankno
//            GROUP BY YEAR(created_at) DESC, MONTH(created_at) DESC 
//        ";
//
//        $date = DB::select($query);

        $title = "Bank Reconciliation System - Bank Accounts";
        $pagetitle = "Bank Accounts";
        $doctitle = "Bank accounts";

        $template = 'trash';

        return view('admin.bankaccount.index', compact('accounts', 'title', 'doctitle', 'pagetitle', 'countall', 'date', 'template', 'counttrash', 'banks'));

    }

    public function createBankAccount() {

        $codes = BankNo::all()->pluck('bankno', 'id');

        $companies = Company::all()->pluck('company', 'company_code');

        $bus = Businessunit::select('unitid', 'bname', 'company_code')->get();

        $currencies = Money::all()->pluck('name', 'id');

        $htmlcodes = '';
        $htmlcompanies = '';
        $htmlbus = '<option value="-1" data-ccode="-1">( Select Bussiness Unit )</option>';
        $htmlcurrencies = '';

        foreach ($codes as $key => $code) {
            $htmlcodes .= '<option value="'.$key.'">'.$code.'</option>';
        }

        foreach ($companies as $key => $company) {
            $htmlcompanies .= '<option value="'.$key.'">'.$company.'</option>';
        }

        foreach ($bus as $key => $bu) {
            $htmlbus .= '<option value="'.$bu->unitid.'" data-ccode="'.$bu->company_code.'">'.$bu->bname.'</option>';
        }

        foreach ($currencies as $key => $currency) {
            $htmlcurrencies .= '<option value="'.$key.'">'.$currency.'</option>';
        }

        $args = [
            'codes' => $htmlcodes,
            'companies' => $htmlcompanies,
            'bus'       => $htmlbus,
            'currencies'    => $htmlcurrencies
        ];

        return response($args);
    }

    public function saveBankAccount(Request $request) {

        if (empty($request->bankcode)) {
            return 0;
        }

        if (empty($request->bankname)) {
            return 1;
        }

        if (empty($request->accountno)) {
            return 2;
        }

        $countaccountno = BankAccount::select(DB::raw('COUNT(id) as count'))
            ->where('accountno', $request->accountno)
            ->first();

        if ($countaccountno->count > 0) {
            return 3;
        }

        if (empty($request->accountname)) {
            return 4;
        }

        if (empty($request->currency)) {
            return 5;
        }

        if (empty($request->company)) {
            return 6;
        }

        if (empty($request->businessunit)) {
            return 7;
        }

        if ($request->businessunit == -1) {
            return 8;
        }

        if ($request->status == "") {
            return 9;
        }

        try {

            $acc = new BankAccount;
            $acc->bankno = $request->bankcode;
            $acc->bank = $request->bankname;
            $acc->accountno = $request->accountno;
            $acc->accountname = $request->accountname;
            $acc->money = $request->currency;
            $acc->company_code = $request->company;
            $acc->buid = $request->businessunit;
            switch($request->status) {

                case 1:
                    $acc->status = 'Active';
                    break;
                default:
                    $acc->status = "Inactive";
                    break;

            }
            if ($request->remarks == '') {
                $acc->remarks = '';
            } else {
                $acc->remarks = $request->remarks;
            }
            if ($request->branchname == '') {
                $acc->branchname = '';
            } else {
                $acc->branchname = $request->branchname;
            }
            if ($request->address == '') {
                $acc->bank_addr = '';
            } else {
                $acc->bank_addr = $request->address;
            }
            if ($request->contact == '') {
                $acc->contact_person = '';
            } else {
                $acc->contact_person = $request->contact;
            }
            $acc->added_by = 163;
            $acc->modified_by = 163;
            $acc->save();

            $code = BankNo::select('bankno')
                ->where('id', $acc->bankno)
                ->pluck('bankno');

            $company = Company::select('company')
                ->where('company_code', $acc->company_code)
                ->pluck('company');

            $bu = Businessunit::select('bname')
                ->where('unitid', $acc->buid)
                ->pluck('bname');

            $user = User::select('firstname', 'lastname')->where('user_id', $acc->added_by)->first();

            $user2 = User::select('firstname', 'lastname')->where('user_id', $acc->modified_by)->first();

            $acc->bankno = $code[0];
            $acc->company_code = $company[0];
            $acc->buid = $bu[0];

            $created = $acc->created_at->format('F d, Y');
            $updated = $acc->updated_at->format('F d, Y');

            $response = [
                'bank'          => $acc->bank,
                'accountno'     => $acc->accountno,
                'accountname'   => $acc->accountname,
                'bankno'        => $code[0],
                'company_code'  => $company[0],
                'buid'          => $bu[0],
                'created_at'    => $created,
                'updated_at'    => $updated,
                'status'        => $acc->status,
                'added_by'      => $user->firstname . ' ' . $user->lastname,
                'modified_by'   => $user2->firstname . ' ' . $user2->lastname
            ];

            return response($response);

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function getAccount($id) {

        $data = json_decode($id);

        if ($data[1] == 'restore') {

            $acc = BankAccount::withTrashed()
                ->where('id', $data[0])
                ->restore();

            return \response($acc);
        }

        $codes = BankNo::all()->pluck('bankno', 'id');

        $currencies = Money::all()->pluck('name', 'id');

        $companies = Company::all()->pluck('company', 'company_code');

        $bus = Businessunit::select('unitid', 'bname', 'company_code')->get();

        $htmlcodes = '';
        $htmlcurrencies = '';
        $htmlcompanies = '';
        $htmlbus = '<option value="-1" data-ccode="-1">( Select Bussiness Unit )</option>';
        $htmlstatus = '';

//        if ($data[1] == 'restore') {
//
//            $code = BankNo::withTrashed()
//                ->where('id', $data[0])
//                ->restore();
//
//            return \response($code);
//        }
        $account = BankAccount::find($data[0]);

        foreach ($codes as $key => $code) {
            if ($key == $account->bankno) {
                $htmlcodes .= '<option value="'.$key.'" selected>'.$code.'</option>';
            } else {
                $htmlcodes .= '<option value="'.$key.'">'.$code.'</option>';
            }

        }

        foreach ($currencies as $key => $currency) {
            if (strtolower($currency) == strtolower($account->money)) {
                $htmlcurrencies .= '<option value="'.$key.'" selected>'.$currency.'</option>';
            } else {
                $htmlcurrencies .= '<option value="'.$key.'">'.$currency.'</option>';
            }
        }

        foreach ($companies as $key => $company) {
            if (strtolower($key) == strtolower($account->company->company_code)) {
                $htmlcompanies .= '<option value="'.$key.'" selected>'.$company.'</option>';
            } else {
                $htmlcompanies .= '<option value="'.$key.'">'.$company.'</option>';
            }

        }

        foreach ($bus as $key => $bu) {
            if (strtolower($bu->unitid) == strtolower($account->buid)) {
                $htmlbus .= '<option value="'.$bu->unitid.'" data-ccode="'.$bu->company_code.'" selected>'.$bu->bname.'</option>';
            } else {
                $htmlbus .= '<option value="'.$bu->unitid.'" data-ccode="'.$bu->company_code.'">'.$bu->bname.'</option>';
            }
        }

        $status = [
            'active',
            'inactive',
            'unknown'
        ];

        foreach ($status as $key => $stats) {
            if (strtolower($stats) == strtolower($account->status)) {
                $htmlstatus .= '<option value="'.$key.'" selected>'.ucfirst($stats).'</option>';
            } else {
                $htmlstatus .= '<option value="'.$key.'">'.ucfirst($stats).'</option>';
            }

        }

        $response = [
            'htmlcodes'     => $htmlcodes,
            'bank'          => $account->bank,
            'accountno'     => $account->accountno,
            'accountname'   => $account->accountname,
            'money'         => $htmlcurrencies,
            'company_code'  => $htmlcompanies,
            'bu'            => $htmlbus,
            'status'        => $htmlstatus,
            'remarks'       => $account->remarks,
            'branchname'    => $account->branchname,
            'address'       => $account->bank_addr,
            'contact'       => $account->contact_person,
            'id'            => $account->id,
        ];

        return response($response);

    }

    public function updateAccount( Request $request, $id ) {

        if (empty($request->bankcode)) {
            return 0;
        }

        if (empty($request->bankname)) {
            return 1;
        }

        if (empty($request->accountno)) {
            return 2;
        }

        $countaccountno = BankAccount::select(DB::raw('COUNT(id) as count'))
            ->where('accountno', $request->accountno)
            ->where('id', '!=', $id)
            ->first();

        if ($countaccountno->count > 0) {
            return 3;
        }



        try {
            $acc = BankAccount::find($id);
            $acc->bankno = $request->bankcode;
            $acc->bank = $request->bankname;
            $acc->accountno = $request->accountno;
            $acc->accountname = $request->accountname;

            switch ($request->currency) {
                case 1:
                    $acc->money = 'Php';
                    break;
                case 2:
                    $acc->money = "Euro";
                    break;
                case 3:
                    $acc->money = "Dollar";
                    break;
                default:
                    break;

            }


            $acc->company_code = $request->company;
            $acc->buid = $request->businessunit;

            if ($request->status == 0) {
                $acc->status = 'Active';
            } elseif($request->status == 1) {
                $acc->status = 'Inactive';
            } else {
                $acc->status = 'Unknown';
            }

            $acc->remarks = $request->remarks;
            $acc->branchname = $request->branchname;
            $acc->bank_addr = $request->address;
            $acc->contact_person = $request->contact;

            $acc->save();

            $code = BankNo::select('bankno')
                ->where('id', $acc->bankno)
                ->pluck('bankno');

            $company = Company::select('company')
                ->where('company_code', $acc->company_code)
                ->pluck('company');

            $bu = Businessunit::select('bname')
                ->where('unitid', $acc->buid)
                ->pluck('bname');

            $response = [
                'bank'          => $acc->bank,
                'accountno'     => $acc->accountno,
                'accountname'   => $acc->accountname,
                'bankno'        => $code[0],
                'company_code'  => $company[0],
                'buid'          => $bu[0],
                'status'        => $acc->status,
            ];

            return response($response);
        }catch (Exception $e) {
            dd($e);
        }

    }

    public function trashAccount($id) {

        try {
            $data = json_decode($id);

            if ($data[1] == 'delete') {
                $code = BankAccount::withTrashed()
                    ->where('id', $data[0])
                    ->forceDelete();

                return $code;
            }

            $code = BankAccount::findOrFail($data[0]);
            $code->delete();

            return response($code);

        }catch (Exception $e) {
            dd($e);
        }

    }

    public function trashSelectedAccount( $ids ) {

        $data = json_decode($ids, true);

        switch ($data['action']) {
            case 'trashallselected':
                $code = BankAccount::destroy($data['data']);
                return response($code);
                break;

            case 'deleteallselected':
                foreach ($data['data'] as $ids) {
                    $code = BankAccount::withTrashed()
                        ->where('id', $ids)
                        ->forceDelete();
                }

                return \response($data['data']);
                break;

            case 'restoreallselected':
                foreach ($data['data'] as $ids) {
                    $code = BankAccount::withTrashed()
                        ->where('id', $ids)
                        ->restore();
                }

                return \response($data['data']);
                break;

            default:
                # code...
                break;
        }

    }
}
