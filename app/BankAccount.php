<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use SoftDeletes;
    //
    protected $table="bankaccount";
    protected $fillable= ['bank,accountno,accountname, bankno, money, status, remarks, buid, company_code, added_by, modified_by, branchname, bank_addr, contact_person, added_by,modified_by'];
    protected  $dates=['deleted_at'];

    public function getBankAccountListAttribute()
    {
        return $this->attributes['bank'] .' - '. $this->attributes['accountno'].' - '. $this->attributes['accountname'];
    }

    public function company()
    {
        return $this->belongsTo('App\Company','company_code','company_code');
    }

    public function businessunit()
    {
        return $this->belongsTo('App\Businessunit','buid','unitid');
    }

    /**
     * My code
     */
    public function getDates() {
        return array('created_at', 'updated_at');
    }

    /**
     * My code
     */
    public function user1() {
        return $this->belongsTo('App\User', 'added_by', 'user_id');
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
    public function bankcode() {
        return $this->belongsTo('App\BankNo', 'bankno', 'id');
    }
}
