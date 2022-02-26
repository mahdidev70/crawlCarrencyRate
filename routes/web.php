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
Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix'=>'current'], function(){
    Route::get('/usd',  'FirstController@currentUsd');
    Route::get('/euro',  'FirstController@currentEuro');
});

Route::group(['prefix'=>'history'], function(){
    Route::get('/usd',  'FirstController@todayUsd');
    Route::get('/euro',  'FirstController@todayEuro');
    Route::get('/ratio',  'FirstController@percent');
});


