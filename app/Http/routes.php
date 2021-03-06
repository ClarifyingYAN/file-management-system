<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::get('file', 'FileController@index');
Route::get('setting', 'SettingsController@index');

Route::post('/file/makeFolder', 'FileController@makeFolder');
Route::delete('/file/deleteFolder', 'FileController@deleteFolder');

Route::delete('/file/deleteFile', 'FileController@deleteFile');

Route::post('/file/upload', 'FileController@upload');

//Route::get('test', 'FileController@download');
Route::post('/file/download', 'FileController@download');


Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
