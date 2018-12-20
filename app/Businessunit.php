<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Businessunit extends Model
{
    use SoftDeletes;
    //
    protected $primaryKey = "unitid";
    protected $table = "businessunit";
    protected $dates = ['deleted_at'];

    public function company()
    {
        return $this->belongsTo('App\Company','company_code','company_code');
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
    
    public function banks()
    {
    	return $this->hasMany('App\BankAccount','buid','unitid');
    }

    /**
     * My code
     */
    public function getDates()
    {
        return ['created_at', 'updated_at'];
    }
}
