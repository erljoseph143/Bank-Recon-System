<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class LogbookCheck extends Model
{
    use Notifiable;
    use SoftDeletes;
    //
    protected $table = "logbook_checks";
    protected $dates = ['deleted_at'];
    protected $fillable = ['check_label_id, ds_number, amount, amount_edited, edit_status, sales_date, deposit_date, created_by, updated_by, deleted_by, bu, bankno, status_treasury'];

    /**
     * My code
     */
    public function getDates()
    {
        return ['sales_date', 'deposit_date', 'created_at', 'updated_at', 'deleted_at'];
    }

    /**
     * My code
     */
    public function company2() {
        return $this->belongsTo('App\Company', 'company', 'company_code');
    }

    /**
     * My code
     */
    public function bu2() {
        return $this->belongsTo('App\Businessunit', 'bu', 'unitid');
    }

    public function user_add() {
        return $this->belongsTo('App\User', 'created_by', 'user_id');
    }

    public function user_upd() {
        return $this->belongsTo('App\User', 'updated_by', 'user_id');
    }

    public function user_del() {
        return $this->belongsTo('App\User', 'deleted_by', 'user_id');
    }

    public function check_label() {
        return $this->belongsTo('App\LogbookCheckLabel', 'check_label_id', 'label_id');
    }

}
