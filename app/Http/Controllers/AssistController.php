<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Assist as Assist;

class AssistController extends Controller
{
    public function fetchByType($type){
        return response()->json([
            'type' => $type,
            'assists' => ($type != 0) ? Assist::where('type_of_assistance', $type)->orderBy('id','desc')->get() : Assist::orderBy('id','desc')->get()
        ]);
    }
    public function fetch(){
        return response()->json([
            'assists' => Assist::orderBy('id','desc')->get(),
            'provinces' => \App\Province::all(),
            'types' => \App\Type::all()
        ]);
    }
    public function insert(Request $request){
    	$this->validate($request, [
	        'fr_name' => 'required',
	        'province' => 'required',
	        'address' => 'required',
	        'type_of_assistance' => 'required',
	        'date_submitted' => 'required',
	        'amount' => 'required|numeric'
	    ]);
	    return $this->validateDuplicates($request);
    	
    }
    public function validateDuplicates($request){
    	$count = Assist::where('type_of_assistance', $request->input('type_of_assistance'))
    					->where('fr_name', $request->input('fr_name'))->count();
    	if ($count) {
    		return response()->json([
    			'duplicate' => $count
    		]);
    	}else {
    		return response()->json([
    			'duplicate' => $count,
    			'assist' => $this->proceedInsert($request)
    		]);
    	}
    }
    private function proceedInsert($request){
    	$assist = new Assist;
    	$assist->fr_name = $request->input('fr_name');
    	$assist->province = $request->input('province');
    	$assist->address = $request->input('address');
    	$assist->amount = $request->input('amount');
    	$assist->date_submitted = $request->input('date_submitted');
    	$assist->type_of_assistance = $request->input('type_of_assistance');
    	$assist->action_taken = '';
    	$assist->save();
    	return $assist;
    }
}
