<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankStatement extends Model
{
    use SoftDeletes;
    //
    public $timestamps = false;
    protected $primaryKey ="bank_id";
    protected $table = "bank_statement";
    protected $dates = ['deleted_at'];
//bank_date,
//description,
//bank_account_no,
//bank_check_no,
//bank_amount,
//bank_balance,
//status,
//type,
//transaction_type,
//uploaded_by,
//year_in,
//deposit_status,
//bu_unit,company,debit_memos
    protected $fillable =[
        'bank_check_no',
        'bank_account_no',
        'bank_amount',
        'bank_date',
        'bank_balance',
        'status',
        'description',
        'type',
        'transaction_type',
        'uploaded_by',
        'year_in',
        'deposit_status',
        'bu_unit',
        'company',
        'debit_memos',
		'error_label',
	    'label_match',
	    'bank_ref_no',
	    'actual_balance'

    ];

//    public function setBankdateofAttribute()
//    {
//        return $this->attributes['MONTH(bank_date)'];
//    }
//
//    public function setBankdateAttribute()
//    {
//        return $this->attributes["DATE_FORMAT(bank_date,'%Y-%m')"];
//    }

    public function PdcLine()
    {
        return $this->hasMany('App\PdcLine');
    }

    public function bankcodes() {
        return $this->belongsTo('App\BankNo', 'bankno', 'bank_account_no');
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
    public function businessunit() {
        return $this->belongsTo('App\Businessunit', 'bu_unit', 'unitid');
    }
    /**
     * My code
     */
    public function getDates() {
        return array('bank_date', 'date_modified', 'date_added');
    }

    public function scopeNotMatch($query, $args) {
        return $query->where('label_match', '!=', 'match check')
            ->where('type', 'AP')
            ->where('bu_unit', $args[0]);
    }

    public function scopeNoBU($query) {
        return $query->where('bu_unit', '=', '')
            ->orWhere('bu_unit', '0')
            ->orWhere('company', '<=', 0)
            ->orWhereNull('bu_unit')
            ->orWhereNull('company');
    }

}
