<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $primaryKey = "user_id";
    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email','username', 'firstname','password','lastname','gender','privilege','company_id','bunitid','added_by','module_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function usertype()
    {
        return $this->belongsTo('App\Usertype','privilege','user_type_id');
    }

    /**
     * My code
     */
    public function businessunit()
    {
        return $this->belongsTo('App\Businessunit','bunitid','unitid');
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

	
	   public function department()
    {
    	return $this->belongsTo('App\Department','dept_id','depid');
    }

    /**
     * @param string|array $roles
     */
    public function authorizeRoles($roles)
    {
        if (is_array($roles)) {
//            DB::enableQueryLog();
//            $this->roles()->whereIn('user_type_name', $roles)->get();
//            dd(DB::getQueryLog());

//            dd($this->roles()->whereIn('user_type_name', $roles)->first());
            return $this->hasAnyRole($roles) ||
                abort(401, 'This action is unauthorized.');
        }
        return $this->hasRole($roles) ||
            abort(401, 'This action is unauthorized.');
    }

    /**
     * Check multiple roles
     * @param array $roles
     */
    public function hasAnyRole($roles)
    {
        return null !== $this->roles()->whereIn('user_type_name', $roles)->first();
    }

    /**
     * Check one role
     * @param string $role
     */
    public function hasRole($role)
    {
    	$this->hasManyThrough('App\BankStatement','App\PDCLine','');
        return null !== $this->roles()->where('user_type_name', $role)->first();
    }
    
    public function isDataAdmin()
    {
    	if($this->usertype->user_type_name=='Data Admin')
	    {
	    	return true;
	    }
	    
	    return false;
    }
    
    
}
