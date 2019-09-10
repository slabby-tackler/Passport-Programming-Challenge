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

Route::get('/', 'HomeController@index');

Route::get('/factory', 'FactoryController@index');
Route::post('/factory', 'FactoryController@store');
Route::put('/factory/{id}/children', 'FactoryController@generateChildren');
Route::put('/factory/{id}', 'FactoryController@update');
Route::delete('/factory/{id}', 'FactoryController@destroy');
