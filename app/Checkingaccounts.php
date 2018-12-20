<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checkingaccounts extends Model
{
    use SoftDeletes;
    //
    protected $table = 'checking_account';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'date_posted',
        'effective_date',
        'transaction_desc',
        'check_no',
        'withdrawals',
        'deposits',
        'trans_amount',
        'balance',
        'trans_type',
        'bu',
        'company',
        'nav_setup_no',
        'bankaccount_id',
        'uploaded_by',
        'created_by',
        'updated_by',
        'file_name'
    ];

    /**
     * My code
     */
    public function company()
    {
        return $this->belongsTo('App\Company','company_code','company_code');
    }
    /**
     * My code
     */
    public function businessunit()
    {
        return $this->belongsTo('App\Businessunit','bunitid','unitid');
    }

    /**
     * My code
     */
    public function user1() {
        return $this->belongsTo('App\User', 'uploaded_by', 'user_id');
    }

    /**
     * My code
     */
    public function user2() {
        return $this->belongsTo('App\User', 'modified_by', 'user_id');
    }
    /**
     * My code
     */
    public function getDates() {
        return array('date_posted', 'effective_date', 'date_uploaded', 'date_edited', 'created_at', 'updated_at');
    }

}
