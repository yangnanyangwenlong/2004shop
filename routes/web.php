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
//-----------------------登录 ;注册------------------------------------
//登录 ;注册
Route::prefix('login')->group(function(){
	Route::get('/','Login\LoginController@login');		//登录 
	Route::post('/veryif','Login\LoginController@veryif');		//登录处理

	Route::get('/register','Login\RegisterController@register'); 	//注册
	Route::post('registerdo','Login\RegisterController@registerdo'); // 注册处理
	
});

// Route::git('/login','Login\LoginController@login');

//------------------------ 首页  -------------------------------------

Route::prefix('index')->group(function(){
	Route::get('/','Index\IndexController@index');
});

//--------------------------贪吃蛇 -----------------------------
Route::get('gluttonous','GluttonousController@index');