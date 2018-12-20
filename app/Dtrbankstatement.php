<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dtrbankstatement extends Model
{
    use SoftDeletes;
    //public $timestamps = false;
    protected $table = 'dtrbankstatement';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'check_date',
        'description',
        'ref',
        'details',
        'debit_amount',
        'credit_amount',
        'cd_amount',
        'stats',
        'balance',
        'accountid',
        'company',
        'bu',
        'created_by',
    ];
    //

    /**
     * My code
     */
    public function bunit() {

        return $this->belongsTo('App\Businessunit', 'bu', 'unitid');

    }

    public function bankaccounts() {

        return $this->belongsTo('App\BankAccount','accountid','id');

    }

    public function getDates()
    {
        return ['check_date', 'created_at', 'updated_at', 'deleted_at'];
    }
}
