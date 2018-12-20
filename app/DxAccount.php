<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DxAccount extends Model
{
    //
    protected $table='dx_accounts';

    public function test() {
        return $this->attributes;
    }
}
