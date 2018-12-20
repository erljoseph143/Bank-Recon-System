<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManualBs extends Model
{
    //
    protected $primaryKey = "bank_id";
    protected $fillable =[
        'bank_date',
        'bank_account_no',
        'description',
        'bank_check_no',
        'bank_amount',
        'bank_balance',
        'type',
        'company',
        'bu_unit',
        'label_match',
        'status_matching'
    ];
}
