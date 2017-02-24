<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Province as Province;

class ProvinceController extends Controller
{
    public function fetchProvinces(){
    	return response()->json([
    		'provinces' => Province::orderBy('id')->get()
    	]);
    }
}
