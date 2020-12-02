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

Route::prefix('draw')->group(function(){
    //抽奖
    Route::get('/','DrawController@create');
    Route::get('enit','DrawController@enit');
});

//电影座次
Route::prefix('film')->group(function(){
    Route::get('/','FilmController@create');
    //递归
    Route::get('dg','FilmController@dg');
});

//优惠
// Route::prefix()

//购物车数据模拟
Route::prefix('yangnan')->group(function(){
    Route::get('/','YangnanController@index');  //购物车首页
    Route::get('/ppt','YangnanController@ppt');  //购物车首页
});

//小程序接口
Route::prefix('/apis')->group(function(){
    Route::get('/home-login','Weixin\ApiController@homeLogin');     //小程序首页登录
    Route::post('/user-login','Weixin\ApiController@userLogin');     //个人中心登录
    Route::get('/userinfo','Weixin\ApiController@userInfo');
    Route::get('/goodslist','Weixin\ApiController@goodsList');      //商品列表
    Route::get('/goods','Weixin\ApiController@goodsInfo');          //商品详情
    Route::post('/add-cart','Weixin\ApiController@addCart')->middleware('check.token');          //加入购物车
    Route::get('/adduser','Weixin\ApiController@addUser');          //添加用户
    Route::get('/cart-list','Weixin\ApiController@cartList');          //购物车列表
    Route::get('/add-fav','Weixin\ApiController@addFav');          //加入收藏
});

//微信

Route::prefix('weixin')->group(function(){
        //微信开发者服务器接入(即支持get又支持post)
    Route::match(['get','post'],'/wx','WeixinController@checkSignature');
    //上传素材{图片}
    Route::get('/guzzle2','WeixinController@guzzle2');
    //自定义菜单
    Route::get('/create_menu','WeixinController@create_menu');
    //上传素材{视频}
    Route::get('/shengpin','WeixinController@shengpin');
    //获取access_token
    Route::get('/access_token','WeixinController@access_token');
    //天气(780)
    Route::get('/weather1','WeixinController@weather1');
    //

    //测试1
    Route::get('/weather','WeixinController@weather');
    //测试2
    Route::get('/test','WeixinController@test');
    //接口测试
    Route::get('/test6','WeixinController@test6');
    //测试3(postman)
    Route::get('test2','WeixinController@test2');//get
    Route::post('test3','WeixinController@test3');//post(form-data)
    Route::post('test4','WeixinController@test4');//post(raw)
});
 //api
Route::prefix('api')->group(function(){
    
    Route::get('/userinfo','WeixinController@userinfo');
    Route::get('/test','WeixinController@test');
    Route::get('/long','WeixinController@long');//登录
    Route::get('/goodslist','Index\ShopController@goodslist');//
    Route::get('/goods','Index\ShopController@goods');//
    Route::get('/opendid','OpenidController@index');
});

//盒子 div
Route::get('home','FilmController@home');

