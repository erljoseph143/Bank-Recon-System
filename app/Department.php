<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $primaryKey='depid';
	public $table  = "departments";
    //

    public function businessunit()
    {
        return $this->belongsTo('App\Businessunit','buid','unitid');
    }

}
