<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\WxUserModel;
class ApiController extends Controller
{
    

    //api
    public function userinfo(){
    	echo __METHOD__;
    }
    
    public function test(){
    	$goods_info = [
    		'goods_id' => '123',
    		'goods_name' => '老武',
    		'price' => '0.00'
    	];
    	echo (json_encode($goods_info));
    }

    public function __construct(){
        app('debugbar')->disable();     //关闭调试
    }

    //登录
    public function long(Request $request){
    	//接收code
    	$code = $request->get('code');

    	//使用code
    	$url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.env('WX_XCX_APPID').'&secret='.env('WX_XCX_SECRET').'&js_code='.$code.'&grant_type=authorization_code';
    	$data = json_decode(file_get_contents($url),true);
        $openid = $data['openid'];
    	echo '<pre>';print_r($data);echo '</pre>';
        
    	//自定义登录状态
    	if(isset($data['errcode'])){
    		//错误
    		$response = [
    			'errno' => 50001,
    			'msg' => '登录失败',
    		];
    	}else{
    		$token = sha1($data['openid'] . $data['session_key'].mt_rand(0,999999));

    		//保存事件
    		$redis_key = 'xcx_token:'.$token;
    		Redis::set($redis_key,time());
    		Redis::expire($redis_key,7200);
    		$response = [
    			"openid" => $openid
    		];
            // echo $response;die;
            WxUserModel::insert($response);
    	
            $response = [
                'error' => 400001,
                'msg' => '已登录'
            ];
        }
    	return $response;

    }

    //商品
    // public function detail(){
    //     $data = ......
    //     rrr
    // }
    
}
