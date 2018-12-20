<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    //
    use SoftDeletes;

    protected $primaryKey = "company_code";
    protected $table = "company";
    protected $dates = ['deleted_at'];
    protected $fillable = ['company, acroname, added_by, modified_by'];

    public function bu_unit()
    {
        return $this->belongsTo('App\Businessunit','company_code','company_code');
    }

    public function getFullNameAttribute()
    {
        return $this->company . " " . $this->acroname;
    }

    public function bankAccounts()
    {
        return $this->hasMany('App\BankAccount','company_code','company_code');
    }

    public function BU()
    {
        return $this->hasMany('App\Businessunit','company_code','company_code');
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
    public function getDates()
    {
        return ['created_at', 'updated_at'];
    }
}
