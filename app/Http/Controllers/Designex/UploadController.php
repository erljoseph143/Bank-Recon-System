<?php

namespace App\Http\Controllers\Designex;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index(Request $request) {
        $login_user = Auth::user();

        $request->user()->authorizeRoles(['admin','finance']);

        $title = "BRS - Designex Accounting Dashboard";
        $ptitle = "upload";
        $max_file_size = ini_get("upload_max_filesize");
        return view('designex.index', compact('title', 'login_user', 'ptitle', 'max_file_size'));
    }

    public function uploadui() {
        $title = "Designex Upload files";
        $ptitle = 'upload';
        $max_file_size = ini_get("upload_max_filesize");
        return view('designex.upload.index', compact('title', 'ptitle', 'max_file_size'));
    }

    public function postReq(Request $request) {
        set_time_limit(0);
        $validator = Validator::make($request->all(), [
            'files' => 'required',
            'files.*' => 'mimes:bin'
        ]);
        if ($validator->fails()) {
            return json_encode(['a' => $validator->errors()->first(), 'b' => 'error']);
        }

        if ($request->hasFile('files')) {

            $files = $request->file('files');
            $uploadcv = new UploadProofListClass();
            $uploadsl = new UploadSLClass();
            $uploadacc = new UploadAccountClass();
            $uploadledger = new UploadLedgerClass();
            $types = DesignexTransactionType::get(['code', 'name']);
            try {
                DB::beginTransaction();
                foreach ($files as $file) {
                    $file_name = $file->getClientOriginalName();
                    $content = File::get($file);
                    switch (FiletypeClass::identify($content)) {
                        case "proof list":
                            if (TRUE !== $result = $uploadcv->upload($content, $file_name, $types)) {
                                return $result;
                            }
                            break;
                        case "subsidiary ledger":

                            if (TRUE !== $result = $uploadsl->upload($content, $file_name, $types)) {
                                return $result;
                            }
                            break;
                        case "accounts":
                            if (TRUE !== $result = $uploadacc->upload($content, $file_name)) {
                                return $result;
                            }
                            break;
                        case "ledgers":
                            if (TRUE !== $result = $uploadledger->upload($content, $file_name, $types)) {
                                return $result;
                            }
                            break;
                        default:
                            return json_encode(['a' => 'Unable to detect what file type is \'' . $file_name . '\'!', 'b' => 'error']);
                            break;
                    }
                }
                DB::commit();
            } catch (QueryException $exception) {
                DB::rollBack();
                return json_encode(['a' => $exception->errorInfo[2], 'b', 'error']);

            }
        }
    }
}
