<?php

namespace App\Http\Controllers\Admin;

use App\Businessunit;
use App\Company;
use App\Department;
use App\User;
use App\Usertype;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {

        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;

        $title = "Bank Reconciliation System - Users";
        $pagetitle = "Users";
        $doctitle = "users";

        $countall = User::count();
        $counttrash = User::onlyTrashed()->count();

        $col = [
            'user_id', 'firstname',
            'lastname', 'username',
            'privilege', 'bunitid',
            'created_at', 'updated_at',
            'added_by', 'modified_by'
        ];

        if ($request->p == 'trash') {
            $users = User::onlyTrashed()->orderBy('user_id', 'DESC')->get($col);
            $template = 'trash';
        } else {
            $users = User::orderBy('user_id', 'DESC')->get($col);
            $template = 'all';
        }

        return view('admin.user.index', compact('users','title','pagetitle', 'doctitle', 'template', 'counttrash', 'countall', 'login_user_firstname', 'name'));
    }

    public function create() {
        $companies = Company::get(['company', 'company_code'])
            ->pluck('company', 'company_code');
        $bus = Businessunit::get(['unitid', 'bname', 'company_code']);
        $usertypes = Usertype::get(['user_type_id', 'user_type_name']);
        $departments = Department::get(['depid','dep_name','buid']);

        $htmlcompanies = '<option value="-1">None</option>';
        $htmlbus = '<option value="-1" data-ccode="-1">( Select Bussiness Unit )</option>';
        $htmlusertype = "";
        $htmldept = '<option value="-1">( Select Department )</option>';

        foreach ($companies as $key => $company) {
            $htmlcompanies .= '<option value="'.$key.'">'.$company.'</option>';
        }

        foreach ($usertypes as $usertype) {
            $htmlusertype .= '<option value="'.$usertype->user_type_id.'">'.strtoupper($usertype->user_type_name).'</option>';
        }

        return response([
            'companies' => $htmlcompanies,
            'bus'       => $htmlbus,
            'usertypes'  => $htmlusertype,
            'dep'   => $htmldept,
        ]);
    }

    public function select(Request $request) {

        if ($request->action === 'selectcom') {

            $ins = Businessunit::where('company_code', $request->data)->get();

            $view = view('admin.user.select', compact('ins'))->render();
            return response()->json(['data'=>$view]);
        } else if ($request->action === 'selectbu') {

            $ins = Department::where('buid', $request->data)->get();

            $view = view('admin.user.selectbu', compact('ins'))->render();
            return response()->json(['data'=>$view]);

        }
    }

    public function store(Request $request) {

        $login_userid = Auth::id();
        $name = $request->user()->firstname . ' ' . $request->user()->lastname;

        $validator = Validator::make($request->all(), [
            'row.data.firstname' => 'required',
            'row.data.lastname' => 'required',
            'row.data.username' => 'required|unique:users,username',
            'row.data.password' => 'required',
            'row.data.gender' => 'required',
            'row.data.privilege' => 'required',
            'row.data.company_id' => 'required',
            'row.data.bunitid' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'error','mes'=>$validator->errors()->first(),'action'=>'save']);
        }

        try {

            $data = $request->row['data'];

            $data['status'] = 'a';
            $data['added_by'] = $login_userid;
            $data['modified_by'] = $login_userid;
            $data['usercaptcha'] = $request->row['data']['password'];
            $data['module_type'] = 'Bank Recon';
            $data['password'] = Hash::make($request->row['data']['password']);

            $user = User::create($data);
            $view = view('admin.user.user', compact('user', 'name'))->render();
            return response()->json(['data'=>$view]);

        } catch (Exception $e) {
            dd($e);
        }

    }

    public function update(Request $request, $id) {

        try {

            $data = $request->row['data'];
//
            User::where('user_id', $id)
                ->update($data);

            return response($data);

        } catch (Exception $e) {
            dd($e);
        }
    }

    public function destroy(Request $request, $id) {

        try {
            if ($request->action == 'trash') {
                $code = User::findOrFail($id);
                $code->delete();
                return response()->json(['id'=>$id]);
            }
            $code = User::withTrashed()
                ->where('user_id', $id)
                ->forceDelete();
            return response()->json(['id'=>$id]);
        }catch (Exception $e) {
            dd($e);
        }

    }

    public function show(Request $request, $id) {

        $action = $request->action;

        if ($action == 'restore') {
            $user = User::withTrashed()
                ->where('user_id', $id)
                ->restore();

            return $id;
        }

        $user = User::find($id);

        $companies = Company::all()->pluck('company', 'company_code');

        $bus = Businessunit::select('unitid', 'bname', 'company_code')
            ->where('company_code', $user->company_id)
            ->get();

        $departments = Department::where('buid', $user->bunitid)
            ->get(['depid', 'dep_name', 'buid']);

//        dd($departments);

        $usertypes = Usertype::all();

        $htmlcompanies = '<option value="-1">None</option>';
        $htmlbus = '<option value="-1" data-ccode="-1">( Select Bussiness Unit )</option>';
        $htmlusertype = '';
        $htmldept = '<option value="-1" data-bu="-1">( Select Department )</option>';

        $type = '';

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
                $htmlusertype .= '<option value="'.$usertype->user_type_id.'" selected>'.strtoupper($usertype->user_type_name).'</option>';
                $type .= strtoupper($usertype->user_type_name);
            } else {
                $htmlusertype .= '<option value="'.$usertype->user_type_id.'">'.strtoupper($usertype->user_type_name).'</option>';
            }
        }

        foreach ($departments as $department) {

            if (strtolower($department->depid) == strtolower($user->dept_id)) {
                $htmldept .= '<option value="'.$department->depid.'" data-bu="'.$department->buid.'" selected>'.strtoupper($department->dep_name).'</option>';
            } else {
                $htmldept .= '<option value="'.$department->depid.'" data-bu="'.$department->buid.'">'.strtoupper($department->dep_name).'</option>';
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
            'department'    => $htmldept,
            'user_id'       => $user->user_id,
            'type'          => $type,
        ];

        return response($response);
    }

    public function selected(Request $request) {
        try {
            if ($request->data) {
                if ($request->action === 'restoreselected') {
                    foreach ($request->data as $ids) {
                        $code = User::withTrashed()
                            ->where('user_id', $ids)
                            ->restore();
                    }
    //
                    return \response($request->data);
                } else if ($request->action === 'trashselected') {
                    $code = User::destroy($request->data);
                    return response($code);
                }
            }
        } catch (\Exception $e) {
            return json_encode(['a' => $e->errorInfo[2], 'b' => 'error']);
        }
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

        $login_user = Auth::user();

        $login_user_firstname = $login_user->firstname;
        $login_user_lastname = $login_user->lastname;
        $login_user_type = $login_user->privilege;
        $login_user_username = ucfirst($login_user->username);
        $login_user_gender = ucfirst($login_user->gender);

        $login_user_company = Company::select('company')
            ->where('company_code', $login_user->company_id)
            ->first();

        $login_user_bu = Businessunit::select('bname')
            ->where('unitid', $login_user->bunitid)
            ->first();

        $user_type = Usertype::select('user_type_name')
            ->where('user_type_id', $login_user_type)
            ->first();

        $title = 'Bank Reconciliation System - Admin Profile';
        $pagetitle = "Admin Profile";

        return view('admin.user.profile', compact('pagetitle', 'title', 'login_user_firstname', 'login_user_lastname', 'user_type', 'login_user_username', 'login_user_gender', 'login_user_company', 'login_user_bu'));

    }

    public function updatePassword(Request $request) {

        $login_userid = Auth::id();

        if ($request->password == '' && $request->password == null) {
            return -1;
        }

        if (strlen($request->password) < 5) {
            return -2;
        }

        $user = User::findOrFail($login_userid);

        $user->fill([
            'password'  => Hash::make($request->password),
        ])->save();

        //echo 'sdfsd';

        return 1;

        //return $request;

    }

    public function resetPassword(Request $request) {

        $user = User::findOrFail($request->id);
//
        $user->fill([
            'password'  => Hash::make('123456'),
        ])->save();
//
        return $request->id;

    }

}
