<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Granted as Granted;
use Illuminate\Support\Facades\Auth;

class GrantedController extends Controller
{
    public function insert(Request $request){
    	// if (Auth::check()) {
		    $date = $request->input('date');
	    	$granted = new Granted;
	    	$granted->date = $date;
	    	$granted->assist_id = $request->input('assist_id');
	    	$granted->personnel = Auth::user()->id;
	    	$granted->save();
	    	return $granted;
		// }
    	
    }
}
