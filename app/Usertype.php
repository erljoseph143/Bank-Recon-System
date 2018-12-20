<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usertype extends Model
{
    use SoftDeletes;
    //
    protected $primaryKey = "user_type_id";

    protected $table = "user_type";

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_type_name','created_by', 'updated_by','deleted_by'
    ];

    /**
     * My code
     */
    public function user1() {
        return $this->belongsTo('App\User', 'created_by', 'user_id');
    }

    /**
     * My code
     */
    public function user2() {
        return $this->belongsTo('App\User', 'updated_by', 'user_id');
    }

    public function getDates()
    {
        return array('created_at', 'updated_at');
    }
}
