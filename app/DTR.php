<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DTR extends Model
{
    use SoftDeletes;
    //
	protected $table = 'dtr';

    protected $dates = ['created_at', 'deleted_at'];
	
	protected $guarded = [];
	
}
