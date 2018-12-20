<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CashPullOut extends Model
{
    //
	protected $table = 'cash_pull_out';
	protected $guarded = [];
	
	public function user()
	{
		return $this->belongsTo('App\User','requested_by','user_id');
	}
	
	public function purposes()
	{
		return $this->belongsTo('App\Purpose','purpose','id');
	}
	
	public function approveby()
	{
		return $this->belongsTo('App\User','approve_by','user_id');
	}
	
	public function releaseby()
	{
		return $this->belongsTo('App\User','release_by','user_id');
	}
	
	public function department()
	{
		return $this->belongsTo('App\Department','dep_sec','depid');
	}
	
	public function ledger()
	{
		return $this->hasMany('App\CPOLedger','cpo_id','id');
	}
}
