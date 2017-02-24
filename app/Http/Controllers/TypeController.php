<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Type as Type;

class TypeController extends Controller
{
    public function insert(Request $request){
    	$this->validate($request, [
	        'name' => 'required|unique:types|max:100'
	    ]);
    	$type = new Type;
    	$type->name = $request->input('name');
    	$type->created_by = Auth::user()->id;
    	$type->save();
    	return $type;
    }
    public function fetch(){
    	return response()->json([
    		'types' => Type::orderBy('id','desc')->get()
    	]);
    }
    public function delete($id){
    	$count = Type::where('id', $id)->count();
    	if ($count > 0) {
    		$type = Type::find($id);
    		$rs = $type->delete();
    		return response()->json([
    			'deleted' => true
    		]);
    	}else {
    		return response()->json([
    			'deleted' => false
    		]);
    	}
    }
}
