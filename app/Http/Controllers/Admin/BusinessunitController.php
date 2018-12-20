<?php

namespace App\Http\Controllers\Admin;

use App\Businessunit;
use App\Company;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BusinessunitController extends Controller
{
    protected $pagetitle = "Business units";
    protected $title = "Bank Reconciliation System - Businessunits";

    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function allBU($id) {

        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;
        $login_user_lastname = $login_user->lastname;

        $bus = Businessunit::select('unitid', 'bname', 'created_at', 'updated_at', 'added_by', 'modified_by', 'company_code')
        ->where('company_code', $id)
        ->get();

        $companies = Company::find($id);

        $countall = Businessunit::select('unitid', 'bname', 'created_at', 'updated_at', 'added_by', 'modified_by')
            ->where('company_code', $id)
            ->count();
        $counttrash = Businessunit::onlyTrashed()
            ->where('company_code', $id)
            ->count();

        $title = $this->title;

        $pagetitle = $this->pagetitle;

        $doctitle = $pagetitle;

        $companyid = $id;

        $template = 'all';

        $url = url('admin/company/'.$id.'/businessunits');

        $trashurl = url('admin/company/'.$id.'/businessunits/trash');

        return view('admin.businessunit.index', compact('bus', 'countall', 'counttrash', 'title', 'pagetitle', 'doctitle', 'template', 'url', 'trashurl', 'companyid', 'companies', 'login_user_firstname'));

    }

    public function allTrash($id) {

        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;
        $login_user_lastname = $login_user->lastname;

        $bus = Businessunit::onlyTrashed()
            ->where('company_code', $id)
            ->get();

        $companies = Company::find($id);

        $countall = Businessunit::all()
            ->where('company_code', $id)
            ->count();
        $counttrash = Businessunit::onlyTrashed()
            ->where('company_code', $id)
            ->count();

        $title = $this->title;
        $pagetitle = $this->pagetitle;
        $doctitle = $pagetitle;

        $companyid = $id;

        $template = 'trash';

        $url = url('admin/company/'.$id.'/businessunits');
        $trashurl = url('admin/company/'.$id.'/businessunits/trash');

        return view('admin.businessunit.index', compact('bus', 'title', 'pagetitle', 'template', 'countall','counttrash', 'url', 'trashurl', 'companyid', 'companies', 'doctitle', 'login_user_firstname'));

    }

    public function saveBU(Request $request) {

        if (empty($request->bname)) {
            return 0;
        }

        if (empty($request->company)) {
            return 1;
        }

        $findBU = Businessunit::select(DB::raw('COUNT(bname) as count'))
            ->where('bname', $request->bname)
            ->where('company_code', $request->company)
            ->first();

        if ($findBU->count > 0) {
            return -1;
        }

        $login_userid = Auth::id();

        $bu = new Businessunit();

        $bu->bname = strtoupper($request->bname);
        $bu->added_by = $login_userid;
        $bu->modified_by = $login_userid;
        $bu->company_code = $request->company;
        $bu->save();

        $created = $bu->created_at->format('F d, Y');
        $updated = $bu->updated_at->format('F d, Y');
//
        $user1 = User::select('firstname', 'lastname')->where('user_id', $bu->added_by)->first();
////
        $user2 = User::select('firstname', 'lastname')->where('user_id', $bu->modified_by)->first();
//
        $response = [
            'unitid'        => $bu->unitid,
            'bname'         => $bu->bname,
            'created_at'    => $created,
            'added_by'      => $user1->firstname . ' ' . $user1->lastname,
            'modified_by'   => $updated,
            'updated_at'    => $user2->firstname . ' ' . $user2->lastname,
        ];

        return response($response);

    }

    public function trashBU($id) {

        try {
            $data = json_decode($id);

            if ($data[1] == 'delete') {
                $bu = Businessunit::withTrashed()
                    ->where('unitid', $data[0])
                    ->forceDelete();

                return response($bu);
            }

            $bu = Businessunit::findOrFail($data[0]);
            $bu->delete();

            return response($data);

        }catch (Exception $e) {
            dd($e);
        }

    }

    public  function getBU($id) {
        $data = json_decode($id);

        if ($data[1] == 'restore') {
            $company = Businessunit::withTrashed()
                ->where('unitid', $data[0])
                ->restore();

            return \response($company);
        }

        $company = Businessunit::find($data[0]);

        return response($company);
    }

    public function updateBU(Request $request, $id) {

        if (empty($request->bname)) {
            return 0;
        }

        if (empty($request->company)) {
            return 1;
        }

        $findCompany = Businessunit::select(DB::raw('COUNT(bname) as count'))
            ->where('bname', $request->bname)
            ->where('company_code', $request->company)
            ->where('unitid', '!=',$id)
            ->first();

        if ($findCompany->count > 0) {
            return -1;
        }

        try {
            $login_userid = Auth::id();

            $bu = Businessunit::find($id);
            $bu->bname = strtoupper($request->bname);
            $bu->modified_by = $login_userid;
            $bu->save();

            $user = User::select('firstname', 'lastname')->where('user_id', $bu->modified_by)->first();
            $updated = $bu->updated_at->format('F d, Y');

            $response = [
                'bname'         => $bu->bname,
                'updated_at'    => $updated,
                'modifiedby'    => $user->firstname . ' ' . $user->lastname
            ];

            return response($response);
        }catch (Exception $e) {
            dd($e);
        }

    }

    public function trashSelectedBU($ids) {

        $data = json_decode($ids, true);

        switch ($data['action']) {
            case 'trashallselected':
                $bu = Businessunit::destroy($data['data']);
                return response($bu);
                break;

            case 'deleteallselected':
                foreach ($data['data'] as $ids) {
                    $bu = Businessunit::withTrashed()
                        ->where('unitid', $ids)
                        ->forceDelete();
                }

                return \response($bu);
                break;

            case 'restoreallselected':
                foreach ($data['data'] as $ids) {
                    $bu = Businessunit::withTrashed()
                        ->where('unitid', $ids)
                        ->restore();
                }

                return \response($bu);
                break;

            default:
                # code...
                break;
        }

    }

}
