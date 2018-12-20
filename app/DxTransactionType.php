<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DxTransactionType extends Model
{
    protected $table='dx_transaction_types';
    protected $fillable = [
        'code',
        'name',
        'created_by',
        'updated_by'
    ];

    /**
     * @param $query
     * @param $args
     */
    public function scopeFilter($query, $args) {
        if (is_array($args)) {
            $query->where($args[0], 'LIKE', "%{$args[1]}%")->orderBy($args[2],'DESC');
        }
    }
}
