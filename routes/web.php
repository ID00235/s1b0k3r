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
	
	//seting role
	Route::group(['prefix'=>'setting-role'], function(){
		Route::get('/', 'SettingController@setting_role');
		Route::get('/dt', 'SettingController@datatable_role');
		Route::get('/get-data/{uuid}', 'SettingController@get_data_role');
		Route::get('/menu/{uuid}', 'SettingController@get_menu_role');
		Route::get('/dt-menu/{uuid}', 'SettingController@datatable_menu_role');
		Route::get('/get-role-menu/{uuid}', 'SettingController@get_data_role_menu');
		Route::post('/insert-role', 'SettingController@submit_insert_role');
		Route::post('/update-role', 'SettingController@submit_update_role');
		Route::post('/delete-role', 'SettingController@submit_delete_role');
		Route::post('/insert-menu', 'SettingController@submit_insert_menu_role');
		Route::post('/update-menu', 'SettingController@submit_update_menu_role');
		Route::post('/delete-menu', 'SettingController@submit_delete_menu_role');
	});

	Route::group(['prefix'=>'setting-user'], function(){
		Route::get('/', 'SettingController@setting_user');
		Route::get('/dt', 'SettingController@datatable_user');
		Route::get('/get-data/{uuid}', 'SettingController@get_data_user');
		Route::post('/insert-user', 'SettingController@submit_insert_user');
		Route::post('/update-user', 'SettingController@submit_update_user');
		Route::post('/delete-user', 'SettingController@submit_delete_user');
	});
});
