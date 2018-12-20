<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CPOLedger extends Model
{
    //
	protected $table = "cpo_ledger";
	protected $guarded = [];
	
	public function tcpof()
	{
		return $this->belongsTo('App\TCPOF','cpo_id','id');
	}
	
}
