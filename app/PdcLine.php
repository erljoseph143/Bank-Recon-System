<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PdcLine extends Model
{
    use SoftDeletes;
    //
    //public $timestamps = false;
    protected $primaryKey ="id";
    protected $table ="pdc_line";

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'cv_no',
        'cv_status',
        'check_no',
        'baccount_no',
        'check_amount',
        'check_date',
        'cd_pd_date',
        'bank_date',
        'cancelled_date',
        'company_code',
        'department_code',
        'date_upload',
        'cv_date',
        'bu_unit',
        'uploaded_by',
        'pdc_status',
        'company',
        'payee',
        'status_matching',
        'status'

    ];

    public function bankcheck()
    {
        return $this->hasMany('App\BankStatement','bank_check_no','check_no');
    }
    /**
     * My code
     */
    public function user1() {
        return $this->belongsTo('App\User', 'uploaded_by', 'user_id');
    }
    /**
     * My code
     */
    public function getDates() {
        return array('cv_date', 'date_upload');
    }
}
