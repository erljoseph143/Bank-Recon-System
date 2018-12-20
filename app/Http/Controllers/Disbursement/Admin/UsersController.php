<?php

namespace App\Http\Controllers\Admin;

use App\Businessunit;
use App\Company;
use App\User;
use App\Usertype;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function allUsers()
    {
        $users = User::all();

        $bunits = User::distinct()->get(['bunitid']);

        $title = "Bank Reconciliation System - Users";

        $pagetitle = "Users";

        $doctitle = "users";

        $template = 'all';

        $countall = User::all()->count();

        $counttrash = User::onlyTrashed()->count();

        //dd($users);

        return view('admin.user.index', compact('users','title','pagetitle', 'doctitle', 'bunits', 'template', 'counttrash', 'countall'));
    }

    public function getUser($id) {

        $data = json_decode($id);

        if ($data[1] == 'restore') {
            $user = User::withTrashed()
                ->where('user_id', $data[0])
                ->restore();

            return \response($user);
        }

        $user = User::find($data[0]);

        $companies = Company::all()->pluck('company', 'company_code');

        $bus = Businessunit::select('unitid', 'bname', 'company_code')->get();

        $usertypes = Usertype::all();

        $htmlcompanies = "";
        $htmlbus = '<option value="-1" data-ccode="-1">( Select Bussiness Unit )</option>';
        $htmlusertype = "";

        foreach ($companies as $key => $company) {
            if (strtolower($key) == strtolower($user->company_id)) {
                $htmlcompanies .= '<option value="'.$key.'" selected>'.$company.'</option>';
            } else {
                $htmlcompanies .= '<option value="'.$key.'">'.$company.'</option>';
            }
        }

        foreach ($bus as $key => $bu) {
            if (strtolower($bu->unitid) == strtolower($user->bunitid)) {
                $htmlbus .= '<option value="'.$bu->unitid.'" data-ccode="'.$bu->company_code.'" selected>'.$bu->bname.'</option>';
            } else {
                $htmlbus .= '<option value="'.$bu->unitid.'" data-ccode="'.$bu->company_code.'">'.$bu->bname.'</option>';
            }
        }

        foreach ($usertypes as $usertype) {
            if (strtolower($usertype->user_type_id) == strtolower($user->privilege)) {
                $htmlusertype .= '<option value="'.$usertype->user_type_id.'" selected>'.$usertype->user_type_name.'</option>';
            } else {
                $htmlusertype .= '<option value="'.$usertype->user_type_id.'">'.$usertype->user_type_name.'</option>';
            }
        }

        $response = [
            'firstname'     => $user->firstname,
            'lastname'      => $user->lastname,
            'username'      => $user->username,
            'gender'        => $user->gender,
            'privilege'     => $htmlusertype,
            'company'       => $htmlcompanies,
            'businessunit'  => $htmlbus,
            'user_id'       => $user->user_id
        ];

        return response($response);
    }

    public function allTrash() {

        $users = User::onlyTrashed()->get();

        $bunits = User::distinct()->get(['bunitid']);

        $title = "Bank Reconciliation System - Users";
        $pagetitle = "Users";
        $doctitle = "users";

        $countall = User::all()->count();

        $counttrash = User::onlyTrashed()->count();

        $template = 'trash';

        return view('admin.user.index', compact('users', 'title', 'doctitle', 'bunits', 'pagetitle', 'countall', 'template', 'counttrash'));
    }

    public function trashUser( $id ) {

        try {
            $data = json_decode($id);

            if ($data[1] == 'delete') {
                $code = User::withTrashed()
                    ->where('user_id', $data[0])
                    ->forceDelete();
//
                return $code;
            }
//
            $code = User::findOrFail($data[0]);
            $code->delete();

            return response($data);

        }catch (Exception $e) {
            dd($e);
        }

    }

    public function createUser() {

        $companies = Company::all()->pluck('company', 'company_code');

        $bus = Businessunit::select('unitid', 'bname', 'company_code')->get();

        $usertypes = Usertype::all();

        $htmlcompanies = "";
        $htmlbus = '<option value="-1" data-ccode="-1">( Select Bussiness Unit )</option>';
        $htmlusertype = "";

        foreach ($companies as $key => $company) {
            $htmlcompanies .= '<option value="'.$key.'">'.$company.'</option>';
        }

        foreach ($bus as $key => $bu) {
            $htmlbus .= '<option value="'.$bu->unitid.'" data-ccode="'.$bu->company_code.'">'.$bu->bname.'</option>';
        }

        foreach ($usertypes as $usertype) {
            $htmlusertype .= '<option value="'.$usertype->user_type_id.'">'.$usertype->user_type_name.'</option>';
        }

        $response = [
            'companies' => $htmlcompanies,
            'bus'       => $htmlbus,
            'usertypes'  => $htmlusertype
        ];

        return response($response);

    }

    public function saveUser(Request $request) {

        try {

            $acc = new User();

            $acc->firstname = $request->firstname;
            $acc->lastname = $request->lastname;
            $acc->username = $request->username;
            $acc->password = $request->password;
            $acc->company_id = $request->company;
            $acc->privilege = $request->privilege;
            $acc->bunitid = $request->bu;
            $acc->gender = $request->gender;
            $acc->status = 'a';
            $acc->added_by = '173';
            $acc->modified_by = '173';
            $acc->module_type = 'Bank Recon';

            $acc->save();

            $bu = Businessunit::select('bname')
                ->where('unitid', $acc->bunitid)
                ->pluck('bname');

            $usertype = Usertype::select('user_type_name')
                ->where('user_type_id', $acc->privilege)
                ->pluck('user_type_name');

            $user = User::select('firstname', 'lastname')->where('user_id', $acc->added_by)->first();

            $user2 = User::select('firstname', 'lastname')->where('user_id', $acc->modified_by)->first();

            $created = $acc->created_at->format('F d, Y');
            $updated = $acc->updated_at->format('F d, Y');

            $response = [
                'firstname'         => $acc->firstname,
                'lastname'          => $acc->lastname,
                'username'          => $acc->username,
                'privilege'         => $usertype[0],
                'businessunit'      => $bu[0],
                'created_at'        => $created,
                'added_by'          => $user->firstname,
                'updated_at'        => $updated,
                'modified_by'   => $user2->firstname
            ];

            return response($response);

        } catch (Exception $e) {
            dd($e);
        }

    }

    public function updateUser(Request $request, $id) {
        try {
            $user = User::find($id);
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->username = $request->username;
            $user->gender = $request->gender;
            $user->privilege = $request->privilege;
            $user->company_id = $request->company;
            $user->bunitid = $request->bu;
            $user->modified_by = 173;
            $user->save();

            $bu = Businessunit::select('bname')
                ->where('unitid', $user->bunitid)
                ->pluck('bname');

            if (sizeof($bu) < 1) {
                $bu = ['None'];
            }
//
            $priv = Usertype::select('user_type_name')
                ->where('user_type_id', $user->privilege)
                ->pluck('user_type_name');
//
            $user2 = User::select('firstname', 'lastname')->where('user_id', $user->modified_by)->first();
//
            $response = [
                'firstname' => $user->firstname,
                'lastname'  => $user->lastname,
                'username'  => $user->username,
                'privilege' => $priv[0],
                'businessunit'   => $bu[0],
                'modifiedby'    => $user2->firstname,
            ];

            return response($response);

        }catch (Exception $e) {
            dd($e);
        }
        return -1;
    }

    public function trashSelectedUser($ids) {

        $data = json_decode($ids, true);

        switch ($data['action']) {
            case 'trashallselected':
                $code = User::destroy($data['data']);
                return response($code);
                break;

            case 'deleteallselected':
                foreach ($data['data'] as $ids) {
                    $code = User::withTrashed()
                        ->where('user_id', $ids)
                        ->forceDelete();
                }

                return \response($data['data']);
                break;

            case 'restoreallselected':
                foreach ($data['data'] as $ids) {
                    $code = User::withTrashed()
                        ->where('user_id', $ids)
                        ->restore();
                }

                return \response($data['data']);
                break;

            default:
                # code...
                break;
        }

    }

    public function userProfile() {

        $title = 'Bank Reconciliation System - Admin Profile';
        $pagetitle = "Admin Profile";

        return view('admin.user.profile', compact('pagetitle', 'title'));

    }

}
