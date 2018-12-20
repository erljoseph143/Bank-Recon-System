<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankNo extends Model
{
    use SoftDeletes;
    //
    protected $table ="bankno";
    protected $fillable = ['bankcode,added_by,modified_by'];
    protected $dates = ['deleted_at'];
    //protected $softDelete = true;
    //public $timestamps = false;

    //My code
//    protected $dates = [
//        'date_added',
//        'date_modified'
//    ];

//    public function usertype()
//    {
//        return $this->belongsTo('App\Usertype','privilege','user_type_id');
//    }

    public function bankName()
    {
        return $this->belongsTo('App\BankAccount','id','bankno');
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
    public function getDates() {
        return array('created_at', 'updated_at');
    }
}
