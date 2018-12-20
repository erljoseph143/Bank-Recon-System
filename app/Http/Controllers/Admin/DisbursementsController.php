<?php

namespace App\Http\Controllers\Admin;

use App\PdcLine;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DisbursementsController extends Controller
{
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

            if ($request->p == 'delete') {
                $code = PdcLine::withTrashed()
                    ->where('id', $id)
                    ->forceDelete();
//
                return response()->json(['a'=>$id,'b'=>'delete']);
            }
            if ($request->p == 'restore') {
                $disburse = PdcLine::withTrashed()
                    ->where('id', $id)
                    ->restore();
                return response()->json(['a'=>$id, 'b'=>'restore']);
            }
//
            $disb = PdcLine::findOrFail($id);
            $disb->delete();
            return response()->json(['a'=>$id,'b'=>'trash']);

        }catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function viewAjax(Request $request)
    {
        $columns = ['cv_date', 'check_no', 'check_amount', 'label_match', 'action'];
        $where = [['baccount_no', $request->code], ['uploaded_by', $request->userid], ['bu_unit', $request->bu]];

        if ($request->page == 'all') {
            $totalData = PdcLine::select('id')->where($where)->whereYear('cv_date', $request->year)
                ->whereMonth('cv_date', $request->month)
                ->count();
            $title="trash";
        } else {
            $totalData = PdcLine::select('id')->where($where)->whereYear('cv_date', $request->year)
                ->whereMonth('cv_date', $request->month)
                ->onlyTrashed()
                ->count();
            $title="delete";
        }

        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value'))) {
            if ($request->page == 'all') {
                $transactions = PdcLine::where($where)->whereYear('cv_date', $request->year)
                    ->whereMonth('cv_date', $request->month)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
            } else {
                $transactions = PdcLine::onlyTrashed()->where($where)->whereYear('cv_date', $request->year)
                    ->whereMonth('cv_date', $request->month)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
            }
        } else {
            $search = $request->input('search.value');
            if ($request->page == 'all') {
                $transactions = PdcLine::where(function ($query) use($search) {
                        $query->where('check_no', 'LIKE', '%'.$search.'%')
                            ->orWhere('check_amount', 'LIKE', '%'.$search.'%');
                        })
                    ->where($where)->whereYear('cv_date', $request->year)
                    ->whereMonth('cv_date', $request->month)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                $totalFiltered = PdcLine::where(function ($query) use($search) {
                        $query->where('check_no', 'LIKE', '%'.$search.'%')
                            ->orWhere('check_amount', 'LIKE', '%'.$search.'%');
                        })
                    ->where($where)->whereYear('cv_date', $request->year)
                    ->whereMonth('cv_date', $request->month)
                    ->count();
            } else {
                $transactions = PdcLine::onlyTrashed()->where($where)->whereYear('cv_date', $request->year)
                    ->whereMonth('cv_date', $request->month)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
            }
        }
        $data = [];
        if (!empty($transactions)) {
            foreach ($transactions as $transaction) {
                $restore = ($title == "delete")?'<a href="'.route('disbursementlists.destroy', $transaction->id).'" class="on-default remove-row" title="restore" onclick="$.Notification.notify(\'white\',\'top left\', \'\', \'Successfully restored!\')"><i class="fa fa-mail-reply"></i></a>':'';
                $nestedData['cv_date'] = $transaction->cv_date->format('M d Y');
                $nestedData['check_no'] = $transaction->check_no;
                $nestedData['check_amount'] = $transaction->check_amount;
                $nestedData['label_match'] = $transaction->label_match;
                $nestedData['action'] = '<div class="actions">'.
                    $restore.'<a href="'.route('disbursementlists.destroy',$transaction->id).'" class="on-default remove-row" title="'.$title.'"><i class="fa fa-trash" onclick="$.Notification.notify(\'white\',\'top left\', \'\', \'Successfully move to trash!\')"></i></a>'.'</div>';
                $data[] = $nestedData;
            }
        }
        return response()->json([
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => $totalData,
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data,
        ]);
    }
}
