<?php

namespace App;

use function foo\func;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DxSubsidiaryLedger extends Model
{
    //
    protected $table='dx_subsidiaryledgers';
    protected $guarded = [];
    protected $dates = ['doc_date','check_date'];

    public function scopeHigh($query) {
        return $query->where('balance', '>', 200);
    }

    public function scopeJoinT($query) {
        return $query->join('dx_transactions as tr', function ($join) {
            $join->on('dx_subsidiaryledgers.doc_date', 'tr.doc_date')
                ->on('dx_subsidiaryledgers.doc_type', 'tr.doc_type')
                ->on('dx_subsidiaryledgers.doc_no', 'tr.doc_no')
                ->on('dx_subsidiaryledgers.buid', 'tr.buid');
        })
            ->where('dx_subsidiaryledgers.doc_type', 'CV');
    }

    public function scopeGetDate($query, $args) {
        return $query->where('dx_subsidiaryledgers.ledger_code', $args[0])
            ->groupBy('docs_date', 'dx_subsidiaryledgers.ledger_code')
            ->orderBy('check_date', 'DESC')
            ->get([DB::raw('DATE_FORMAT(tr.doc_date, "%Y %M") as docs_date'), 'dx_subsidiaryledgers.ledger_code', 'tr.check_bank']);
    }

    public function scopeGetCodes($query, $args) {
        return $query->whereNotNull('dx_subsidiaryledgers.ledger_code')
            ->where('dx_subsidiaryledgers.buid', $args[0])
            ->groupBy('dx_subsidiaryledgers.ledger_code');
    }

}
