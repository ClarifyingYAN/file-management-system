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

Route::post('/file/makeFolder', 'FileController@make_folder');
Route::delete('/file/deleteFolder', 'FileController@delete_folder');

Route::delete('/file/deleteFile', 'FileController@delete_file');

Route::post('/file/upload', 'FileController@upload');

//Route::get('test', 'FileController@fill_dir_info');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
