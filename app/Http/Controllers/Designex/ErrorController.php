<?php

namespace App\Http\Controllers\Designex;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ErrorController extends Controller
{
    //
    public function index() {

        $title = 'Designex error handling';
        $tables = [
            'ledgers',
            'subsidiary ledgers',
            'transaction types',
            'transactions',
            'accounts',
        ];

        $errors = [
            'date'
        ];

        $date_options = [
            'year',
            'month',
            'day'
        ];

        $format = [
            'dd'
        ];

        return view('designex.error.index', compact('title','tables', 'errors', 'date_options'));
    }
}
