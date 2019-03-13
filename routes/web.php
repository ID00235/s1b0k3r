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

Route::get('/login','LoginController@page_login');
Route::post('/submit-login','LoginController@submit_login');
Route::get('/logout','LoginController@logout');
Route::get('/genuuid','LoginController@gen_uuid');


Route::group(["middleware"=>['auth.login','auth.menu']], function(){

	Route::get('/','HomeController@beranda');
	Route::group(['prefix'=>'setting-menu'], function(){
		Route::get('/', 'SettingController@setting_menu');
		Route::get('/dt', 'SettingController@datatable_menu');
		Route::get('/get-data/{uuid}', 'SettingController@get_data_menu');
		Route::post('/insert-menu', 'SettingController@submit_insert_menu');
		Route::post('/update-menu', 'SettingController@submit_update_menu');
	});
	
});