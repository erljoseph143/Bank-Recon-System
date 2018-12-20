<?php

namespace App\Http\Controllers\Data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataController extends Controller
{
    //
	public function __construct()
	{
		$this->middleware('dataAdmin');
	}
	
	public function index()
	{
		$com = \App\Company::pluck('company','company_code')->all();
		return view('data.home',compact('com'));
	}
}
