<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssistController extends Controller
{
    public function insert(){
    	$this->validate($request, [
	        'fr_name' => 'required',
	        'province' => 'required',
	        'province' => 'required'
	    ]);

    }
}
