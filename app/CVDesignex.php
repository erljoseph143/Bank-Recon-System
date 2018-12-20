<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CVDesignex extends Model
{
    //
    public $timestamps = false;
    protected $table = "cv_designex";
    protected $fillable = [
        'cv_no',
        'cv_date',
        'check_date',
        'check_no',
        'check_amount',
        'bcode',
        'bdesc',
        'des',
        'payee',
        'company',
        'bu_unit',
        'label_match',
        'status',
        'stale_check',
        'release_date',
        'status_matching'
    ];
}
