<?php

namespace App\Http\Controllers\Admin;

use App\BankStatement;
use App\Deposit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Expr\Array_;

class TestController extends Controller
{
    //
    public function matchSameDateAmount() {

        $title = "Match same date and amount";

        $bank_statements = BankStatement::select('bank_id', 'bank_date', 'description', 'bank_amount')
            ->where('bank_account_no', 'B-001')
            ->where('type', 'AR')
            ->where(DB::raw('MONTH(bank_date)'), '01')
            ->where(DB::raw('YEAR(bank_date)'), '2009')
            ->where('company', '2')
            ->where('bu_unit', '2')
            ->orderBy('bank_amount')
            ->get();
//            ->pluck('bank_amount')
//            ->toArray();

        $deposits = Deposit::select('id', 'posting_date', 'doc_no', 'ext_doc_no', 'amount')
            ->where('bank_account_no', 'B-001')
            ->where(DB::raw('MONTH(posting_date)'), '01')
            ->where(DB::raw('YEAR(posting_date)'), '2009')
            ->where('company', '2')
            ->where('bu_unit', '2')
            ->orderBy('amount')
            ->get();

        //dd($bs);

        //$bs = [500,500,100,200,300,300,300,400,600,700];

        $countbs = count($bank_statements)-1;
        $countdp = count($deposits)-1;

        //dd($countdp);

        $unique = [];

        //get unique
        if (!empty($bank_statements)) {

            $matches_deposits = [];
            $matches_bankstatements = [];
            $amount_bs_matches = [];
            $amount_dp_matches = [];

            foreach ($bank_statements as $bank_statement) {
                //results base on bank statements match
                $deposit_search_result = exponentialSearch($deposits,$countdp, $bank_statement->bank_amount);

                if ($deposit_search_result != -1) {
                    $matches_bankstatements[] = $deposit_search_result;
                    $amount_bs_matches[] = $deposit_search_result[1];
                }
            }

            foreach ($deposits as $deposit) {
                $bank_statement_search_result = exponentialSearch2($bank_statements, $countbs, $deposit->amount);
                if ($bank_statement_search_result != -1) {
                    $matches_deposits[] = $bank_statement_search_result;
                    $amount_dp_matches[] = $bank_statement_search_result[1];
                }
            }

            //kuhaon sa ang mga duplicate
            $bs_unique = array_unique($amount_bs_matches);
            $dp_unique = array_unique($amount_dp_matches);

            $dup = [];

            $count = 0;

            $bank = [];

//            echo '<pre>';
//            print_r($duplicates);
//            echo '</pre>';

        return view('test.index', compact('title', 'matches_deposits', 'matches_bankstatements', 'final_datas', 'dup'));

        }

    }
}
