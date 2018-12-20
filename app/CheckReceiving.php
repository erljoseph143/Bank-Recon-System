<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CheckReceiving extends Model
{
    //
	protected $connection = "mysql2";
	protected $table      = "checksreceivingtransaction";
	
	public function checks()
	{
		return $this->hasMany('App\Checks','checksreceivingtransaction_id','checksreceivingtransaction_id');
	}
	
}
