<?php

namespace App\Http\Controllers\Admin;

use App\BankAccount;
use App\BankNo;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Mockery\Exception;


class BankcodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function allBankCodes() {

        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;
        $login_user_lastname = $login_user->lastname;

        $codes = BankNo::all();

        $countall = BankNo::all()->count();

        $counttrash = BankNo::onlyTrashed()->count();

        $query = "
            SELECT CONCAT(MONTHNAME(updated_at), ' ', YEAR(updated_at)) as created_at
            FROM bankno
            GROUP BY YEAR(updated_at) DESC, MONTH(updated_at) DESC 
        ";

        $date = DB::select($query);

        $title = "Bank Reconciliation System - Bank Codes";
        $pagetitle = "Bank Codes";
        $doctitle = "Bank Codes";

        $template = 'all';

        return view('admin.bankcode.index', compact('codes', 'title', 'pagetitle', 'countall', 'date', 'template', 'counttrash', 'doctitle', 'login_user_firstname'));
    }

    public function allTrashCodes() {

        $login_user = Auth::user();
        $login_user_firstname = $login_user->firstname;
        $login_user_lastname = $login_user->lastname;

        $codes = BankNo::onlyTrashed()
            ->get();

        $countall = BankNo::all()->count();
        $counttrash = BankNo::onlyTrashed()->count();

        $query = "
            SELECT CONCAT(MONTHNAME(updated_at), ' ', YEAR(updated_at)) as created_at
            FROM bankno
            GROUP BY YEAR(updated_at) DESC, MONTH(updated_at) DESC 
        ";

        $date = DB::select($query);

        $title = "Bank Reconciliation System - Bank Codes";
        $pagetitle = "Bank Codes";
        $doctitle = "Bank Codes";

        $template = 'trash';

        return view('admin.bankcode.index', compact('codes', 'title', 'pagetitle', 'countall', 'date', 'template', 'counttrash', 'doctitle', 'login_user_firstname'));
    }

    public function save( ) {
//        if ($id->isMethod('post')) {
//            return response()->json(['response' => 'This is post method']);
//        }

        return response()->json(['response' => 'This is get method']);
    }

    public function getBankcode($id) {
        $data = json_decode($id);

        if ($data[1] == 'restore') {

            $code = BankNo::withTrashed()
                ->where('id', $data[0])
                ->restore();

            return \response($code);
        }

        $bankcode = BankNo::find($data[0]);

        return response($bankcode);
    }

    public function createCode(Request $request) {
        //$bankcode = BankNo::create(['bankno' => 'fsdfsdfdsf']);
        //echo $request->bankcode;
        $login_userid = Auth::id();


        if (empty($request->bankcode)) {
            return 0;
        }

        $findBankno = BankNo::select(DB::raw('COUNT(bankno) as count'))
            ->where('bankno', $request->bankcode)
            ->first();

        if ($findBankno->count > 0) {
            return -1;
        }

        $bankcode = new BankNo;

        $bankcode->bankno = $request->bankcode;
        $bankcode->added_by = $login_userid;
        $bankcode->modified_by = $login_userid;
        $bankcode->save();

        $user = User::select('firstname', 'lastname')->where('user_id', $bankcode->added_by)->first();

        $created = $bankcode->created_at->format('F d, Y');
        $updated = $bankcode->updated_at->format('F d, Y');

        $response = [
            'id'        => $bankcode->id,
            'bankno'    => $bankcode->bankno,
            'added_by'  => $user->firstname . ' ' . $user->lastname,
            'created_at'=> $created,
            'updated_at'=> $updated,
        ];

        return response($response);
    }

    public function updateCode(Request $request, $id) {

        $login_userid = Auth::id();

        if (empty($request->bankcode)) {
            return 0;
        }

        $findBankno = BankNo::select(DB::raw('COUNT(bankno) as count'))
            ->where('bankno', $request->bankcode)
            ->where('id', '!=', $id)
            ->first();

        if ($findBankno->count > 0) {
            return -1;
        }

        try {
            $bankcode = BankNo::find($id);
            $bankcode->bankno = $request->bankcode;
            $bankcode->modified_by = $login_userid;
            $bankcode->save();

            $user = User::select('firstname', 'lastname')->where('user_id', $bankcode->added_by)->first();

            $user2 = User::select('firstname', 'lastname')->where('user_id', $bankcode->modified_by)->first();

            $created = $bankcode->created_at->format('F d, Y');
            $updated = $bankcode->updated_at->format('F d, Y');

            if ( $bankcode->added_by == 0 || is_null($bankcode->added_by) || empty($bankcode->added_by) ) {
                $added_by = '';
            } else {
                $added_by = $user->firstname . ' ' . $user->lastname;
            }

            $response = [
                'id'        => $bankcode->id,
                'bankno'    => $bankcode->bankno,
                'added_by'  => $added_by,
                'created_at'=> $created,
                'modified_by'=> $user2->firstname . ' ' . $user2->lastname,
                'updated_at'=> $updated,
            ];

            return response($response);
        }catch (Exception $e) {
            dd($e);
        }
    }

    public function deleteCode($id) {

        try {
            $data = json_decode($id);

            if ($data[1] == 'delete') {
                $code = BankNo::withTrashed()
                    ->where('id', $data[0])
                    ->forceDelete();

                return $code;
            }

            $code = BankNo::findOrFail($data[0]);
            $code->delete();

            return response($code);

        }catch (Exception $e) {
            dd($e);
        }

    }

    public function editSelectedCode($ids) {
        echo 'sdfdsf';
        //return response('sdfsdf');
    }

    public function deleteSelectedCode($ids) {

//        $data = Input::all();
//        return $data;

        $data = json_decode($ids, true);

        switch ($data['action']) {
            case 'trashallselected':
                $code = BankNo::destroy($data['data']);
                return response($code);
                break;

            case 'deleteallselected':
                foreach ($data['data'] as $ids) {
                    $code = BankNo::withTrashed()
                        ->where('id', $ids)
                        ->forceDelete();
                }

                return \response($data['data']);
                break;

            case 'restoreallselected':
                foreach ($data['data'] as $ids) {
                    $code = BankNo::withTrashed()
                        ->where('id', $ids)
                        ->restore();
                }

                return \response($data['data']);
                break;

            default:
                # code...
                break;
        }

//        if ($data['action'] == "trashallselected") {
//
//
//        } elseif ($data['action'] == "deleteallselected") {
//
//            foreach ($data['data'] as $ids) {
//                $code = BankNo::withTrashed()
//                    ->where('id', $ids)
//                    ->forceDelete();
//            }
//
//            return \response($data['data']);
//
//        } elseif ($) {
//
//        }


//        $code = BankNo::destroy($ids);
//
//        return response($code);

//        foreach ($ids as $id) {
//            echo $id;
//        }
        //return $ids;
    }
}