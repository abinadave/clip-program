<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@redirect');

Auth::routes();

Route::get('/home', 'HomeController@index');


Route::group(['middleware' => ['admin']], function () {
    Route::get('admin', function(){
    	return view('layouts.admin');
    });
    Route::post('type', 'TypeController@insert');
    Route::get('type', 'TypeController@fetch');
    Route::delete('type/{id}', 'TypeController@delete');
    Route::get('province', 'ProvinceController@fetchProvinces');
    Route::post('assist', 'AssistController@insert');
    Route::get('granted', 'AssistController@fetch');
    Route::get('assist/display/by/type/{id}', 'AssistController@fetchByType');
    Route::put('granted', 'AssistController@put');
});