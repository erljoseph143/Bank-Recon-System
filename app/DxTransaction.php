<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DxTransaction extends Model
{
//    use FullTextSearch;

    protected $searchable = ['hash'];
    //
    protected $table='dx_transactions';
    protected $dates = ['doc_date','check_date','created_at','updated_at','deleted_at'];

    protected $fillable= [
        'hash',
        'doc_date',
        'doc_type',
        'doc_no',
        'payee',
        'description',
        'amount',
        'check_bank',
        'check_date',
        'check_no',
        'status',
        'ledger_code',
        'buid',
        'created_by',
        'updated_by',
//        'is_oc',
//        'is_pdc'
    ];

    public function types()
    {
        return $this->hasMany('App\DxTransactionType', 'doc_type', 'type_id');
    }

    public function scopePage() {

    }

}
