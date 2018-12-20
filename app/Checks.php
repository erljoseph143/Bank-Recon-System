<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checks extends Model
{
    //
	protected $connection = 'mysql2';
	protected $table      = 'checks';
	public $timestamps = false;
	
	public function  checkreceive()
	{
		return $this->belongsTo('App\CheckReceiving','checksreceivingtransaction_id','checksreceivingtransaction_id');
	}
}
