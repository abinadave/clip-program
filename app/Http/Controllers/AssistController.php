<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Assist as Assist;

class AssistController extends Controller
{
    public function put(Request $request){
        $this->validate($request, [
            'fr_name' => 'required',
            'province' => 'required',
            'address' => 'required',
            'type_of_assistance' => 'required',
            'amount' => 'required|numeric'
        ]);
        return $this->update($request);
    }
    private function update($request){
        $id = $request->input('id');
        $assist = Assist::findOrFail($id);
        $assist->fr_name = $request->input('fr_name');
        $assist->province = $request->input('province');
        $assist->address = $request->input('address');
        $assist->amount = $request->input('amount');
        $assist->date_submitted = $request->input('date_submitted');
        $assist->type_of_assistance = $request->input('type_of_assistance');
        $assist->action_taken = $request->input('action_taken');
        $rs = $assist->save();
        return response()->json([
            'updated' => $rs
        ]);
    }
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
            'action_taken' => 'required',
	        'date_received' => 'required',
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
    	$assist->action_taken = $request->input('action_taken');
        $assist->date_received = $request->input('date_received');
    	$assist->save();
    	return $assist;
    }
}
