<?php

namespace App\Http\Controllers\Admin;

use App\BankAccount;
use App\BankNo;
use App\Businessunit;
use App\Company;
use App\Money;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BankaccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;
        $banks = BankAccount::distinct()->get(['bank']);
        $title = "Bank Reconciliation System - Bank Accounts";
        $pagetitle = "Bank Accounts";
        $doctitle = "Bank accounts";
        $countall = BankAccount::all()->count();
        $counttrash = BankAccount::onlyTrashed()->count();

        if ($request->p=='trash') {
            $accounts = BankAccount::onlyTrashed()
                ->get(['id','bank','accountno','accountname','bankno','status','buid', 'company_code','added_by','modified_by','created_at','updated_at']);
            $template = 'trash';
        } else {
            $accounts = BankAccount::get(['id','bank','accountno','accountname','bankno','status','buid', 'company_code','added_by','modified_by','created_at','updated_at']);
            $template = 'all';
        }

        return view('admin.bankaccount.index', compact('accounts', 'title', 'pagetitle', 'banks', 'template', 'countall', 'counttrash', 'doctitle', 'login_user_firstname'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $codes = BankNo::get(['bankno', 'id']);
        $companies = Company::get(['company', 'company_code']);
        $bus = Businessunit::get(['unitid', 'bname', 'company_code']);
        $currencies = Money::get(['name', 'id']);
        $htmlcodes = '';
        $htmlcompanies = '';
        $htmlbus = '<option value="-1" data-ccode="-1">( Select Bussiness Unit )</option>';
        $htmlcurrencies = '';

        foreach ($codes as $code) {
            $htmlcodes .= '<option value="'.$code->id.'">'.$code->bankno.'</option>';
        }
        foreach ($companies as $company) {
            $htmlcompanies .= '<option value="'.$company->company_code.'">'.$company->company.'</option>';
        }
        foreach ($bus as $bu) {
            $htmlbus .= '<option value="'.$bu->unitid.'" data-ccode="'.$bu->company_code.'">'.$bu->bname.'</option>';
        }
        foreach ($currencies as $currency) {
            $htmlcurrencies .= '<option value="'.$currency->id.'">'.$currency->name.'</option>';
        }

        $args = [
            'codes' => $htmlcodes,
            'companies' => $htmlcompanies,
            'bus'       => $htmlbus,
            'currencies'    => $htmlcurrencies
        ];

        return response($args);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bankcode' => 'required',
            'bankname' => 'required',
            'accountno' => 'required|unique:bankaccount,accountno',
            'accountname' => 'required',
            'currency' => 'required',
            'company' => 'required',
            'businessunit' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'error','mes'=>$validator->errors()->first(),'action'=>'update']);
        }

        try {
            $login_userid = Auth::id();
            $acc = new BankAccount;
            $acc->bankno = $request->bankcode;
            $acc->bank = $request->bankname;
            $acc->accountno = $request->accountno;
            $acc->accountname = $request->accountname;
            $acc->money = $request->currency;
            $acc->company_code = $request->company;
            $acc->buid = $request->businessunit;
            switch($request->status) {

                case 0:
                    $acc->status = 'Active';
                    break;
                default:
                    $acc->status = "Inactive";
                    break;

            }
            $acc->remarks = $request->remarks;
            $acc->branchname = $request->branchname;
            $acc->bank_addr = $request->address;
            $acc->contact_person = $request->contact;
            $acc->added_by = $login_userid;
            $acc->modified_by = $login_userid;
            $acc->save();
            $response = [
                'id'            => $acc->id,
                'bank'          => $acc->bank,
                'accountno'     => $acc->accountno,
                'accountname'   => $acc->accountname,
                'bankno'        => $acc->bankcode->bankno,
                'company_code'  => $acc->company->company,
                'buid'          => $acc->businessunit->bname,
                'created_at'    => $acc->created_at->format('F d, Y'),
                'updated_at'    => $acc->updated_at->format('F d, Y'),
                'status'        => $acc->status,
                'added_by'      => $acc->user1->firstname . ' ' . $acc->user1->lastname,
                'modified_by'   => $acc->user2->firstname . ' ' . $acc->user2->lastname
            ];
            return response($response);
        } catch (Exception $err) {
            dd($err);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $codes = BankNo::get(['bankno', 'id']);
        $currencies = Money::get(['name', 'id']);
        $companies = Company::get(['company', 'company_code']);
        $bus = Businessunit::get(['unitid', 'bname', 'company_code']);
        $htmlcodes = '';
        $htmlcurrencies = '';
        $htmlcompanies = '';
        $htmlbus = '<option value="-1" data-ccode="-1">( Select Bussiness Unit )</option>';
        $htmlstatus = '';
        $account = BankAccount::find($id);
        foreach ($codes as $code) {
            if ($code->id == $account->bankno) {
                $htmlcodes .= '<option value="'.$code->id.'" selected>'.$code->bankno.'</option>';
            } else {
                $htmlcodes .= '<option value="'.$code->id.'">'.$code->bankno.'</option>';
            }
        }
        foreach ($currencies as $currency) {
            if (strtolower($currency->name) == strtolower($account->money)) {
                $htmlcurrencies .= '<option value="'.$currency->id.'" selected>'.$currency->name.'</option>';
            } else {
                $htmlcurrencies .= '<option value="'.$currency->id.'">'.$currency->name.'</option>';
            }
        }
        foreach ($companies as $company) {
            if (strtolower($company->company_code) == strtolower($account->company->company_code)) {
                $htmlcompanies .= '<option value="'.$company->company_code.'" selected>'.$company->company.'</option>';
            } else {
                $htmlcompanies .= '<option value="'.$company->company_code.'">'.$company->company.'</option>';
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->action == 'restore') {
            $acc = BankAccount::withTrashed()
                ->where('id', $id)
                ->restore();
            return response()->json(['id'=>$id]);
        }
        $validator = Validator::make($request->all(), [
            'bankcode' => 'required',
            'bankname' => 'required',
            'accountno' => 'required|unique:bankaccount,accountno,'.$id,
            'accountname' => 'required',
            'currency' => 'required',
            'company' => 'required',
            'businessunit' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'error','mes'=>$validator->errors()->first(),'action'=>'update']);
        }

        try {

            $login_userid = Auth::id();
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
            $acc->modified_by = $login_userid;
            $acc->save();

            $response = [
                'bank'          => $acc->bank,
                'accountno'     => $acc->accountno,
                'accountname'   => $acc->accountname,
                'bankno'        => $acc->bankcode->bankno,
                'company_code'  => $acc->company->company,
                'buid'          => $acc->businessunit->bname,
                'status'        => $acc->status,
                'modified_by'   => $acc->user2->firstname . ' ' . $acc->user2->lastname,
                'updated_at'    => $acc->updated_at->format('F d, Y'),
            ];
            return response($response);
        }catch (Exception $e) {
            dd($e);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        try {
            if ($request->action == 'trash') {
                $code = BankAccount::findOrFail($id);
                $code->delete();
                return response()->json(['id'=>$id]);
            }
            $code = BankAccount::withTrashed()
                ->where('id', $id)
                ->forceDelete();
            return response()->json(['id'=>$id]);
        }catch (Exception $e) {
            dd($e);
        }
    }

    public function selectedAction(Request $request) {
        $data = json_decode($request->data);
        switch ($request->action) {
            case 'trash':
                $code = BankAccount::destroy($data);
                return response($code);
                break;
            case 'delete':
                foreach ($data as $ids) {
                    $code = BankAccount::withTrashed()
                        ->where('id', $ids)
                        ->forceDelete();
                }
                return response($data);
                break;
            case 'restore':
                foreach ($data as $ids) {
                    $code = BankAccount::withTrashed()
                        ->where('id', $ids)
                        ->restore();
                }
                return response($data);
                break;
            default:
                break;
        }
    }
}
