<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function redirect(){
        if (Auth::check() && Auth::user()->admin == 1) {
            return view('layouts.admin');
        }elseif(Auth::check()){
            echo "user la";
        }else {
            return view('home');
        }
    }
}
