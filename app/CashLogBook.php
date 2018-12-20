<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CashLogBook extends Model
{
    //
    protected $table = "cash_log_book";
    protected $guarded = [];
	
	/**
	 * My code
	 */
	public function company2() {
		return $this->belongsTo('App\Company', 'company', 'company_code');
	}
	
	/**
	 * My code
	 */
	public function bu2() {
		return $this->belongsTo('App\Businessunit', 'bu_unit', 'unitid');
	}
	
	public function cashLog()
	{
		return $this->belongsTo('App\Cashlog','cash_id','id');
	}

    /**
     * My code
     */
    public function getDates() {
        return array('sales_date', 'deposit_date');
    }

}
