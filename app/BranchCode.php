<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchCode extends Model
{
    //
    use SoftDeletes;

    protected $table = 'branch_code';

    protected $dates = ['created_at','updated_at','deleted_at'];

    protected $fillable = [
        'bankname',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * My code
     */
    public function bank() {
        return $this->belongsTo('App\Bank', 'bank_name', 'id');
    }

    /**
     * My code
     */
    public function creator() {
        return $this->belongsTo('App\User', 'created_by', 'user_id');
    }
}
