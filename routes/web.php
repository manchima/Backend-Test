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

//Route::get('/', function () {
//    return view('index');
//});

Route::get('/','LoanController@index');
Route::get('/view/{id}','LoanController@view');

Route::get('/create/loan','LoanController@create');
Route::post('/create/loan','LoanController@store');

Route::get('/edit/loan/{id}','LoanController@edit');
Route::post('/edit/loan/{id}','LoanController@update');

Route::patch('/edit/loan/{id}','LoanController@update');
Route::delete('/delete/loan/{id}','LoanController@destroy');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
