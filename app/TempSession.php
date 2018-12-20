<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempSession extends Model
{
    //
    //        $_SESSION['SESS_PRIVILEGE'] = $type;
//        $_SESSION['SESS_ADMIN_ID'] = $user_id;
//        $_SESSION['SESS_ADMIN_USERNAME'] = $member['username'];
//        $_SESSION['SESS_ADMIN_PASSWORD'] = $member['password'];
//        $_SESSION['SESS_FULLNAME'] = $member['firstname'] ." ". $member['lastname'];
//        $_SESSION['SESS_BUNITID'] = $member['bunitid'];
//        $_SESSION['SESS_GENDER']= $member['gender'];
//        $_SESSION['SESS_LOCK'] = "false";
    public $timestamps = false;
    protected $table = "temp_session";
    protected $fillable = [
        'SESS_PRIVILEGE',
        'SESS_ADMIN_ID',
        'SESS_ADMIN_USERNAME',
        'SESS_ADMIN_PASSWORD',
        'SESS_FULLNAME',
        'SESS_BUNITID',
        'SESS_GENDER'
    ];
}
