<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function allCompany() {
        $companies = Company::all();

        $countall = Company::all()->count();
        $counttrash = Company::onlyTrashed()->count();

        $title = "Bank Reconciliation System - Companies";
        $pagetitle = "Companies";
        $doctitle = "Companies";

        $template = 'all';

        return view('admin.company.index', compact('companies', 'title', 'pagetitle', 'template', 'countall','counttrash','doctitle'));
    }

    public function allTrash() {
        $companies = Company::onlyTrashed()
            ->get();

        $countall = Company::all()->count();
        $counttrash = Company::onlyTrashed()->count();

        $title = "Bank Reconciliation System - Companies";
        $pagetitle = "Companies";
        $doctitle = "Companies";

        $template = 'trash';

        return view('admin.company.index', compact('companies', 'title', 'pagetitle', 'template', 'countall','counttrash','doctitle'));
    }

    public function saveCompany(Request $request) {

        $company = new Company();

        $company->company = $request->company;
        $company->acroname = $request->acronym;
        $company->added_by = 173;
        $company->modified_by = 173;
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
        try {
            $company = Company::find($id);
            $company->company = $request->company;
            $company->acroname = $request->acronym;
            $company->modified_by = 173;
            $company->save();

            $user = User::select('firstname', 'lastname')->where('user_id', $company->modified_by)->first();

            $response = [
                'company'   => $company->company,
                'acronym'   => $company->acroname,
                'modifiedby'=>$user
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
