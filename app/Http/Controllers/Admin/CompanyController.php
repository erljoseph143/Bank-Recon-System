<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function allCompany() {

        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;
        $login_user_lastname = $login_user->lastname;

        $companies = Company::all();

        $countall = Company::all()->count();
        $counttrash = Company::onlyTrashed()->count();

        $title = "Bank Reconciliation System - Companies";
        $pagetitle = "Companies";
        $doctitle = "Companies";

        $template = 'all';

        return view('admin.company.index', compact('companies', 'title', 'pagetitle', 'template', 'countall','counttrash','doctitle', 'login_user_firstname'));
    }

    public function allTrash() {

        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;
        $login_user_lastname = $login_user->lastname;

        $companies = Company::onlyTrashed()
            ->get();

        $countall = Company::all()->count();
        $counttrash = Company::onlyTrashed()->count();

        $title = "Bank Reconciliation System - Companies";
        $pagetitle = "Companies";
        $doctitle = "Companies";

        $template = 'trash';

        return view('admin.company.index', compact('companies', 'title', 'pagetitle', 'template', 'countall','counttrash','doctitle', 'login_user_firstname'));
    }

    public function saveCompany(Request $request) {

        $login_userid = Auth::id();

        if (empty($request->company)) {
            return 0;
        }

        if (empty($request->acronym)) {
            return 1;
        }

        $findCompany = Company::select(DB::raw('COUNT(company) as count'))
            ->where(DB::raw('LOWER(company)'), $request->company)
            ->first();

        if ($findCompany->count > 0) {
            return -1;
        }

        $company = new Company();

        $company->company = strtoupper($request->company);
        $company->acroname = strtoupper($request->acronym);
        $company->added_by = $login_userid;
        $company->modified_by = $login_userid;
        $company->save();

        $created = $company->created_at->format('F d, Y');
        $updated = $company->updated_at->format('F d, Y');
//
        $user = User::select('firstname', 'lastname')->where('user_id', $company->added_by)->first();
//
        $user2 = User::select('firstname', 'lastname')->where('user_id', $company->modified_by)->first();
//
        $response = [
            'company_code'  => $company->company_code,
            'company'   => $company->company,
            'acronym'   => $company->acroname,
            'created_at'=> $created,
            'added_by'  => $user->firstname . ' ' . $user->lastname,
            'modified_by'=> $updated,
            'updated_at'=> $user2->firstname . ' ' . $user2->lastname,
        ];

        return response($response);
    }

    public function getCompany($id) {
        $data = json_decode($id);

        if ($data[1] == 'restore') {
            $company = Company::withTrashed()
                ->where('company_code', $data[0])
                ->restore();

            return \response($company);
        }

        $company = Company::find($data[0]);

        return response($company);
    }

    public function updateCompany(Request $request, $id) {

        if (empty($request->company)) {
            return 0;
        }

        if (empty($request->acronym)) {
            return 1;
        }

        $findCompany = Company::select(DB::raw('COUNT(company) as count'))
            ->where('company', $request->company)
            ->where('company_code', '!=',$id)
            ->first();

        if ($findCompany->count > 0) {
            return -1;
        }

        try {
            $login_userid = Auth::id();

            $company = Company::find($id);
            $company->company = strtoupper($request->company);
            $company->acroname = strtoupper($request->acronym);
            $company->modified_by = $login_userid;
            $company->save();

            $user = User::select('firstname', 'lastname')->where('user_id', $company->modified_by)->first();

            $updated = $company->updated_at->format('F d, Y');

            $response = [
                'company'   => $company->company,
                'acronym'   => $company->acroname,
                'modifiedby'=> $user->firstname . ' ' . $user->lastname,
                'updated_at'=> $updated,
            ];

            return response($response);
        }catch (Exception $e) {
            dd($e);
        }
    }

    public function trashCompany($id) {

        try {
            $data = json_decode($id);

            if ($data[1] == 'delete') {
                $company = Company::withTrashed()
                    ->where('company_code', $data[0])
                    ->forceDelete();

                return $company;
            }

            $company = Company::findOrFail($data[0]);
            $company->delete();

            return response($company);

        }catch (Exception $e) {
            dd($e);
        }

    }

    public function trashSelectedCompany($ids) {

        $data = json_decode($ids, true);

        switch ($data['action']) {
            case 'trashallselected':
                $company = Company::destroy($data['data']);
                return response($company);
                break;

            case 'deleteallselected':
                foreach ($data['data'] as $ids) {
                    $company = Company::withTrashed()
                        ->where('company_code', $ids)
                        ->forceDelete();
                }

                return \response($company);
                break;

            case 'restoreallselected':
                foreach ($data['data'] as $ids) {
                    $company = Company::withTrashed()
                        ->where('company_code', $ids)
                        ->restore();
                }

                return \response($company);
                break;

            default:
                # code...
                break;
        }

    }
}
