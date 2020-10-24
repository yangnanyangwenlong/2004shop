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

Route::get('/info', function () {
    phpinfo();
//    return view('welcome');
});

// 前台
Route::prefix('/')->group(function(){
    // 首页\
    Route::prefix('/')->group(function(){
        Route::get('/','Index\IndexController@index');
    });
    // 登录
    Route::prefix('login')->group(function(){
        // 登录
        Route::get('login','Index\LoginController@login');
        // 执行登录
        Route::post('loginDo','Index\LoginController@loginDo');
        // 注册
        Route::get('register','Index\LoginController@register');
        // 执行注册
        Route::post('registerDo','Index\LoginController@registerDo');
        // 退出
        Route::get('exit','Index\LoginController@exit');
        // 确认注册
        Route::get('enroll/{key}','Index\LoginController@enroll');
        // 第三方登录--github
        Route::get('/success','Index\LoginController@success');
    });
    // 商品
    Route::prefix('shop')->group(function(){
        // 商品列表
        Route::get('/','Index\ShopController@list');
        // 非法操作
        Route::get("detail",function (){
            // 非法操作
            return redirect('/');
        });
        // 商品详情
        Route::get("detail/{id}",'Index\ShopController@detail');
    });
    // 购物车
    Route::prefix('cart')->group(function(){
        // 列表
        Route::get('/','Index\CartController@index');
        // 添加
        Route::get('add','Index\CartController@add');
        // 添加完购物车
        Route::get('success','Index\CartController@success');
        // 生成订单
        Route::get('getOrderInfo','Index\CartController@getOrderInfo');
        // 支付
        Route::get('pay','Index\CartController@pay');
    });
    //支付宝支付处理路由
    Route::prefix('alipay')->group(function(){
        // 发起支付请求
        Route::get('/','Index\AlipayController@Alipay');
        //服务器同步通知页面路径
        Route::any('AliPayNotify','Index\AlipayController@AliPayNotify');
        //页面跳转异步通知页面路径
        Route::any('AliPayReturn','Index\AlipayController@AliPayReturn');
    });
    // 个人中心
    Route::prefix('home')->group(function(){
        // 我的订单
        Route::get('/','Index\HomeController@home');
    });
    // 订单
    Route::prefix('order')->group(function(){
        // 购买
        Route::post('pay','Index\OrderController@pay');
    });
});


Route::prefix('test')->group(function(){
    Route::get('curl','Index\TestController@curl');
    Route::get('guzzlehttp','Index\TestController@guzzlehttp');
    Route::get('test','Index\TestController@test');
});
