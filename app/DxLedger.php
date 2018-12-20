<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DxLedger extends Model
{
    //
    protected $table='dx_ledgers';
    protected $guarded=[];

    public function scopeFilter($query, $args) {
        if (is_array($args)) {
            $query->where($args[0], 'LIKE', "%{$args[1]}%")->orderBy($args[2],'DESC');
        }
    }
}
